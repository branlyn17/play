<?php

namespace App\Support\Localization;

class LocaleConfig
{
    public static function default(): string
    {
        return (string) config('locales.default', config('app.locale', 'en'));
    }

    public static function fallback(): string
    {
        return (string) config('locales.fallback', config('app.fallback_locale', 'en'));
    }

    public static function all(): array
    {
        return collect(config('locales.supported', []))
            ->mapWithKeys(function (array $meta, string $code) {
                return [
                    $code => array_merge([
                        'code' => $code,
                        'label' => strtoupper($code),
                        'name' => strtoupper($code),
                        'native_name' => strtoupper($code),
                        'flag' => null,
                        'direction' => 'ltr',
                        'enabled' => true,
                        'fallback' => self::fallback(),
                        'hreflang' => $code,
                    ], $meta),
                ];
            })
            ->all();
    }

    public static function supported(): array
    {
        return collect(self::all())
            ->filter(fn (array $meta) => (bool) ($meta['enabled'] ?? true))
            ->all();
    }

    public static function codes(bool $enabledOnly = true): array
    {
        return array_keys($enabledOnly ? self::supported() : self::all());
    }

    public static function has(string $locale, bool $enabledOnly = true): bool
    {
        return in_array($locale, self::codes($enabledOnly), true);
    }

    public static function metadata(?string $locale = null): array
    {
        $resolvedLocale = $locale ?: app()->getLocale();
        $all = self::all();

        if (isset($all[$resolvedLocale])) {
            return $all[$resolvedLocale];
        }

        return $all[self::default()] ?? [
            'code' => self::default(),
            'label' => strtoupper(self::default()),
            'name' => strtoupper(self::default()),
            'native_name' => strtoupper(self::default()),
            'flag' => null,
            'direction' => 'ltr',
            'enabled' => true,
            'fallback' => self::fallback(),
            'hreflang' => self::default(),
        ];
    }

    public static function fallbackFor(?string $locale = null): string
    {
        return (string) (self::metadata($locale)['fallback'] ?? self::fallback());
    }

    public static function direction(?string $locale = null): string
    {
        return (string) (self::metadata($locale)['direction'] ?? 'ltr');
    }

    public static function isRtl(?string $locale = null): bool
    {
        return self::direction($locale) === 'rtl';
    }

    public static function current(): string
    {
        $locale = app()->getLocale();

        return self::has($locale, enabledOnly: false) ? $locale : self::default();
    }

    public static function htmlLang(?string $locale = null): string
    {
        return str_replace('_', '-', $locale ?: self::current());
    }
}
