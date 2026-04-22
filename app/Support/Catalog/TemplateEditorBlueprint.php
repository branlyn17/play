<?php

namespace App\Support\Catalog;

use App\Models\Template;
use App\Support\Templates\TemplateFieldCatalog;
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
            'visibilityDefaults' => array_merge(
                TemplateFieldCatalog::defaultVisibility(),
                $shared['visibility'] ?? [],
                $localeContent['visibility'] ?? [],
            ),
            'mediaDefaults' => self::normalizeMedia($localeContent['media'] ?? $shared['media'] ?? []),
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
            [
                '_visibility' => $resolved['visibilityDefaults'],
                '_media' => $resolved['mediaDefaults'],
            ],
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
            'content' => Arr::except($editorState, array_merge($styleKeys, ['_visibility', '_media', '_meta'])),
            'style' => Arr::only($editorState, $styleKeys),
            'visibility' => $editorState['_visibility'] ?? [],
            'media' => self::normalizeMedia($editorState['_media'] ?? []),
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
            $payload['visibility'] = array_merge($payload['visibility'] ?? [], $candidatePayload['visibility'] ?? []);
            $payload['media'] = self::mergeMedia($payload['media'] ?? [], $candidatePayload['media'] ?? []);
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
                    'required' => (bool) ($field['required'] ?? false),
                    'section' => $field['section'] ?? null,
                    'label_key' => $field['label_key'] ?? null,
                ];
            })
            ->filter(fn (array $field) => filled($field['key']))
            ->values()
            ->all();
    }

    protected static function normalizeMedia(array $media): array
    {
        $defaults = TemplateFieldCatalog::defaultMedia();
        $gallery = collect($media['gallery'] ?? [])
            ->filter(fn ($item) => is_array($item))
            ->map(fn (array $item) => [
                'url' => (string) ($item['url'] ?? ''),
                'alt' => (string) ($item['alt'] ?? ''),
                'caption' => (string) ($item['caption'] ?? ''),
            ])
            ->values()
            ->all();

        return [
            'hero' => [
                'url' => (string) ($media['hero']['url'] ?? $defaults['hero']['url']),
                'alt' => (string) ($media['hero']['alt'] ?? $defaults['hero']['alt']),
            ],
            'background' => [
                'url' => (string) ($media['background']['url'] ?? $defaults['background']['url']),
                'alt' => (string) ($media['background']['alt'] ?? $defaults['background']['alt']),
            ],
            'gallery' => $gallery,
        ];
    }

    protected static function mergeMedia(array $base, array $override): array
    {
        $base = self::normalizeMedia($base);
        $override = self::normalizeMedia($override);

        return [
            'hero' => array_merge($base['hero'], array_filter($override['hero'])),
            'background' => array_merge($base['background'], array_filter($override['background'])),
            'gallery' => $override['gallery'] !== [] ? $override['gallery'] : $base['gallery'],
        ];
    }
}
