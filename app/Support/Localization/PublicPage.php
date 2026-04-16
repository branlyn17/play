<?php

namespace App\Support\Localization;

class PublicPage
{
    public static function defaultLocale(): string
    {
        return config('locales.default', 'es');
    }

    public static function supportedLocales(): array
    {
        return array_keys(config('locales.supported', []));
    }

    public static function localePattern(): string
    {
        return implode('|', self::supportedLocales());
    }

    public static function slug(string $page, string $locale): string
    {
        return config("public_pages.slugs.{$page}.{$locale}", '');
    }

    public static function routeName(string $page, string $locale): string
    {
        return "public.{$page}.{$locale}";
    }

    public static function localeOptions(string $page): array
    {
        $options = [];

        foreach (config('locales.supported', []) as $code => $meta) {
            $options[] = [
                'code' => $code,
                'label' => $meta['label'],
                'name' => $meta['name'],
                'flag' => $meta['flag'],
                'href' => route(self::routeName($page, $code)),
            ];
        }

        return $options;
    }

    public static function navigation(string $locale, string $currentPage): array
    {
        return collect(config("public_pages.navigation.{$locale}", []))
            ->reject(function (array $item) {
                return $item['key'] === 'login' && auth()->check();
            })
            ->map(function (array $item) use ($locale, $currentPage) {
                $href = match ($item['key']) {
                    'login' => route('login', ['redirect' => request()->getRequestUri()]),
                    default => $item['href'] ?? route(self::routeName($item['key'], $locale)),
                };

                return [
                    'key' => $item['key'],
                    'label' => trans("public.shared.navigation.{$item['key']}"),
                    'href' => $href,
                    'active' => $item['key'] === $currentPage,
                ];
            })
            ->all();
    }
}
