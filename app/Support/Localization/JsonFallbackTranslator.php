<?php

namespace App\Support\Localization;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\Translator;

class JsonFallbackTranslator extends Translator
{
    public function __construct(Loader $loader, string $locale)
    {
        parent::__construct($loader, $locale);
    }

    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $resolvedLocale = $locale ?: $this->getLocale();
        $line = TranslationCatalog::get((string) $key, $resolvedLocale);

        if ($line !== null) {
            return $this->makeReplacements($line, $replace);
        }

        return parent::get($key, $replace, $locale, $fallback);
    }
}
