<?php

namespace App\Support\Catalog;

use App\Models\Event;
use App\Models\Invitation;
use App\Models\Template;
use App\Support\Templates\TemplateMetricTracker;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class InvitationDraftService
{
    public static function createFromTemplate(Template $template, string $locale): Invitation
    {
        $fallbackLocale = config('locales.fallback', 'en');
        $translation = self::resolveTranslation($template->translations, $locale, $fallbackLocale);
        $blueprint = TemplateEditorBlueprint::resolve($template, $locale);
        $initialEditorState = TemplateEditorBlueprint::defaultEditorState($template, $locale);
        $title = $initialEditorState['headline'] ?? $translation?->name ?? $template->code;
        $description = $initialEditorState['subheadline'] ?? $translation?->teaser ?? $translation?->description;
        $parts = TemplateEditorBlueprint::splitEditorState($template, $initialEditorState);
        $eventTiming = self::resolveEventTiming($initialEditorState);

        return DB::transaction(function () use ($template, $locale, $title, $description, $initialEditorState, $parts, $blueprint, $eventTiming) {
            $event = Event::create([
                'title' => $title,
                'description' => $description,
                'starts_at' => $eventTiming['starts_at'],
                'timezone' => $eventTiming['timezone'],
                'venue_name' => ($initialEditorState['venueName'] ?? null) ?: ($initialEditorState['venueLabel'] ?? null),
                'address_line' => $initialEditorState['venueAddress'] ?? null,
                'location_url' => $initialEditorState['googleMapsUrl'] ?? null,
                'privacy' => 'unlisted',
                'status' => 'draft',
            ]);

            $invitation = Invitation::create([
                'event_id' => $event->id,
                'template_id' => $template->id,
                'title' => $title,
                'description' => $description,
                'locale' => $locale,
                'edit_token' => (string) Str::uuid(),
                'public_token' => (string) Str::uuid(),
                'status' => 'draft',
                'editor_state' => array_merge($initialEditorState, [
                    '_meta' => [
                        'locale' => $locale,
                        'resolved_locale' => $blueprint['resolvedLocale'],
                    ],
                ]),
                'customization_data' => $parts['content'],
                'style_overrides' => $parts['style'],
            ]);

            TemplateMetricTracker::recordUse($template, $invitation);
            self::syncMediaItems($invitation, $parts['media']);

            return $invitation;
        });
    }

    public static function persistEditorState(Invitation $invitation, array $editorState, string $htmlDocument, bool $downloaded = false): Invitation
    {
        $parts = TemplateEditorBlueprint::splitEditorState($invitation->template, $editorState);
        $styleOverrides = $parts['style'];
        $customizationData = $parts['content'];
        $path = 'invitations/'.$invitation->edit_token.'.html';

        Storage::disk('public')->put($path, $htmlDocument);

        $invitation->fill([
            'title' => $editorState['headline'] ?? $invitation->title,
            'description' => $editorState['subheadline'] ?? $invitation->description,
            'customization_data' => $customizationData,
            'style_overrides' => $styleOverrides,
            'editor_state' => array_merge($editorState, [
                '_meta' => [
                    'locale' => $invitation->locale,
                ],
            ]),
            'rendered_html_path' => $path,
            'rendered_html_checksum' => hash('sha256', $htmlDocument),
        ]);

        if ($downloaded) {
            $invitation->status = 'published';
            $invitation->published_at ??= now();
        }

        $invitation->save();
        $eventTiming = self::resolveEventTiming($editorState);

        $invitation->event?->update([
            'title' => ($editorState['eventName'] ?? null) ?: ($editorState['headline'] ?? $invitation->title),
            'description' => $editorState['subheadline'] ?? $invitation->description,
            'starts_at' => $eventTiming['starts_at'],
            'timezone' => $eventTiming['timezone'],
            'venue_name' => ($editorState['venueName'] ?? null) ?: ($editorState['venueLabel'] ?? null),
            'address_line' => $editorState['venueAddress'] ?? null,
            'location_url' => $editorState['googleMapsUrl'] ?? null,
        ]);
        self::syncMediaItems($invitation, $parts['media']);

        if ($downloaded) {
            $invitation = TemplateMetricTracker::recordDownload($invitation);
        }

        return $invitation->fresh(['template']);
    }

    protected static function resolveEventTiming(array $editorState): array
    {
        $timezone = self::validTimezone($editorState['timezoneLabel'] ?? null);
        $date = $editorState['dateLabel'] ?? null;
        $time = $editorState['timeLabel'] ?? null;
        $startsAt = null;

        if (is_string($date) && is_string($time) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && preg_match('/^\d{2}:\d{2}$/', $time)) {
            try {
                $startsAt = CarbonImmutable::createFromFormat('Y-m-d H:i', "{$date} {$time}", $timezone)->utc();
            } catch (Throwable) {
                $startsAt = null;
            }
        }

        return [
            'starts_at' => $startsAt,
            'timezone' => $timezone,
        ];
    }

    protected static function validTimezone(?string $timezone): string
    {
        if ($timezone && in_array($timezone, timezone_identifiers_list(), true)) {
            return $timezone;
        }

        return config('app.timezone', 'UTC');
    }

    protected static function syncMediaItems(Invitation $invitation, array $media): void
    {
        $items = [];

        foreach (['hero', 'background'] as $role) {
            $item = $media[$role] ?? [];
            $url = trim((string) ($item['url'] ?? ''));

            if ($url === '') {
                continue;
            }

            $items[] = [
                'role' => $role,
                'url' => $url,
                'alt_text' => $item['alt'] ?? null,
                'caption' => null,
                'sort_order' => 0,
            ];
        }

        foreach (($media['gallery'] ?? []) as $index => $item) {
            $url = trim((string) ($item['url'] ?? ''));

            if ($url === '') {
                continue;
            }

            $items[] = [
                'role' => 'gallery',
                'url' => $url,
                'alt_text' => $item['alt'] ?? null,
                'caption' => $item['caption'] ?? null,
                'sort_order' => $index + 1,
            ];
        }

        $invitation->mediaItems()->delete();
        $invitation->mediaItems()->createMany($items);
    }

    protected static function resolveTranslation(Collection $translations, string $locale, string $fallbackLocale): mixed
    {
        return $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', $fallbackLocale)
            ?? $translations->first();
    }
}
