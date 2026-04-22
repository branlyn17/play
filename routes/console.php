<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Template;
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
