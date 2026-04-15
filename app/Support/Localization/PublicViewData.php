<?php

namespace App\Support\Localization;

class PublicViewData
{
    public static function make(string $page): array
    {
        $locale = app()->getLocale();
        $content = trans("public.{$page}");

        return [
            'title' => trans("public.{$page}.title"),
            'metaDescription' => trans("public.{$page}.meta_description"),
            'page' => self::pageComponent($page),
            'props' => [
                'appName' => config('app.name'),
                'locale' => $locale,
                'locales' => PublicPage::localeOptions($page),
                'navigation' => PublicPage::navigation($locale, $page),
                'shared' => trans('public.shared'),
                'content' => is_array($content) ? $content : [],
            ],
        ];
    }

    protected static function pageComponent(string $page): string
    {
        return match ($page) {
            'home' => 'public-home',
            'catalog' => 'public-catalog',
            default => "public-{$page}",
        };
    }
}
