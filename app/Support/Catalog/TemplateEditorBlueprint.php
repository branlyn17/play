<?php

namespace App\Support\Catalog;

use App\Models\Template;
use Illuminate\Support\Arr;

class TemplateEditorBlueprint
{
    public static function resolve(Template $template, string $locale): array
    {
        $schema = $template->editor_schema ?? [];
        $defaultContent = $template->default_content ?? [];
        $fallbackLocale = config('locales.fallback', 'en');
        $templateLocale = $template->default_locale ?: $fallbackLocale;

        $shared = $defaultContent['shared'] ?? [];
        $localeContent = self::resolveLocaleContent($defaultContent, $locale, $templateLocale, $fallbackLocale);

        return [
            'schema' => $schema,
            'fields' => self::normalizeFields($schema),
            'contentDefaults' => $localeContent['content'] ?? [],
            'styleDefaults' => $shared['style'] ?? [],
            'dictionary' => $localeContent['dictionary'] ?? [],
            'resolvedLocale' => $localeContent['resolved_locale'] ?? $locale,
        ];
    }

    public static function defaultEditorState(Template $template, string $locale): array
    {
        $resolved = self::resolve($template, $locale);

        return array_merge(
            $resolved['contentDefaults'],
            $resolved['styleDefaults'],
        );
    }

    public static function splitEditorState(Template $template, array $editorState): array
    {
        $fields = collect(self::normalizeFields($template->editor_schema ?? []));
        $styleKeys = $fields
            ->where('group', 'style')
            ->pluck('key')
            ->filter()
            ->values()
            ->all();

        return [
            'content' => Arr::except($editorState, $styleKeys),
            'style' => Arr::only($editorState, $styleKeys),
        ];
    }

    protected static function resolveLocaleContent(array $defaultContent, string $locale, string $templateLocale, string $fallbackLocale): array
    {
        $locales = $defaultContent['locales'] ?? [];
        $candidates = array_values(array_unique([$locale, $templateLocale, $fallbackLocale]));
        $payload = ['content' => [], 'dictionary' => []];
        $resolvedLocale = $locale;

        foreach (array_reverse($candidates) as $candidate) {
            $candidatePayload = $locales[$candidate] ?? null;

            if (! is_array($candidatePayload)) {
                continue;
            }

            $payload['content'] = array_merge($payload['content'], $candidatePayload['content'] ?? []);
            $payload['dictionary'] = array_replace_recursive($payload['dictionary'], $candidatePayload['dictionary'] ?? []);
            $resolvedLocale = $candidate;
        }

        $payload['resolved_locale'] = $resolvedLocale;

        return $payload;
    }

    protected static function normalizeFields(array $schema): array
    {
        return collect($schema['fields'] ?? [])
            ->map(function (array $field) {
                return [
                    'key' => $field['key'] ?? null,
                    'group' => $field['group'] ?? 'content',
                    'type' => $field['type'] ?? 'text',
                    'translatable' => (bool) ($field['translatable'] ?? false),
                    'multiline' => (bool) ($field['multiline'] ?? false),
                    'label_key' => $field['label_key'] ?? null,
                ];
            })
            ->filter(fn (array $field) => filled($field['key']))
            ->values()
            ->all();
    }
}
