<?php

namespace App\Support\Catalog;

use App\Models\Template;
use App\Support\Localization\PublicPage;
use Illuminate\Support\Collection;
use Throwable;

class PublicHomeFeaturedSlides
{
    public static function make(string $locale, int $limit = 3): array
    {
        $fallbackLocale = config('locales.fallback', 'en');

        try {
            $templates = Template::query()
                ->where('is_active', true)
                ->whereNotNull('preview_image_path')
                ->where(function ($query) {
                    $query->where('is_featured', true)
                        ->orWhere('is_premium', true);
                })
                ->with(['translations', 'category.translations'])
                ->inRandomOrder()
                ->limit($limit)
                ->get();

            if ($templates->count() < $limit) {
                $extraTemplates = Template::query()
                    ->where('is_active', true)
                    ->whereNotNull('preview_image_path')
                    ->whereNotIn('id', $templates->pluck('id'))
                    ->with(['translations', 'category.translations'])
                    ->inRandomOrder()
                    ->limit($limit - $templates->count())
                    ->get();

                $templates = $templates->merge($extraTemplates);
            }

            return $templates
                ->map(fn (Template $template) => self::present($template, $locale, $fallbackLocale))
                ->values()
                ->all();
        } catch (Throwable) {
            return [];
        }
    }

    private static function present(Template $template, string $locale, string $fallbackLocale): array
    {
        $translation = self::resolveTranslation($template->translations, $locale, $fallbackLocale);
        $categoryTranslation = $template->category
            ? self::resolveTranslation($template->category->translations, $locale, $fallbackLocale)
            : null;
        $designTokens = $template->design_tokens ?? [];

        return [
            'id' => $template->code,
            'category' => $categoryTranslation?->name ?? __('public.catalog.uncategorized_label'),
            'title' => $translation?->name ?? $template->code,
            'caption' => $translation?->teaser ?? $translation?->description ?? '',
            'label' => $template->is_featured ? __('public.catalog.featured_badge') : __('public.catalog.ready_badge'),
            'preview' => $translation?->name ?? $template->code,
            'microcopy' => $translation?->description ?? $translation?->teaser ?? '',
            'year' => now()->year,
            'background' => $designTokens['catalog_background'] ?? 'linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)',
            'imageUrl' => self::assetUrl($template->preview_image_path),
            'thumbnailUrl' => self::assetUrl($template->thumbnail_image_path),
            'href' => route(PublicPage::routeName('catalog.show', $locale), [
                'slug' => $translation?->slug ?? $template->code,
            ]),
        ];
    }

    private static function resolveTranslation(Collection $translations, string $locale, string $fallbackLocale): mixed
    {
        return $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', $fallbackLocale)
            ?? $translations->first();
    }

    private static function assetUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
