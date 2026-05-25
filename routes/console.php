<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Models\Template;
use App\Support\Localization\LocaleConfig;
use App\Support\Templates\TemplatePreviewGenerator;
use Symfony\Component\Console\Command\Command;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('templates:generate-previews {code? : Template code to regenerate} {--locale= : Locale used for the preview content} {--force : Regenerate even when an image already exists}', function (TemplatePreviewGenerator $generator) {
    $query = Template::query()->with(['translations', 'category.translations'])->orderBy('sort_order');
    $code = $this->argument('code');
    $locale = $this->option('locale') ?: config('locales.default', 'es');
    $force = (bool) $this->option('force');

    if ($code) {
        $query->where('code', $code);
    }

    $templates = $query->get();

    if ($templates->isEmpty()) {
        $this->warn('No templates found.');

        return Command::FAILURE;
    }

    $generated = 0;

    foreach ($templates as $template) {
        $this->line("Generating preview for {$template->code}...");

        if ($generator->generate($template, $locale, $force)) {
            $generated++;
            $this->info("Preview ready for {$template->code}.");
        } else {
            $this->warn("Preview skipped for {$template->code}. Check the Laravel log for details.");
        }
    }

    $this->info("Finished. {$generated} of {$templates->count()} previews ready.");

    return Command::SUCCESS;
})->purpose('Generate public preview images for invitation templates');

Artisan::command('templates:sync-metrics', function () {
    $templates = Template::query()
        ->withCount('invitations')
        ->withSum('invitations as invitations_view_count', 'view_count')
        ->withSum('invitations as invitations_download_count', 'download_count')
        ->get();

    foreach ($templates as $template) {
        $template->forceFill([
            'view_count' => (int) ($template->invitations_view_count ?? 0),
            'download_count' => (int) ($template->invitations_download_count ?? 0),
            'use_count' => (int) $template->invitations_count,
        ])->save();
    }

    $this->info("Synced metrics for {$templates->count()} templates.");

    return Command::SUCCESS;
})->purpose('Synchronize template counters from real invitation activity');

Artisan::command('i18n:check', function () {
    $configuredLocales = collect(array_keys(config('locales.supported', [])))->values();
    $jsonFiles = collect(File::files(lang_path()))
        ->filter(fn ($file) => $file->getExtension() === 'json')
        ->map(fn ($file) => $file->getFilenameWithoutExtension())
        ->values();

    $missingFiles = $configuredLocales->diff($jsonFiles)->values();
    $extraFiles = $jsonFiles->diff($configuredLocales)->values();
    $baseLocale = LocaleConfig::fallback();
    $basePath = lang_path("{$baseLocale}.json");

    if (! File::exists($basePath)) {
        $this->error("Base locale file not found: {$basePath}");

        return Command::FAILURE;
    }

    $decode = function (string $locale): array {
        $path = lang_path("{$locale}.json");

        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode(File::get($path), true);

        return is_array($decoded) ? $decoded : [];
    };

    $baseMessages = collect($decode($baseLocale))
        ->filter(fn ($value, $key) => is_string($key) && is_string($value))
        ->all();

    $hasErrors = false;

    if ($missingFiles->isNotEmpty()) {
        $hasErrors = true;
        $this->error('Configured locales without JSON file: '.$missingFiles->implode(', '));
    }

    if ($extraFiles->isNotEmpty()) {
        $hasErrors = true;
        $this->error('JSON files not declared in config/locales.php: '.$extraFiles->implode(', '));
    }

    foreach ($configuredLocales as $locale) {
        $messages = collect($decode($locale))
            ->filter(fn ($value, $key) => is_string($key) && is_string($value))
            ->all();

        if ($messages === []) {
            $hasErrors = true;
            $this->error("Locale {$locale} has no readable JSON messages.");
            continue;
        }

        $missingKeys = array_values(array_diff(array_keys($baseMessages), array_keys($messages)));
        $extraKeys = array_values(array_diff(array_keys($messages), array_keys($baseMessages)));
        $emptyKeys = collect($messages)
            ->filter(fn ($value) => trim($value) === '')
            ->keys()
            ->values()
            ->all();

        if ($missingKeys !== []) {
            $hasErrors = true;
            $this->error("Locale {$locale} is missing ".count($missingKeys).' keys.');
            $this->line('  Missing sample: '.collect($missingKeys)->take(10)->implode(', '));
        }

        if ($extraKeys !== []) {
            $hasErrors = true;
            $this->error("Locale {$locale} has ".count($extraKeys).' extra keys.');
            $this->line('  Extra sample: '.collect($extraKeys)->take(10)->implode(', '));
        }

        if ($emptyKeys !== []) {
            $hasErrors = true;
            $this->error("Locale {$locale} has ".count($emptyKeys).' empty values.');
            $this->line('  Empty sample: '.collect($emptyKeys)->take(10)->implode(', '));
        }

        if ($missingKeys === [] && $extraKeys === [] && $emptyKeys === []) {
            $this->info("Locale {$locale} is in sync.");
        }
    }

    if ($hasErrors) {
        return Command::FAILURE;
    }

    $this->info('i18n catalog check passed.');

    return Command::SUCCESS;
})->purpose('Validate JSON translation integrity against configured locales');
