<?php

namespace App\Support\Catalog;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\TemplateTranslation;
use App\Support\Localization\PublicPage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PublicTemplateEditorData
{
    public static function findTemplateBySlug(string $locale, string $slug): ?Template
    {
        try {
            $translation = TemplateTranslation::query()
                ->where('locale', $locale)
                ->where('slug', $slug)
                ->with([
                    'template.translations',
                    'template.category.translations',
                ])
                ->first();
        } catch (Throwable) {
            return null;
        }

        if (! $translation) {
            return null;
        }

        return $translation->template;
    }

    public static function findInvitationByEditToken(string $editToken): ?Invitation
    {
        try {
            return Invitation::query()
                ->where('edit_token', $editToken)
                ->with([
                    'template.translations',
                    'template.category.translations',
                ])
                ->first();
        } catch (Throwable) {
            return null;
        }
    }

    public static function present(Template $template, string $locale, ?Invitation $invitation = null): array
    {
        $fallbackLocale = config('locales.fallback', 'en');

        $template->increment('view_count');
        $template->refresh();
        $template->loadMissing(['translations', 'category.translations']);

        $resolvedTranslation = self::resolveTranslation($template->translations, $locale, $fallbackLocale);
        $categoryTranslation = $template->category
            ? self::resolveTranslation($template->category->translations, $locale, $fallbackLocale)
            : null;
        $blueprint = TemplateEditorBlueprint::resolve($template, $locale);

        $designTokens = $template->design_tokens ?? [];
        $htmlSource = null;

        if ($template->source_html_path && Storage::exists($template->source_html_path)) {
            $htmlSource = Storage::get($template->source_html_path);
        }

        return [
            'template' => [
                'id' => $template->id,
                'code' => $template->code,
                'name' => $resolvedTranslation?->name ?? $template->code,
                'slug' => $resolvedTranslation?->slug ?? $template->code,
                'teaser' => $resolvedTranslation?->teaser,
                'description' => $resolvedTranslation?->description,
                'categoryName' => $categoryTranslation?->name ?? __('public.catalog.uncategorized_label'),
                'isPremium' => (bool) $template->is_premium,
                'viewCount' => (int) $template->view_count,
                'downloadCount' => (int) $template->download_count,
                'useCount' => (int) $template->use_count,
                'designTokens' => $designTokens,
                'availableFonts' => $template->available_fonts ?? [],
                'availableColors' => $template->available_colors ?? [],
                'htmlSource' => $htmlSource,
                'editorSchema' => $blueprint['schema'],
                'editorFields' => $blueprint['fields'],
                'defaultContent' => [
                    'content' => $blueprint['contentDefaults'],
                    'style' => $blueprint['styleDefaults'],
                    'resolvedLocale' => $blueprint['resolvedLocale'],
                ],
                'dictionary' => $blueprint['dictionary'],
                'savedState' => $invitation?->editor_state ?? [],
            ],
            'locales' => self::localeOptionsForTemplate($template, $invitation),
        ];
    }

    public static function localeOptionsForTemplate(Template $template, ?Invitation $invitation = null): array
    {
        $options = [];

        foreach (config('locales.supported', []) as $code => $meta) {
            $translation = $template->translations->firstWhere('locale', $code);
            $query = [];

            if ($invitation && $invitation->locale === $code) {
                $query['edit'] = $invitation->edit_token;
            }

            $options[] = [
                'code' => $code,
                'label' => $meta['label'],
                'name' => $meta['name'],
                'flag' => $meta['flag'],
                'href' => $translation
                    ? route(PublicPage::routeName('catalog.show', $code), array_merge(['slug' => $translation->slug], $query))
                    : route(PublicPage::routeName('catalog', $code)),
            ];
        }

        return $options;
    }

    protected static function resolveTranslation(Collection $translations, string $locale, string $fallbackLocale): mixed
    {
        return $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', $fallbackLocale)
            ?? $translations->first();
    }
}
