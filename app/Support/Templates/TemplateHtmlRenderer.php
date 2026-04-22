<?php

namespace App\Support\Templates;

use App\Models\Template;
use App\Support\Catalog\TemplateEditorBlueprint;
use Carbon\CarbonImmutable;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateHtmlRenderer
{
    private const FONT_LINKS = [
        'Sora' => 'https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap',
        'Manrope' => 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap',
        'Cormorant Garamond' => 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&display=swap',
    ];

    public function render(Template $template, ?string $locale = null): string
    {
        $locale ??= $template->default_locale ?: config('locales.default', 'es');

        $blueprint = TemplateEditorBlueprint::resolve($template, $locale);
        $state = TemplateEditorBlueprint::defaultEditorState($template, $locale);
        $sourceHtml = $this->sourceHtml($template);

        if ($sourceHtml) {
            return $this->renderUploadedHtml($sourceHtml, $state, $locale, $blueprint['dictionary'] ?? []);
        }

        return $this->renderFallbackHtml($template, $state, $locale, $blueprint['dictionary'] ?? []);
    }

    private function sourceHtml(Template $template): ?string
    {
        if (! $template->source_html_path || ! Storage::exists($template->source_html_path)) {
            return null;
        }

        return Storage::get($template->source_html_path);
    }

    private function renderUploadedHtml(string $sourceHtml, array $state, string $locale, array $dictionary): string
    {
        $documentHtml = $sourceHtml;

        foreach ($this->replacements($state, $locale, $dictionary) as $key => $value) {
            $documentHtml = str_replace('{{'.$key.'}}', $value, $documentHtml);
        }

        $documentHtml = $this->injectFontLink($documentHtml, (string) ($state['fontFamily'] ?? ''));
        $documentHtml = $this->applyOptionalSectionVisibility($documentHtml, $state['_visibility'] ?? []);

        return $this->setHtmlLanguage($documentHtml, $locale);
    }

    private function renderFallbackHtml(Template $template, array $state, string $locale, array $dictionary): string
    {
        $labels = $this->labels($dictionary);
        $media = $this->media($state['_media'] ?? []);
        $visibility = array_merge(TemplateFieldCatalog::defaultVisibility(), $state['_visibility'] ?? []);
        $background = $template->design_tokens['catalog_background']
            ?? 'linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)';
        $fontFamily = $this->plain($state['fontFamily'] ?? 'Sora');
        $fontLink = self::FONT_LINKS[$fontFamily] ?? self::FONT_LINKS['Sora'];
        $gallery = $visibility['showGallery'] ? $this->galleryHtml($media) : '';
        $heroImage = $visibility['showHeroImage'] && filled($media['hero']['url'])
            ? '<img class="hero-photo" src="'.$this->attr($media['hero']['url']).'" alt="'.$this->attr($media['hero']['alt']).'">'
            : '';
        $locationButton = filled($state['googleMapsUrl'] ?? '')
            ? '<a class="cta" href="'.$this->attr($state['googleMapsUrl']).'">'.$this->html($state['mapButtonLabel'] ?: ($locale === 'en' ? 'View location' : 'Ver ubicacion')).'</a>'
            : '';
        $appleMapsButton = filled($state['appleMapsUrl'] ?? '')
            ? '<a class="cta cta-soft" href="'.$this->attr($state['appleMapsUrl']).'">Apple Maps</a>'
            : '';

        return '<!doctype html>
<html lang="'.$this->attr($locale).'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>'.$this->html($state['headline'] ?? $template->code).'</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="'.$this->attr($fontLink).'" rel="stylesheet">
    <style>
        :root {
            --accent: '.$this->plain($state['accentColor'] ?? '#38bdf8').';
            --background: '.$this->plain($state['backgroundColor'] ?? '#eef6ff').';
            --surface: '.$this->plain($state['surfaceColor'] ?? '#ffffff').';
            --text: '.$this->plain($state['textColor'] ?? '#0f172a').';
            --font: "'.$this->plain($fontFamily).'", sans-serif;
            --catalog-background: '.$background.';
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            color: var(--text);
            font-family: var(--font);
            background: var(--background);
        }
        .frame {
            width: min(760px, 100%);
            overflow: hidden;
            border-radius: 34px;
            background: var(--surface);
            border: 1px solid rgba(148, 163, 184, 0.22);
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.18);
        }
        .hero {
            padding: 34px;
            background: var(--catalog-background);
        }
        .pill {
            display: inline-flex;
            border-radius: 999px;
            padding: 9px 14px;
            background: rgba(255,255,255,0.78);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }
        h1 {
            margin: 22px 0 12px;
            max-width: 620px;
            font-size: clamp(38px, 7vw, 70px);
            line-height: 0.95;
            letter-spacing: -0.05em;
        }
        .subtitle {
            max-width: 560px;
            margin: 0;
            font-size: 18px;
            line-height: 1.65;
        }
        .hero-photo {
            display: block;
            width: min(440px, 100%);
            margin-top: 24px;
            border-radius: 26px;
            box-shadow: 0 22px 50px rgba(15, 23, 42, 0.2);
        }
        .content {
            display: grid;
            gap: 16px;
            padding: 24px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 14px;
        }
        .card {
            border-radius: 22px;
            padding: 17px;
            background: rgba(248, 250, 252, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }
        .label {
            margin: 0 0 8px;
            color: rgba(71, 85, 105, 0.78);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }
        .value,
        .message {
            margin: 0;
            font-size: 17px;
            line-height: 1.65;
        }
        .cta {
            display: inline-flex;
            margin-top: 14px;
            margin-right: 8px;
            border-radius: 16px;
            padding: 13px 16px;
            color: #fff;
            background: var(--accent);
            font-weight: 800;
            text-decoration: none;
        }
        .cta-soft { background: rgba(15, 23, 42, 0.74); }
        .gallery {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }
        .invita-gallery-item {
            margin: 0;
            overflow: hidden;
            border-radius: 18px;
            background: rgba(248,250,252,0.95);
        }
        .invita-gallery-item img {
            display: block;
            width: 100%;
            height: 110px;
            object-fit: cover;
        }
        .invita-gallery-item figcaption {
            padding: 9px 10px;
            font-size: 12px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <main class="frame">
        <section class="hero">
            <span class="pill">'.$this->html($state['eventLabel'] ?? '').'</span>
            <h1>'.$this->html($state['headline'] ?? '').'</h1>
            <p class="subtitle">'.$this->text($state['subheadline'] ?? '').'</p>
            '.$heroImage.'
        </section>
        <section class="content">
            '.(($visibility['showGuestName'] ?? true) && filled($state['guestName'] ?? '') ? '<div class="card"><p class="label">'.($locale === 'en' ? 'Guest' : 'Invitado').'</p><p class="value">'.$this->text($state['guestName']).'</p></div>' : '').'
            <div class="card"><p class="label">'.$this->html($labels['hosts']).'</p><p class="value">'.$this->text($state['hosts'] ?? '').'</p></div>
            <div class="grid">
                <div class="card"><p class="label">'.$this->html($labels['date']).'</p><p class="value">'.$this->text($this->dateForDisplay($state['dateLabel'] ?? '', $locale)).'</p></div>
                <div class="card"><p class="label">'.$this->html($labels['time']).'</p><p class="value">'.$this->text($this->timeForDisplay($state['timeLabel'] ?? '', $locale)).'</p></div>
                <div class="card"><p class="label">'.$this->html($labels['venue']).'</p><p class="value">'.$this->text($state['venueLabel'] ?? '').'</p></div>
            </div>
            '.(($visibility['showLocation'] ?? true) ? '<div class="card"><p class="label">'.$this->html($state['venueName'] ?: $labels['venue']).'</p><p class="value">'.$this->text($state['venueAddress'] ?: ($state['venueLabel'] ?? '')).'</p>'.$locationButton.$appleMapsButton.'</div>' : '').'
            <div class="card"><p class="message">'.$this->text($state['message'] ?? '').'</p></div>
            '.$gallery.'
            <div class="card"><p class="message">'.$this->text($state['closing'] ?? '').'</p><a class="cta" href="#">'.$this->html($state['buttonLabel'] ?? '').'</a></div>
        </section>
    </main>
</body>
</html>';
    }

    private function replacements(array $state, string $locale, array $dictionary): array
    {
        $replacements = [];

        foreach ($state as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $displayValue = match ($key) {
                'dateLabel' => $this->dateForDisplay($value, $locale),
                'timeLabel' => $this->timeForDisplay($value, $locale),
                default => $value,
            };

            $replacements[$key] = in_array($key, ['subheadline', 'message', 'closing', 'venueAddress'], true)
                ? $this->text($displayValue)
                : $this->html($displayValue);
        }

        $labels = $this->labels($dictionary);
        $media = $this->media($state['_media'] ?? []);

        $replacements['label_hosts'] = $this->html($labels['hosts']);
        $replacements['label_date'] = $this->html($labels['date']);
        $replacements['label_time'] = $this->html($labels['time']);
        $replacements['label_venue'] = $this->html($labels['venue']);
        $replacements['heroImageUrl'] = $this->attr($media['hero']['url']);
        $replacements['heroImageAlt'] = $this->attr($media['hero']['alt']);
        $replacements['backgroundImageUrl'] = $this->attr($media['background']['url']);
        $replacements['backgroundImageAlt'] = $this->attr($media['background']['alt']);
        $replacements['galleryImagesHtml'] = $this->galleryHtml($media);
        $replacements['locale'] = $this->attr($locale);

        return $replacements;
    }

    private function galleryHtml(array $media): string
    {
        return collect($media['gallery'] ?? [])
            ->filter(fn (array $item) => filled($item['url'] ?? ''))
            ->map(fn (array $item) => '<figure class="invita-gallery-item"><img src="'.$this->attr($item['url']).'" alt="'.$this->attr($item['alt'] ?? '').'" loading="lazy">'.(filled($item['caption'] ?? '') ? '<figcaption>'.$this->html($item['caption']).'</figcaption>' : '').'</figure>')
            ->implode('');
    }

    private function applyOptionalSectionVisibility(string $documentHtml, array $visibility): string
    {
        $sectionMap = [
            'guest' => 'showGuestName',
            'event-details' => 'showEventDetails',
            'location' => 'showLocation',
            'hero-image' => 'showHeroImage',
            'gallery' => 'showGallery',
        ];

        $visibility = array_merge(TemplateFieldCatalog::defaultVisibility(), $visibility);

        if (! class_exists(DOMDocument::class)) {
            return $documentHtml;
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $loaded = $dom->loadHTML('<?xml encoding="UTF-8">'.$documentHtml, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return $documentHtml;
        }

        $xpath = new DOMXPath($dom);

        foreach ($sectionMap as $section => $key) {
            if ($visibility[$key] ?? true) {
                continue;
            }

            foreach ($xpath->query('//*[@data-invita-section="'.$section.'"]') ?: [] as $node) {
                $node->parentNode?->removeChild($node);
            }
        }

        return '<!doctype html>'.PHP_EOL.$dom->saveHTML($dom->documentElement);
    }

    private function injectFontLink(string $documentHtml, string $fontFamily): string
    {
        $fontLink = self::FONT_LINKS[$fontFamily] ?? null;

        if (! $fontLink || Str::contains($documentHtml, $fontLink)) {
            return $documentHtml;
        }

        $injection = '
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="'.$this->attr($fontLink).'" rel="stylesheet">';

        if (Str::contains($documentHtml, '</head>')) {
            return str_replace('</head>', $injection.PHP_EOL.'</head>', $documentHtml);
        }

        return $injection.PHP_EOL.$documentHtml;
    }

    private function setHtmlLanguage(string $documentHtml, string $locale): string
    {
        if (preg_match('/<html\b[^>]*lang=/i', $documentHtml)) {
            return preg_replace('/<html([^>]*)lang=(["\']).*?\2([^>]*)>/i', '<html$1lang="'.$this->attr($locale).'"$3>', $documentHtml) ?? $documentHtml;
        }

        if (preg_match('/<html\b/i', $documentHtml)) {
            return preg_replace('/<html\b([^>]*)>/i', '<html lang="'.$this->attr($locale).'"$1>', $documentHtml) ?? $documentHtml;
        }

        return $documentHtml;
    }

    private function dateForDisplay(mixed $value, string $locale): string
    {
        $value = (string) ($value ?? '');

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        $date = CarbonImmutable::createFromFormat('Y-m-d', $value);

        return $locale === 'en'
            ? $date->isoFormat('dddd, MMMM D, YYYY')
            : $date->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');
    }

    private function timeForDisplay(mixed $value, string $locale): string
    {
        $value = (string) ($value ?? '');

        if (! preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value;
        }

        $time = CarbonImmutable::createFromFormat('H:i', $value);

        return $locale === 'en'
            ? $time->format('g:i A')
            : $time->format('H:i');
    }

    private function labels(array $dictionary): array
    {
        $labels = $dictionary['labels'] ?? $dictionary;

        return [
            'hosts' => (string) ($labels['hosts'] ?? 'Hosts'),
            'date' => (string) ($labels['date'] ?? 'Date'),
            'time' => (string) ($labels['time'] ?? 'Time'),
            'venue' => (string) ($labels['venue'] ?? 'Venue'),
        ];
    }

    private function media(array $media): array
    {
        return array_replace_recursive(TemplateFieldCatalog::defaultMedia(), $media);
    }

    private function html(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }

    private function attr(mixed $value): string
    {
        return $this->html($value);
    }

    private function text(mixed $value): string
    {
        return nl2br($this->html($value), false);
    }

    private function plain(mixed $value): string
    {
        return str_replace(['<', '>', '"', "'"], '', (string) ($value ?? ''));
    }
}
