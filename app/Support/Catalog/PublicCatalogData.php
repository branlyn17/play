<?php

namespace App\Support\Catalog;

use App\Models\InvitationCategory;
use App\Models\Template;
use App\Support\Localization\PublicPage;
use Illuminate\Support\Collection;
use Throwable;

class PublicCatalogData
{
    public static function make(string $locale): array
    {
        $fallbackLocale = config('locales.fallback', 'en');

        try {
            $categories = InvitationCategory::query()
                ->where('is_active', true)
                ->with('translations')
                ->withCount([
                    'templates as active_templates_count' => fn ($query) => $query->where('is_active', true),
                ])
                ->orderBy('sort_order')
                ->get()
                ->map(function (InvitationCategory $category) use ($locale, $fallbackLocale) {
                    $translation = self::resolveTranslation($category->translations, $locale, $fallbackLocale);

                    return [
                        'id' => $category->id,
                        'key' => $category->key,
                        'name' => $translation?->name ?? $category->key,
                        'slug' => $translation?->slug ?? $category->key,
                        'description' => $translation?->description,
                        'templateCount' => $category->active_templates_count,
                    ];
                })
                ->filter(fn (array $category) => $category['templateCount'] > 0)
                ->values();

            $templates = Template::query()
                ->where('is_active', true)
                ->with(['translations', 'category.translations'])
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->get()
                ->map(function (Template $template) use ($locale, $fallbackLocale) {
                    $translation = self::resolveTranslation($template->translations, $locale, $fallbackLocale);
                    $categoryTranslation = $template->category
                        ? self::resolveTranslation($template->category->translations, $locale, $fallbackLocale)
                        : null;

                    $designTokens = $template->design_tokens ?? [];
                    $defaultContent = $template->default_content ?? [];
                    $preview = $defaultContent['preview'] ?? [];

                    return [
                        'id' => $template->id,
                        'code' => $template->code,
                        'name' => $translation?->name ?? $template->code,
                        'slug' => $translation?->slug ?? $template->code,
                        'teaser' => $translation?->teaser,
                        'description' => $translation?->description,
                        'categoryKey' => $template->category?->key,
                        'categoryName' => $categoryTranslation?->name ?? __('public.catalog.uncategorized_label'),
                        'href' => route(PublicPage::routeName('catalog.show', $locale), [
                            'slug' => $translation?->slug ?? $template->code,
                        ]),
                        'isFeatured' => (bool) $template->is_featured,
                        'isPremium' => (bool) $template->is_premium,
                        'viewCount' => (int) $template->view_count,
                        'downloadCount' => (int) $template->download_count,
                        'useCount' => (int) $template->use_count,
                        'previewImagePath' => $template->preview_image_path,
                        'previewImageUrl' => self::assetUrl($template->preview_image_path),
                        'thumbnailImageUrl' => self::assetUrl($template->thumbnail_image_path),
                        'background' => $designTokens['catalog_background'] ?? self::fallbackBackground($template->code),
                        'accent' => $designTokens['accent'] ?? 'sky',
                        'badge' => $preview['badge'] ?? ($template->is_featured ? __('public.catalog.featured_badge') : __('public.catalog.ready_badge')),
                        'mood' => $preview['mood'] ?? __('public.catalog.editor_ready_label'),
                    ];
                })
                ->values();
        } catch (Throwable) {
            return [
                'categories' => [],
                'templates' => [],
                'categoryCount' => 0,
                'templateCount' => 0,
            ];
        }

        return [
            'categories' => $categories->all(),
            'templates' => $templates->all(),
            'categoryCount' => $categories->count(),
            'templateCount' => $templates->count(),
        ];
    }

    protected static function resolveTranslation(Collection $translations, string $locale, string $fallbackLocale): mixed
    {
        return $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', $fallbackLocale)
            ?? $translations->first();
    }

    protected static function fallbackBackground(string $code): string
    {
        return match ($code) {
            'aura' => 'linear-gradient(160deg, rgba(255,255,255,0.15), rgba(255,255,255,0.03)), radial-gradient(circle at top left, rgba(191,219,254,0.55), transparent 32%), radial-gradient(circle at bottom right, rgba(129,140,248,0.28), transparent 30%), linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)',
            'luna' => 'linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(96,165,250,0.24), transparent 28%), radial-gradient(circle at bottom left, rgba(244,114,182,0.18), transparent 24%), linear-gradient(135deg, #1e1b4b, #172554, #0f172a)',
            'sky' => 'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03)), radial-gradient(circle at top, rgba(186,230,253,0.5), transparent 30%), radial-gradient(circle at bottom right, rgba(125,211,252,0.22), transparent 24%), linear-gradient(135deg, #f0f9ff, #dbeafe, #e0e7ff)',
            default => 'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)), radial-gradient(circle at top left, rgba(224,242,254,0.8), transparent 34%), radial-gradient(circle at bottom right, rgba(147,197,253,0.26), transparent 26%), linear-gradient(135deg, #ffffff, #eff6ff, #dbeafe)',
        };
    }

    protected static function assetUrl(?string $path): ?string
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
