<?php

namespace App\Support\Templates;

class TemplateFieldCatalog
{
    public static function editorSchema(): array
    {
        return [
            'version' => 1,
            'sections' => ['hero', 'event', 'countdown', 'gallery', 'rsvp'],
            'supports' => ['colors', 'fonts', 'spacing', 'backgrounds'],
            'fields' => array_merge(self::contentFields(), self::styleFields()),
        ];
    }

    public static function contentFields(): array
    {
        return [
            ['key' => 'eventLabel', 'group' => 'content', 'type' => 'text', 'translatable' => true, 'label_key' => 'event_label'],
            ['key' => 'headline', 'group' => 'content', 'type' => 'text', 'translatable' => true, 'label_key' => 'headline'],
            ['key' => 'subheadline', 'group' => 'content', 'type' => 'textarea', 'multiline' => true, 'translatable' => true, 'label_key' => 'subheadline'],
            ['key' => 'hosts', 'group' => 'content', 'type' => 'text', 'translatable' => true, 'label_key' => 'hosts'],
            ['key' => 'dateLabel', 'group' => 'content', 'type' => 'text', 'translatable' => true, 'label_key' => 'date_label'],
            ['key' => 'timeLabel', 'group' => 'content', 'type' => 'text', 'translatable' => true, 'label_key' => 'time_label'],
            ['key' => 'venueLabel', 'group' => 'content', 'type' => 'text', 'translatable' => true, 'label_key' => 'venue_label'],
            ['key' => 'message', 'group' => 'content', 'type' => 'textarea', 'multiline' => true, 'translatable' => true, 'label_key' => 'message'],
            ['key' => 'closing', 'group' => 'content', 'type' => 'textarea', 'multiline' => true, 'translatable' => true, 'label_key' => 'closing'],
            ['key' => 'buttonLabel', 'group' => 'content', 'type' => 'text', 'translatable' => true, 'label_key' => 'button_label'],
        ];
    }

    public static function styleFields(): array
    {
        return [
            ['key' => 'accentColor', 'group' => 'style', 'type' => 'color', 'translatable' => false, 'label_key' => 'accent_color'],
            ['key' => 'backgroundColor', 'group' => 'style', 'type' => 'color', 'translatable' => false, 'label_key' => 'background_color'],
            ['key' => 'surfaceColor', 'group' => 'style', 'type' => 'color', 'translatable' => false, 'label_key' => 'surface_color'],
            ['key' => 'textColor', 'group' => 'style', 'type' => 'color', 'translatable' => false, 'label_key' => 'text_color'],
            ['key' => 'fontFamily', 'group' => 'style', 'type' => 'select', 'translatable' => false, 'label_key' => 'font_family'],
        ];
    }

    public static function dictionaryFields(): array
    {
        return [
            ['key' => 'hosts', 'placeholder' => 'label_hosts', 'label_key' => 'hosts'],
            ['key' => 'date', 'placeholder' => 'label_date', 'label_key' => 'date'],
            ['key' => 'time', 'placeholder' => 'label_time', 'label_key' => 'time'],
            ['key' => 'venue', 'placeholder' => 'label_venue', 'label_key' => 'venue'],
        ];
    }

    public static function availableFonts(): array
    {
        return ['Sora', 'Manrope', 'Cormorant Garamond'];
    }

    public static function availableColorTokens(): array
    {
        return ['sky', 'indigo', 'rose', 'emerald', 'slate'];
    }

    public static function requiredPlaceholders(): array
    {
        $fieldPlaceholders = collect(array_merge(self::contentFields(), self::styleFields()))
            ->pluck('key')
            ->map(fn (string $key) => '{{'.$key.'}}');

        $dictionaryPlaceholders = collect(self::dictionaryFields())
            ->pluck('placeholder')
            ->map(fn (string $key) => '{{'.$key.'}}');

        return $fieldPlaceholders
            ->merge($dictionaryPlaceholders)
            ->values()
            ->all();
    }
}
