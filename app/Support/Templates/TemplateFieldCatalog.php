<?php

namespace App\Support\Templates;

class TemplateFieldCatalog
{
    public static function editorSchema(): array
    {
        return [
            'version' => 2,
            'sections' => ['hero', 'guest', 'event_details', 'location', 'media', 'gallery', 'rsvp'],
            'supports' => ['colors', 'fonts', 'spacing', 'backgrounds', 'visibility', 'media', 'maps'],
            'fields' => array_merge(self::contentFields(), self::styleFields(), self::visibilityFields()),
        ];
    }

    public static function contentFields(): array
    {
        return [
            ['key' => 'eventLabel', 'group' => 'content', 'section' => 'hero', 'type' => 'text', 'translatable' => true, 'required' => true, 'label_key' => 'event_label'],
            ['key' => 'headline', 'group' => 'content', 'section' => 'hero', 'type' => 'text', 'translatable' => true, 'required' => true, 'label_key' => 'headline'],
            ['key' => 'subheadline', 'group' => 'content', 'section' => 'hero', 'type' => 'textarea', 'multiline' => true, 'translatable' => true, 'required' => true, 'label_key' => 'subheadline'],
            ['key' => 'hosts', 'group' => 'content', 'section' => 'event_details', 'type' => 'text', 'translatable' => true, 'required' => true, 'label_key' => 'hosts'],
            ['key' => 'dateLabel', 'group' => 'content', 'section' => 'event_details', 'type' => 'date', 'translatable' => false, 'required' => true, 'label_key' => 'date_label'],
            ['key' => 'timeLabel', 'group' => 'content', 'section' => 'event_details', 'type' => 'time', 'translatable' => false, 'required' => true, 'label_key' => 'time_label'],
            ['key' => 'venueLabel', 'group' => 'content', 'section' => 'location', 'type' => 'text', 'translatable' => true, 'required' => true, 'label_key' => 'venue_label'],
            ['key' => 'message', 'group' => 'content', 'section' => 'message', 'type' => 'textarea', 'multiline' => true, 'translatable' => true, 'required' => true, 'label_key' => 'message'],
            ['key' => 'closing', 'group' => 'content', 'section' => 'message', 'type' => 'textarea', 'multiline' => true, 'translatable' => true, 'required' => true, 'label_key' => 'closing'],
            ['key' => 'buttonLabel', 'group' => 'content', 'section' => 'rsvp', 'type' => 'text', 'translatable' => true, 'required' => true, 'label_key' => 'button_label'],
            ['key' => 'guestName', 'group' => 'content', 'section' => 'guest', 'type' => 'text', 'translatable' => true, 'required' => false, 'label_key' => 'guest_name'],
            ['key' => 'eventType', 'group' => 'content', 'section' => 'event_details', 'type' => 'text', 'translatable' => true, 'required' => false, 'label_key' => 'event_type'],
            ['key' => 'eventName', 'group' => 'content', 'section' => 'event_details', 'type' => 'text', 'translatable' => true, 'required' => false, 'label_key' => 'event_name'],
            ['key' => 'dressCode', 'group' => 'content', 'section' => 'event_details', 'type' => 'text', 'translatable' => true, 'required' => false, 'label_key' => 'dress_code'],
            ['key' => 'rsvpDeadline', 'group' => 'content', 'section' => 'rsvp', 'type' => 'text', 'translatable' => true, 'required' => false, 'label_key' => 'rsvp_deadline'],
            ['key' => 'timezoneLabel', 'group' => 'content', 'section' => 'event_details', 'type' => 'timezone', 'translatable' => false, 'required' => false, 'label_key' => 'timezone_label'],
            ['key' => 'venueName', 'group' => 'content', 'section' => 'location', 'type' => 'text', 'translatable' => true, 'required' => false, 'label_key' => 'venue_name'],
            ['key' => 'venueAddress', 'group' => 'content', 'section' => 'location', 'type' => 'textarea', 'multiline' => true, 'translatable' => true, 'required' => false, 'label_key' => 'venue_address'],
            ['key' => 'googleMapsUrl', 'group' => 'content', 'section' => 'location', 'type' => 'url', 'translatable' => false, 'required' => false, 'label_key' => 'google_maps_url'],
            ['key' => 'appleMapsUrl', 'group' => 'content', 'section' => 'location', 'type' => 'url', 'translatable' => false, 'required' => false, 'label_key' => 'apple_maps_url'],
            ['key' => 'mapButtonLabel', 'group' => 'content', 'section' => 'location', 'type' => 'text', 'translatable' => true, 'required' => false, 'label_key' => 'map_button_label'],
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

    public static function visibilityFields(): array
    {
        return [
            ['key' => 'showGuestName', 'group' => 'visibility', 'section' => 'guest', 'type' => 'boolean', 'label_key' => 'show_guest_name'],
            ['key' => 'showEventDetails', 'group' => 'visibility', 'section' => 'event_details', 'type' => 'boolean', 'label_key' => 'show_event_details'],
            ['key' => 'showLocation', 'group' => 'visibility', 'section' => 'location', 'type' => 'boolean', 'label_key' => 'show_location'],
            ['key' => 'showHeroImage', 'group' => 'visibility', 'section' => 'hero_image', 'type' => 'boolean', 'label_key' => 'show_hero_image'],
            ['key' => 'showGallery', 'group' => 'visibility', 'section' => 'gallery', 'type' => 'boolean', 'label_key' => 'show_gallery'],
        ];
    }

    public static function defaultVisibility(): array
    {
        return collect(self::visibilityFields())
            ->pluck('key')
            ->mapWithKeys(fn (string $key) => [$key => true])
            ->all();
    }

    public static function defaultMedia(): array
    {
        return [
            'hero' => ['url' => '', 'alt' => ''],
            'background' => ['url' => '', 'alt' => ''],
            'gallery' => [],
        ];
    }

    public static function mediaPlaceholders(): array
    {
        return [
            '{{heroImageUrl}}',
            '{{heroImageAlt}}',
            '{{backgroundImageUrl}}',
            '{{backgroundImageAlt}}',
            '{{galleryImagesHtml}}',
        ];
    }

    public static function optionalContentFields(): array
    {
        return collect(self::contentFields())
            ->where('required', false)
            ->values()
            ->all();
    }

    public static function requiredContentFields(): array
    {
        return collect(self::contentFields())
            ->filter(fn (array $field) => (bool) ($field['required'] ?? false))
            ->values()
            ->all();
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
        $fieldPlaceholders = collect(array_merge(self::requiredContentFields(), self::styleFields()))
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

    public static function optionalPlaceholders(): array
    {
        $contentPlaceholders = collect(self::optionalContentFields())
            ->pluck('key')
            ->map(fn (string $key) => '{{'.$key.'}}');

        return $contentPlaceholders
            ->merge(self::mediaPlaceholders())
            ->values()
            ->all();
    }

    public static function supportedPlaceholders(): array
    {
        return collect(self::requiredPlaceholders())
            ->merge(self::optionalPlaceholders())
            ->unique()
            ->values()
            ->all();
    }
}
