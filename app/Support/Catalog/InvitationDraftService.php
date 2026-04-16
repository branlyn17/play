<?php

namespace App\Support\Catalog;

use App\Models\Event;
use App\Models\Invitation;
use App\Models\Template;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        return DB::transaction(function () use ($template, $locale, $title, $description, $initialEditorState, $parts, $blueprint) {
            $event = Event::create([
                'title' => $title,
                'description' => $description,
                'timezone' => config('app.timezone', 'UTC'),
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

            $template->increment('use_count');

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
            $invitation->last_downloaded_at = now();
            $invitation->download_count++;

            if ($invitation->template) {
                $invitation->template->increment('download_count');
            }
        }

        $invitation->save();

        return $invitation->fresh(['template']);
    }

    protected static function resolveTranslation(Collection $translations, string $locale, string $fallbackLocale): mixed
    {
        return $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', $fallbackLocale)
            ?? $translations->first();
    }
}
