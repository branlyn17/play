<?php

namespace App\Support\Localization;

class PublicViewData
{
    public static function make(string $page, array $extraProps = []): array
    {
        $locale = app()->getLocale();
        $content = trans("public.{$page}");
        $baseProps = [
            'appName' => config('app.name'),
            'locale' => $locale,
            'shared' => trans('public.shared'),
            'content' => is_array($content) ? $content : [],
        ];

        if (! array_key_exists('locales', $extraProps)) {
            $baseProps['locales'] = PublicPage::localeOptions($page);
        }

        if (! array_key_exists('navigation', $extraProps)) {
            $baseProps['navigation'] = PublicPage::navigation($locale, $page);
        }

        return [
            'title' => trans("public.{$page}.title"),
            'metaDescription' => trans("public.{$page}.meta_description"),
            'page' => self::pageComponent($page),
            'props' => array_merge($baseProps, $extraProps),
        ];
    }

    protected static function pageComponent(string $page): string
    {
        return match ($page) {
            'home' => 'public-home',
            'catalog' => 'public-catalog',
            'template_editor' => 'public-template_editor',
            default => "public-{$page}",
        };
    }
}
