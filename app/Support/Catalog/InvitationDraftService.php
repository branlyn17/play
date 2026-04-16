<?php

namespace App\Support\Catalog;

use App\Models\Event;
use App\Models\Invitation;
use App\Models\Template;
use Illuminate\Support\Arr;
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
        $title = $translation?->name ?? $template->code;
        $description = $translation?->teaser ?? $translation?->description;

        return DB::transaction(function () use ($template, $locale, $title, $description) {
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
                'editor_state' => $template->default_content ?? [],
                'customization_data' => $template->default_content ?? [],
                'style_overrides' => $template->design_tokens ?? [],
            ]);

            $template->increment('use_count');

            return $invitation;
        });
    }

    public static function persistEditorState(Invitation $invitation, array $editorState, string $htmlDocument, bool $downloaded = false): Invitation
    {
        $styleOverrides = Arr::only($editorState, [
            'accentColor',
            'backgroundColor',
            'surfaceColor',
            'textColor',
            'fontFamily',
        ]);

        $customizationData = Arr::except($editorState, array_keys($styleOverrides));
        $path = 'invitations/'.$invitation->edit_token.'.html';

        Storage::disk('public')->put($path, $htmlDocument);

        $invitation->fill([
            'title' => $editorState['headline'] ?? $invitation->title,
            'description' => $editorState['subheadline'] ?? $invitation->description,
            'customization_data' => $customizationData,
            'style_overrides' => $styleOverrides,
            'editor_state' => $editorState,
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
