<?php

namespace App\Support\Templates;

use App\Models\Template;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Throwable;

class TemplatePreviewGenerator
{
    public function __construct(
        private readonly TemplateHtmlRenderer $renderer,
    ) {}

    public function generate(Template $template, ?string $locale = null, bool $force = false): bool
    {
        if (! config('template_previews.enabled', true)) {
            return false;
        }

        if (! $force && $template->preview_image_path && File::exists(public_path($template->preview_image_path))) {
            return true;
        }

        $chromePath = $this->chromePath();

        if (! $chromePath) {
            Log::warning('Template preview generation skipped because Chrome was not found.', [
                'template' => $template->code,
            ]);

            return false;
        }

        try {
            $locale ??= $template->default_locale ?: config('locales.default', 'es');
            $html = $this->renderer->render($template, $locale);
            $tmpDirectory = storage_path('app/private/template-previews/tmp/'.$template->code);
            $publicDirectory = public_path(trim(config('template_previews.directory', 'template-previews'), '/').'/'.$template->code);

            File::ensureDirectoryExists($tmpDirectory);
            File::ensureDirectoryExists($publicDirectory);

            $htmlPath = $tmpDirectory.'/preview.html';
            $previewPath = $publicDirectory.'/preview.png';
            $thumbnailPath = $publicDirectory.'/thumbnail.png';

            File::put($htmlPath, $html);

            $chromeProfileDirectory = $tmpDirectory.'/chrome-profile';
            File::ensureDirectoryExists($chromeProfileDirectory);

            $this->capture($chromePath, $htmlPath, $previewPath, $chromeProfileDirectory, [
                'width' => (int) config('template_previews.viewport.width', 430),
                'height' => (int) config('template_previews.viewport.height', 760),
            ]);

            $this->capture($chromePath, $htmlPath, $thumbnailPath, $chromeProfileDirectory, [
                'width' => (int) config('template_previews.thumbnail.width', 360),
                'height' => (int) config('template_previews.thumbnail.height', 520),
            ]);

            $directory = trim(config('template_previews.directory', 'template-previews'), '/');

            $template->forceFill([
                'preview_image_path' => $directory.'/'.$template->code.'/preview.png',
                'thumbnail_image_path' => $directory.'/'.$template->code.'/thumbnail.png',
            ])->save();

            return true;
        } catch (Throwable $exception) {
            Log::warning('Template preview generation failed.', [
                'template' => $template->code,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function capture(string $chromePath, string $htmlPath, string $outputPath, string $profileDirectory, array $viewport): void
    {
        if (File::exists($outputPath)) {
            File::delete($outputPath);
        }

        $process = new Process([
            $chromePath,
            '--headless=new',
            '--disable-gpu',
            '--disable-extensions',
            '--disable-background-networking',
            '--disable-breakpad',
            '--disable-crash-reporter',
            '--disable-crashpad',
            '--disable-dev-shm-usage',
            '--disable-features=Crashpad',
            '--disable-sync',
            '--hide-scrollbars',
            '--no-sandbox',
            '--no-first-run',
            '--no-default-browser-check',
            '--allow-file-access-from-files',
            '--user-data-dir='.$profileDirectory,
            '--window-size='.$viewport['width'].','.$viewport['height'],
            '--screenshot='.$outputPath,
            $this->fileUrl($htmlPath),
        ]);

        $process->setTimeout((int) config('template_previews.timeout', 30));
        $process->run();

        if (! $process->isSuccessful() || ! File::exists($outputPath)) {
            throw new \RuntimeException(trim($process->getErrorOutput() ?: $process->getOutput()) ?: 'Chrome did not create the screenshot.');
        }
    }

    private function chromePath(): ?string
    {
        $configuredPath = config('template_previews.chrome_path');

        if ($configuredPath && File::exists($configuredPath)) {
            return $configuredPath;
        }

        $candidates = [
            'C:\Program Files\Google\Chrome\Application\chrome.exe',
            'C:\Program Files (x86)\Google\Chrome\Application\chrome.exe',
            (string) ($_SERVER['LOCALAPPDATA'] ?? '').'\Google\Chrome\Application\chrome.exe',
            'C:\Program Files\Microsoft\Edge\Application\msedge.exe',
            'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe',
            (string) ($_SERVER['LOCALAPPDATA'] ?? '').'\Microsoft\Edge\Application\msedge.exe',
            '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
            '/usr/bin/google-chrome',
            '/usr/bin/google-chrome-stable',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser',
        ];

        foreach ($candidates as $candidate) {
            if ($candidate && File::exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function fileUrl(string $path): string
    {
        $realPath = realpath($path) ?: $path;
        $normalizedPath = str_replace('\\', '/', $realPath);

        if (preg_match('/^[A-Za-z]:\//', $normalizedPath)) {
            return 'file:///'.$normalizedPath;
        }

        return 'file://'.$normalizedPath;
    }
}
