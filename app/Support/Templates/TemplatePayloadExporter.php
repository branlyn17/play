<?php

namespace App\Support\Templates;

use App\Models\Template;
use Illuminate\Support\Facades\Storage;

class TemplatePayloadExporter
{
    public function payload(Template $template): array
    {
        $template->loadMissing('translations');

        $defaultContent = $template->default_content ?? [];
        $shared = $defaultContent['shared'] ?? [];
        $locales = $defaultContent['locales'] ?? [];

        return [
            'version' => 2,
            'style' => array_merge($this->defaultStyle(), $shared['style'] ?? []),
            'visibility' => array_merge(TemplateFieldCatalog::defaultVisibility(), $shared['visibility'] ?? []),
            'locales' => collect(config('locales.supported', []))
                ->mapWithKeys(function ($meta, string $locale) use ($template, $locales) {
                    $translation = $template->translations->firstWhere('locale', $locale);
                    $localePayload = $locales[$locale] ?? [];
                    $dictionary = $localePayload['dictionary']['labels']
                        ?? $localePayload['dictionary']
                        ?? [];

                    return [
                        $locale => [
                            'catalog' => [
                                'name' => $translation?->name ?? '',
                                'slug' => $translation?->slug ?? '',
                                'teaser' => $translation?->teaser ?? '',
                                'description' => $translation?->description ?? '',
                                'seo_title' => $translation?->seo_title ?? '',
                                'seo_description' => $translation?->seo_description ?? '',
                            ],
                            'content' => array_merge(
                                collect(TemplateFieldCatalog::contentFields())
                                    ->pluck('key')
                                    ->mapWithKeys(fn (string $key) => [$key => ''])
                                    ->all(),
                                $localePayload['content'] ?? [],
                            ),
                            'dictionary' => array_merge($this->defaultDictionary(), $dictionary),
                            'media' => array_replace_recursive(
                                TemplateFieldCatalog::defaultMedia(),
                                $localePayload['media'] ?? [],
                            ),
                        ],
                    ];
                })
                ->all(),
        ];
    }

    public function sourceHtml(Template $template): string
    {
        if ($template->source_html_path && Storage::exists($template->source_html_path)) {
            return Storage::get($template->source_html_path);
        }

        return $this->fallbackSourceHtml();
    }

    private function defaultStyle(): array
    {
        return [
            'accentColor' => '#4f7cff',
            'backgroundColor' => '#eef6ff',
            'surfaceColor' => '#ffffff',
            'textColor' => '#0f172a',
            'fontFamily' => 'Sora',
        ];
    }

    private function defaultDictionary(): array
    {
        return [
            'hosts' => 'Hosts',
            'date' => 'Date',
            'time' => 'Time',
            'venue' => 'Venue',
        ];
    }

    private function fallbackSourceHtml(): string
    {
        return <<<'HTML'
<!doctype html>
<html lang="{{locale}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{headline}}</title>
    <style>
        :root {
            --accent: {{accentColor}};
            --background: {{backgroundColor}};
            --surface: {{surfaceColor}};
            --text: {{textColor}};
            --font: "{{fontFamily}}", sans-serif;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background: var(--background);
            color: var(--text);
            font-family: var(--font);
        }
        .invitation {
            width: min(760px, 100%);
            overflow: hidden;
            border-radius: 32px;
            background: var(--surface);
            box-shadow: 0 32px 80px rgba(15, 23, 42, 0.18);
        }
        .hero {
            padding: 34px;
            background: linear-gradient(135deg, var(--accent), var(--background));
        }
        .pill {
            display: inline-flex;
            border-radius: 999px;
            padding: 9px 14px;
            background: rgba(255, 255, 255, 0.82);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }
        h1 {
            margin: 22px 0 12px;
            font-size: clamp(38px, 7vw, 70px);
            line-height: 0.95;
        }
        .content {
            display: grid;
            gap: 16px;
            padding: 28px;
        }
        .card {
            border-radius: 22px;
            padding: 18px;
            background: rgba(248, 250, 252, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }
        .label {
            margin: 0 0 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(71, 85, 105, 0.72);
        }
        .value,
        .message {
            margin: 0;
            font-size: 17px;
            line-height: 1.7;
        }
        .grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        .hero-photo {
            width: min(460px, 100%);
            margin-top: 24px;
            border-radius: 24px;
        }
        .gallery {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        }
        .cta {
            display: inline-flex;
            margin-top: 12px;
            border-radius: 16px;
            padding: 13px 16px;
            background: var(--accent);
            color: white;
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main class="invitation">
        <section class="hero">
            <span class="pill">{{eventLabel}}</span>
            <h1>{{headline}}</h1>
            <p class="message">{{subheadline}}</p>
            <div data-invita-section="hero-image">
                <img class="hero-photo" src="{{heroImageUrl}}" alt="{{heroImageAlt}}">
            </div>
        </section>
        <section class="content">
            <div class="card" data-invita-section="guest">
                <p class="label">{{guestName}}</p>
            </div>
            <div class="card">
                <p class="label">{{label_hosts}}</p>
                <p class="value">{{hosts}}</p>
            </div>
            <div class="grid" data-invita-section="event-details">
                <div class="card"><p class="label">{{label_date}}</p><p class="value">{{dateLabel}}</p></div>
                <div class="card"><p class="label">{{label_time}}</p><p class="value">{{timeLabel}}</p></div>
                <div class="card"><p class="label">{{eventType}}</p><p class="value">{{eventName}}</p></div>
                <div class="card"><p class="label">{{dressCode}}</p><p class="value">{{rsvpDeadline}}</p></div>
                <div class="card"><p class="label">{{timezoneLabel}}</p></div>
            </div>
            <div class="card" data-invita-section="location">
                <p class="label">{{label_venue}}</p>
                <p class="value">{{venueLabel}}</p>
                <p class="value">{{venueName}}</p>
                <p class="value">{{venueAddress}}</p>
                <a class="cta" href="{{googleMapsUrl}}">{{mapButtonLabel}}</a>
                <a class="cta" href="{{appleMapsUrl}}">Apple Maps</a>
            </div>
            <div class="card">
                <p class="message">{{message}}</p>
            </div>
            <div class="gallery" data-invita-section="gallery">
                {{galleryImagesHtml}}
            </div>
            <div class="card">
                <p class="message">{{closing}}</p>
                <a class="cta" href="#">{{buttonLabel}}</a>
            </div>
        </section>
    </main>
</body>
</html>
HTML;
    }
}
