<?php

namespace App\Support\Localization;

use Illuminate\Support\Facades\File;

class TranslationCatalog
{
    /**
     * @var array<string, array<string, string>>
     */
    protected static array $cache = [];

    public static function messages(?string $locale = null): array
    {
        $resolvedLocale = $locale ?: LocaleConfig::current();
        $fallbackLocale = LocaleConfig::fallbackFor($resolvedLocale);

        return array_replace(
            self::loadLocale($fallbackLocale),
            self::loadLocale($resolvedLocale),
        );
    }

    public static function subset(array $prefixes, ?string $locale = null): array
    {
        $messages = self::messages($locale);
        $resolvedPrefixes = collect($prefixes)
            ->filter(fn ($prefix) => is_string($prefix) && $prefix !== '')
            ->values()
            ->all();

        if ($resolvedPrefixes === []) {
            return $messages;
        }

        return collect($messages)
            ->filter(function ($value, string $key) use ($resolvedPrefixes) {
                foreach ($resolvedPrefixes as $prefix) {
                    if ($key === $prefix || str_starts_with($key, $prefix)) {
                        return true;
                    }
                }

                return false;
            })
            ->all();
    }

    public static function has(string $key, ?string $locale = null): bool
    {
        return array_key_exists($key, self::messages($locale));
    }

    public static function get(string $key, ?string $locale = null): ?string
    {
        $value = self::messages($locale)[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    protected static function loadLocale(string $locale): array
    {
        if (isset(self::$cache[$locale])) {
            return self::$cache[$locale];
        }

        $path = lang_path("{$locale}.json");

        if (! File::exists($path)) {
            return self::$cache[$locale] = [];
        }

        $decoded = json_decode(File::get($path), true);

        if (! is_array($decoded)) {
            return self::$cache[$locale] = [];
        }

        return self::$cache[$locale] = collect($decoded)
            ->filter(fn ($value, $key) => is_string($key) && is_string($value))
            ->all();
    }
}
