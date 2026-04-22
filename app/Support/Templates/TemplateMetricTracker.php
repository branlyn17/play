<?php

namespace App\Support\Templates;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\TemplateMetricEvent;
use App\Support\Analytics\TemplateAnalyticsRecorder;

class TemplateMetricTracker
{
    public static function recordView(Template $template, ?Invitation $invitation = null): Template
    {
        Template::query()
            ->whereKey($template->getKey())
            ->increment('view_count');

        if ($invitation) {
            Invitation::query()
                ->whereKey($invitation->getKey())
                ->increment('view_count', 1, ['last_viewed_at' => now()]);
        }

        app(TemplateAnalyticsRecorder::class)->record($template, TemplateMetricEvent::TYPE_VIEW, $invitation);

        return $template->fresh(['translations', 'category.translations']) ?? $template;
    }

    public static function recordUse(Template $template, ?Invitation $invitation = null): void
    {
        Template::query()
            ->whereKey($template->getKey())
            ->increment('use_count');

        app(TemplateAnalyticsRecorder::class)->record($template, TemplateMetricEvent::TYPE_USE, $invitation);
    }

    public static function recordDownload(Invitation $invitation): Invitation
    {
        Invitation::query()
            ->whereKey($invitation->getKey())
            ->increment('download_count', 1, ['last_downloaded_at' => now()]);

        if ($invitation->template_id) {
            Template::query()
                ->whereKey($invitation->template_id)
                ->increment('download_count');

            $template = $invitation->template ?: Template::query()->find($invitation->template_id);

            if ($template) {
                app(TemplateAnalyticsRecorder::class)->record($template, TemplateMetricEvent::TYPE_DOWNLOAD, $invitation);
            }
        }

        return $invitation->fresh(['template']) ?? $invitation;
    }
}
