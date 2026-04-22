import { useEffect, useMemo, useRef, useState } from 'react';

import PublicLayout from '../components/public/PublicLayout';

const fontLinks = {
    Sora: 'https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap',
    Manrope: 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap',
    'Cormorant Garamond': 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&display=swap',
};

const timezoneOptions = [
    'America/La_Paz',
    'America/New_York',
    'America/Mexico_City',
    'America/Bogota',
    'America/Lima',
    'America/Santiago',
    'America/Argentina/Buenos_Aires',
    'America/Santo_Domingo',
    'Europe/Madrid',
    'UTC',
];

const multilineFields = new Set(['subheadline', 'message', 'closing', 'venueAddress']);
const visibilityDefaults = {
    showGuestName: true,
    showEventDetails: true,
    showLocation: true,
    showHeroImage: true,
    showGallery: true,
};

const emptyMedia = {
    hero: { url: '', alt: '' },
    background: { url: '', alt: '' },
    gallery: [],
};

function normalizeContent(content = {}) {
    return {
        header: content.header ?? {},
        hero: content.hero ?? {},
        actions: content.actions ?? {},
        panels: content.panels ?? {},
        fields: content.fields ?? {},
        stats: content.stats ?? {},
        badges: content.badges ?? {},
        status: content.status ?? {},
        preview: content.preview ?? {},
        tips: content.tips ?? [],
    };
}

function normalizeDictionary(dictionary = {}) {
    return {
        labels: {
            hosts: dictionary.labels?.hosts ?? 'Hosts',
            date: dictionary.labels?.date ?? 'Date',
            time: dictionary.labels?.time ?? 'Time',
            venue: dictionary.labels?.venue ?? 'Venue',
        },
    };
}

function normalizeVisibility(visibility = {}) {
    return {
        ...visibilityDefaults,
        ...visibility,
    };
}

function normalizeMedia(media = {}) {
    return {
        hero: {
            url: media.hero?.url ?? '',
            alt: media.hero?.alt ?? '',
        },
        background: {
            url: media.background?.url ?? '',
            alt: media.background?.alt ?? '',
        },
        gallery: Array.isArray(media.gallery)
            ? media.gallery.map((item) => ({
                url: item?.url ?? '',
                alt: item?.alt ?? '',
                caption: item?.caption ?? '',
            }))
            : [],
    };
}

function buildInitialState(template) {
    const defaults = {
        ...(template.defaultContent?.content ?? {}),
        ...(template.defaultContent?.style ?? {}),
        _visibility: normalizeVisibility(template.defaultContent?.visibility),
        _media: normalizeMedia(template.defaultContent?.media ?? emptyMedia),
    };
    const savedState = { ...(template.savedState ?? {}) };
    const savedMedia = normalizeMedia(template.savedMedia ?? savedState._media ?? defaults._media);

    delete savedState._meta;
    delete savedState._media;

    const mergedState = {
        ...defaults,
        ...savedState,
        _visibility: normalizeVisibility(savedState._visibility ?? defaults._visibility),
        _media: savedMedia,
    };

    if (!/^\d{4}-\d{2}-\d{2}$/.test(String(mergedState.dateLabel ?? ''))) {
        mergedState.dateLabel = defaults.dateLabel ?? '';
    }

    if (!/^\d{2}:\d{2}$/.test(String(mergedState.timeLabel ?? ''))) {
        mergedState.timeLabel = defaults.timeLabel ?? '';
    }

    if (!timezoneOptions.includes(mergedState.timezoneLabel)) {
        mergedState.timezoneLabel = defaults.timezoneLabel || 'America/La_Paz';
    }

    return mergedState;
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function formatHtmlText(value) {
    return escapeHtml(value).replace(/\n/g, '<br />');
}

function formatDateForDisplay(value, locale) {
    if (!/^\d{4}-\d{2}-\d{2}$/.test(String(value ?? ''))) {
        return value ?? '';
    }

    const [year, month, day] = value.split('-').map(Number);
    const date = new Date(year, month - 1, day);

    return new Intl.DateTimeFormat(locale, {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(date);
}

function formatTimeForDisplay(value, locale) {
    if (!/^\d{2}:\d{2}$/.test(String(value ?? ''))) {
        return value ?? '';
    }

    const [hour, minute] = value.split(':').map(Number);
    const date = new Date(2000, 0, 1, hour, minute);

    return new Intl.DateTimeFormat(locale, {
        hour: 'numeric',
        minute: '2-digit',
    }).format(date);
}

function displayValueForPlaceholder(key, value, locale) {
    if (key === 'dateLabel') {
        return formatDateForDisplay(value, locale);
    }

    if (key === 'timeLabel') {
        return formatTimeForDisplay(value, locale);
    }

    return value;
}

function renderGalleryImagesHtml(media) {
    return normalizeMedia(media).gallery
        .filter((item) => item.url)
        .map((item) => `
            <figure class="invita-gallery-item">
                <img src="${escapeHtml(item.url)}" alt="${escapeHtml(item.alt)}" loading="lazy">
                ${item.caption ? `<figcaption>${escapeHtml(item.caption)}</figcaption>` : ''}
            </figure>
        `)
        .join('');
}

function applyOptionalSectionVisibility(documentHtml, visibility) {
    const sectionMap = {
        guest: 'showGuestName',
        'event-details': 'showEventDetails',
        location: 'showLocation',
        'hero-image': 'showHeroImage',
        gallery: 'showGallery',
    };

    if (typeof window.DOMParser === 'undefined') {
        return documentHtml;
    }

    const normalizedVisibility = normalizeVisibility(visibility);
    const parser = new window.DOMParser();
    const doc = parser.parseFromString(documentHtml, 'text/html');

    Object.entries(sectionMap).forEach(([section, key]) => {
        if (normalizedVisibility[key]) {
            return;
        }

        doc.querySelectorAll(`[data-invita-section="${section}"]`).forEach((element) => element.remove());
    });

    return `<!doctype html>\n${doc.documentElement.outerHTML}`;
}

function injectFontLink(documentHtml, fontFamily) {
    const fontLink = fontLinks[fontFamily];

    if (!fontLink || documentHtml.includes(fontLink)) {
        return documentHtml;
    }

    const injection = `
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="${fontLink}" rel="stylesheet">`;

    if (documentHtml.includes('</head>')) {
        return documentHtml.replace('</head>', `${injection}\n</head>`);
    }

    return `${injection}\n${documentHtml}`;
}

function setHtmlLanguage(documentHtml, locale) {
    if (/<html\b[^>]*lang=/i.test(documentHtml)) {
        return documentHtml.replace(/<html([^>]*)lang=(['"]).*?\2([^>]*)>/i, `<html$1lang="${escapeHtml(locale)}"$3>`);
    }

    if (/<html\b/i.test(documentHtml)) {
        return documentHtml.replace(/<html\b([^>]*)>/i, `<html lang="${escapeHtml(locale)}"$1>`);
    }

    return documentHtml;
}

function renderUploadedHtmlTemplate(sourceHtml, state, locale, dictionary) {
    const labels = normalizeDictionary(dictionary).labels;
    const media = normalizeMedia(state._media);
    const replacements = {
        ...Object.fromEntries(
            Object.entries(state)
                .filter(([, value]) => typeof value !== 'object')
                .map(([key, value]) => [
                    key,
                    multilineFields.has(key)
                        ? formatHtmlText(displayValueForPlaceholder(key, value, locale))
                        : escapeHtml(displayValueForPlaceholder(key, value, locale)),
                ]),
        ),
        label_hosts: escapeHtml(labels.hosts),
        label_date: escapeHtml(labels.date),
        label_time: escapeHtml(labels.time),
        label_venue: escapeHtml(labels.venue),
        heroImageUrl: escapeHtml(media.hero.url),
        heroImageAlt: escapeHtml(media.hero.alt),
        backgroundImageUrl: escapeHtml(media.background.url),
        backgroundImageAlt: escapeHtml(media.background.alt),
        galleryImagesHtml: renderGalleryImagesHtml(media),
        locale: escapeHtml(locale),
    };

    let documentHtml = sourceHtml;

    Object.entries(replacements).forEach(([key, value]) => {
        documentHtml = documentHtml.split(`{{${key}}}`).join(value);
    });

    documentHtml = injectFontLink(documentHtml, state.fontFamily);
    documentHtml = applyOptionalSectionVisibility(documentHtml, state._visibility);

    return setHtmlLanguage(documentHtml, locale);
}

function generateHtmlDocument(state, template, locale, dictionary) {
    if (template.htmlSource) {
        return renderUploadedHtmlTemplate(template.htmlSource, state, locale, dictionary);
    }

    const fontLink = fontLinks[state.fontFamily] ?? fontLinks.Sora;
    const previewGradient = template.designTokens?.catalog_background
        ?? 'linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)';
    const labels = normalizeDictionary(dictionary).labels;
    const visibility = normalizeVisibility(state._visibility);
    const media = normalizeMedia(state._media);
    const editorLabels = locale === 'en'
        ? { guest: 'Guest', type: 'Type', event: 'Event', timezone: 'Timezone', map: 'View location' }
        : { guest: 'Invitado', type: 'Tipo', event: 'Evento', timezone: 'Zona horaria', map: 'Ver ubicacion' };
    const safeTitle = escapeHtml(state.headline);

    return `<!DOCTYPE html>
<html lang="${escapeHtml(locale)}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>${safeTitle}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="${fontLink}" rel="stylesheet">
    <style>
        :root {
            --accent: ${escapeHtml(state.accentColor)};
            --background: ${escapeHtml(state.backgroundColor)};
            --surface: ${escapeHtml(state.surfaceColor)};
            --text: ${escapeHtml(state.textColor)};
            --font: '${escapeHtml(state.fontFamily)}', sans-serif;
            --gradient: ${previewGradient};
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: var(--font);
            background: ${media.background.url ? `linear-gradient(rgba(255,255,255,0.84), rgba(255,255,255,0.84)), url('${escapeHtml(media.background.url)}') center/cover fixed, var(--background)` : 'radial-gradient(circle at top, rgba(255,255,255,0.8), transparent 35%), var(--background)'};
            color: var(--text);
            min-height: 100vh;
            padding: 32px 18px;
        }
        .frame {
            max-width: 920px;
            margin: 0 auto;
            border-radius: 36px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(15, 23, 42, 0.18);
            background: rgba(255,255,255,0.4);
            border: 1px solid rgba(255,255,255,0.4);
        }
        .hero {
            padding: 28px;
            background: var(--gradient);
            position: relative;
        }
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.1), rgba(255,255,255,0.18));
        }
        .hero-inner { position: relative; z-index: 1; }
        .pill {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255,255,255,0.82);
            font-size: 12px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-weight: 700;
        }
        h1 {
            margin: 22px 0 12px;
            font-size: clamp(36px, 8vw, 72px);
            line-height: 0.95;
        }
        .subtitle {
            font-size: 18px;
            line-height: 1.7;
            max-width: 640px;
            margin: 0;
        }
        .hero-photo {
            margin-top: 24px;
            width: min(520px, 100%);
            border-radius: 28px;
            border: 1px solid rgba(255,255,255,0.55);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16);
        }
        .content {
            background: var(--surface);
            padding: 28px;
            display: grid;
            gap: 22px;
        }
        .meta {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }
        .card {
            border-radius: 24px;
            padding: 18px;
            background: rgba(248, 250, 252, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }
        .label {
            margin: 0 0 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(51,65,85,0.72);
        }
        .value {
            margin: 0;
            font-size: 18px;
            line-height: 1.5;
        }
        .message {
            font-size: 17px;
            line-height: 1.8;
            margin: 0;
        }
        .cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 8px;
            padding: 16px 22px;
            border-radius: 20px;
            background: var(--accent);
            color: white;
            text-decoration: none;
            font-weight: 700;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 14px;
        }
        .gallery figure {
            margin: 0;
            border-radius: 22px;
            overflow: hidden;
            background: rgba(248,250,252,0.9);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }
        .gallery img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }
        .gallery figcaption {
            padding: 10px 12px;
            font-size: 13px;
            line-height: 1.4;
        }
        .footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
        }
        @media (max-width: 640px) {
            body { padding: 14px; }
            .hero, .content { padding: 18px; }
        }
    </style>
</head>
<body>
    <main class="frame">
        <section class="hero">
            <div class="hero-inner">
                <span class="pill">${escapeHtml(state.eventLabel)}</span>
                <h1>${safeTitle}</h1>
                <p class="subtitle">${formatHtmlText(state.subheadline)}</p>
                ${visibility.showHeroImage && media.hero.url ? `<img class="hero-photo" src="${escapeHtml(media.hero.url)}" alt="${escapeHtml(media.hero.alt)}">` : ''}
            </div>
        </section>
        <section class="content">
            ${visibility.showGuestName && state.guestName ? `<div class="card">
                <p class="label">${editorLabels.guest}</p>
                <p class="value">${formatHtmlText(state.guestName)}</p>
            </div>` : ''}
            ${visibility.showEventDetails ? `<div class="meta">
                ${state.eventType ? `<div class="card"><p class="label">${editorLabels.type}</p><p class="value">${formatHtmlText(state.eventType)}</p></div>` : ''}
                ${state.eventName ? `<div class="card"><p class="label">${editorLabels.event}</p><p class="value">${formatHtmlText(state.eventName)}</p></div>` : ''}
                ${state.dressCode ? `<div class="card"><p class="label">Dress code</p><p class="value">${formatHtmlText(state.dressCode)}</p></div>` : ''}
                ${state.rsvpDeadline ? `<div class="card"><p class="label">RSVP</p><p class="value">${formatHtmlText(state.rsvpDeadline)}</p></div>` : ''}
                ${state.timezoneLabel ? `<div class="card"><p class="label">${editorLabels.timezone}</p><p class="value">${formatHtmlText(state.timezoneLabel)}</p></div>` : ''}
            </div>` : ''}
            <div class="card">
                <p class="label">${escapeHtml(labels.hosts)}</p>
                <p class="value">${formatHtmlText(state.hosts)}</p>
            </div>
            <div class="meta">
                <div class="card">
                    <p class="label">${escapeHtml(labels.date)}</p>
                    <p class="value">${formatHtmlText(formatDateForDisplay(state.dateLabel, locale))}</p>
                </div>
                <div class="card">
                    <p class="label">${escapeHtml(labels.time)}</p>
                    <p class="value">${formatHtmlText(formatTimeForDisplay(state.timeLabel, locale))}</p>
                </div>
                <div class="card">
                    <p class="label">${escapeHtml(labels.venue)}</p>
                    <p class="value">${formatHtmlText(state.venueLabel)}</p>
                </div>
            </div>
            ${visibility.showLocation ? `<div class="card">
                <p class="label">${escapeHtml(state.venueName || labels.venue)}</p>
                <p class="value">${formatHtmlText(state.venueAddress || state.venueLabel)}</p>
                ${state.googleMapsUrl ? `<a class="cta" href="${escapeHtml(state.googleMapsUrl)}">${escapeHtml(state.mapButtonLabel || editorLabels.map)}</a>` : ''}
                ${state.appleMapsUrl ? `<a class="cta" href="${escapeHtml(state.appleMapsUrl)}" style="margin-left: 8px;">Apple Maps</a>` : ''}
            </div>` : ''}
            <div class="card">
                <p class="message">${formatHtmlText(state.message)}</p>
            </div>
            ${visibility.showGallery && media.gallery.some((item) => item.url) ? `<div class="gallery">${renderGalleryImagesHtml(media)}</div>` : ''}
            <div class="footer">
                <p class="message" style="max-width: 560px;">${formatHtmlText(state.closing)}</p>
                <a class="cta" href="#">${escapeHtml(state.buttonLabel)}</a>
            </div>
        </section>
    </main>
</body>
</html>`;
}

export default function PublicTemplateEditorPage({
    appName,
    auth = {},
    locale = 'es',
    locales = [],
    navigation = [],
    shared = {},
    content = {},
    template,
    invitation,
    saveUrl,
}) {
    const [theme, setTheme] = useState('dark');
    const iframeRef = useRef(null);
    const current = useMemo(() => normalizeContent(content), [content]);
    const dictionary = useMemo(() => normalizeDictionary(template.dictionary), [template.dictionary]);
    const contentFields = useMemo(() => (template.editorFields ?? []).filter((field) => field.group === 'content'), [template.editorFields]);
    const styleFields = useMemo(() => (template.editorFields ?? []).filter((field) => field.group === 'style'), [template.editorFields]);
    const visibilityFields = useMemo(() => (template.editorFields ?? []).filter((field) => field.group === 'visibility'), [template.editorFields]);
    const initialState = useMemo(() => buildInitialState(template), [template]);
    const [editorState, setEditorState] = useState(initialState);
    const [downloadCount, setDownloadCount] = useState(template.downloadCount);
    const [isSaving, setIsSaving] = useState(false);
    const [statusMessage, setStatusMessage] = useState('');
    const [previewHeight, setPreviewHeight] = useState(760);

    useEffect(() => {
        const savedTheme = window.localStorage.getItem('invita-plus-theme');

        if (savedTheme === 'light' || savedTheme === 'dark') {
            setTheme(savedTheme);
        }
    }, []);

    useEffect(() => {
        window.localStorage.setItem('invita-plus-theme', theme);
        document.documentElement.dataset.theme = theme;
    }, [theme]);

    useEffect(() => {
        setEditorState(initialState);
    }, [initialState]);

    useEffect(() => {
        setDownloadCount(template.downloadCount);
    }, [template.downloadCount]);

    const isLight = theme === 'light';
    const htmlDocument = useMemo(() => generateHtmlDocument(editorState, template, locale, dictionary), [dictionary, editorState, locale, template]);
    const catalogHref = navigation.find((item) => item.key === 'catalog')?.href ?? '#';

    useEffect(() => {
        setPreviewHeight(760);
    }, [htmlDocument]);

    function updatePreviewHeight() {
        const iframe = iframeRef.current;

        if (!iframe?.contentWindow?.document) {
            return;
        }

        const { body, documentElement } = iframe.contentWindow.document;
        const nextHeight = Math.max(
            body?.scrollHeight ?? 0,
            body?.offsetHeight ?? 0,
            documentElement?.scrollHeight ?? 0,
            documentElement?.offsetHeight ?? 0,
            760,
        );

        setPreviewHeight(nextHeight + 4);
    }

    function updateField(key, value) {
        setEditorState((currentState) => ({
            ...currentState,
            [key]: value,
        }));
    }

    function updateVisibility(key, value) {
        setEditorState((currentState) => ({
            ...currentState,
            _visibility: {
                ...normalizeVisibility(currentState._visibility),
                [key]: value,
            },
        }));
    }

    function updateMedia(path, value) {
        setEditorState((currentState) => {
            const media = normalizeMedia(currentState._media);
            const [group, key] = path.split('.');

            return {
                ...currentState,
                _media: {
                    ...media,
                    [group]: {
                        ...media[group],
                        [key]: value,
                    },
                },
            };
        });
    }

    function updateGalleryItem(index, key, value) {
        setEditorState((currentState) => {
            const media = normalizeMedia(currentState._media);
            const gallery = [...media.gallery];

            gallery[index] = {
                ...(gallery[index] ?? { url: '', alt: '', caption: '' }),
                [key]: value,
            };

            return {
                ...currentState,
                _media: {
                    ...media,
                    gallery,
                },
            };
        });
    }

    function addGalleryItem() {
        setEditorState((currentState) => {
            const media = normalizeMedia(currentState._media);

            return {
                ...currentState,
                _media: {
                    ...media,
                    gallery: [...media.gallery, { url: '', alt: '', caption: '' }],
                },
            };
        });
    }

    function removeGalleryItem(index) {
        setEditorState((currentState) => {
            const media = normalizeMedia(currentState._media);

            return {
                ...currentState,
                _media: {
                    ...media,
                    gallery: media.gallery.filter((item, itemIndex) => itemIndex !== index),
                },
            };
        });
    }

    async function persistInvitation(downloaded = false) {
        if (!saveUrl || !invitation?.editToken) {
            return null;
        }

        setIsSaving(true);
        setStatusMessage('');

        try {
            const response = await window.fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    edit_token: invitation.editToken,
                    editor_state: editorState,
                    html_document: htmlDocument,
                    downloaded,
                }),
            });

            if (!response.ok) {
                throw new Error('save_failed');
            }

            const payload = await response.json();

            if (typeof payload.download_count === 'number') {
                setDownloadCount(payload.download_count);
            }

            setStatusMessage(downloaded ? current.status.downloaded : current.status.saved);

            return payload;
        } catch (error) {
            setStatusMessage(current.status.save_failed);
            return null;
        } finally {
            setIsSaving(false);
        }
    }

    async function downloadHtml() {
        const payload = await persistInvitation(true);

        if (!payload) {
            return;
        }

        const blob = new Blob([htmlDocument], { type: 'text/html;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `${template.slug || template.code}.html`;
        link.click();
        URL.revokeObjectURL(url);
    }

    return (
        <PublicLayout
            appName={appName}
            auth={auth}
            footerCopy={{
                left: template.name,
                right: template.teaser ?? template.description ?? '',
            }}
            theme={theme}
            headerProps={{
                navItems: navigation,
                locale,
                locales,
                onLocaleChange: (code) => {
                    const target = locales.find((item) => item.code === code);

                    if (target?.href) {
                        window.location.href = target.href;
                    }
                },
                onThemeToggle: () => setTheme((value) => (value === 'dark' ? 'light' : 'dark')),
                labels: {
                    kicker: current.header.kicker ?? '',
                    cta: current.header.cta ?? '',
                },
                uiLabels: shared.header ?? {},
            }}
        >
            <section className="px-2 pt-4">
                <div className="mx-auto max-w-7xl space-y-8">
                    <div className="grid gap-6 xl:grid-cols-[1.2fr_0.8fr] xl:items-end">
                        <div>
                            <div className={`inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] ${isLight ? 'border-sky-200 bg-white/80 text-sky-700' : 'border-white/10 bg-white/8 text-sky-200'}`}>
                                <span className={`h-2 w-2 rounded-full ${isLight ? 'bg-sky-500' : 'bg-sky-300'}`} />
                                {current.hero.eyebrow}
                            </div>
                            <h1 className={`mt-6 max-w-4xl text-5xl font-semibold leading-tight tracking-tight text-balance sm:text-6xl ${isLight ? 'text-slate-950' : 'text-white'}`}>
                                {current.hero.title}
                            </h1>
                            <p className={`mt-4 max-w-3xl text-lg leading-8 sm:text-xl ${isLight ? 'text-slate-600' : 'text-slate-300'}`}>
                                {current.hero.subtitle}
                            </p>
                        </div>

                        <div className={`rounded-[2rem] border p-5 ${isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'}`}>
                            <div className="flex items-start justify-between gap-4">
                                <div>
                                    <p className={`text-sm uppercase tracking-[0.24em] ${isLight ? 'text-slate-500' : 'text-white/55'}`}>{template.categoryName}</p>
                                    <h2 className={`mt-2 text-3xl font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{template.name}</h2>
                                    <p className={`mt-3 text-base leading-7 ${isLight ? 'text-slate-600' : 'text-white/68'}`}>{template.teaser ?? template.description}</p>
                                </div>
                                <div className={`rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] ${template.isPremium ? 'bg-indigo-600 text-white' : isLight ? 'bg-slate-100 text-slate-700' : 'bg-white/10 text-white/80'}`}>
                                    {template.isPremium ? current.badges.premium : current.badges.base}
                                </div>
                            </div>
                            <div className={`mt-5 grid grid-cols-3 gap-2 rounded-[1.2rem] border px-3 py-3 text-center text-xs ${isLight ? 'border-slate-200 bg-slate-50 text-slate-600' : 'border-white/10 bg-slate-900/35 text-white/70'}`}>
                                <div>
                                    <p className="font-semibold">{template.viewCount}</p>
                                    <p className="mt-1 uppercase tracking-[0.18em]">{current.stats.views}</p>
                                </div>
                                <div>
                                    <p className="font-semibold">{downloadCount}</p>
                                    <p className="mt-1 uppercase tracking-[0.18em]">{current.stats.downloads}</p>
                                </div>
                                <div>
                                    <p className="font-semibold">{template.useCount}</p>
                                    <p className="mt-1 uppercase tracking-[0.18em]">{current.stats.uses}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
                        <aside className="space-y-5">
                            <div className={`rounded-[2rem] border p-5 ${isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'}`}>
                                <div className="flex items-center justify-between gap-3">
                                    <h3 className={`text-lg font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{current.panels.content}</h3>
                                    <button
                                        type="button"
                                        onClick={() => setEditorState(initialState)}
                                        className={`rounded-full px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] ${isLight ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-white/8 text-white/75 hover:bg-white/12'} transition`}
                                    >
                                        {current.actions.reset}
                                    </button>
                                </div>

                                <div className="mt-5 space-y-4">
                                    {contentFields.map((field) => {
                                        const label = current.fields[field.label_key] ?? field.key;
                                        const value = editorState[field.key] ?? '';
                                        const sharedClass = `w-full rounded-2xl border px-4 py-3 text-sm outline-none transition ${isLight ? 'border-slate-200 bg-slate-50 text-slate-900 focus:border-sky-400' : 'border-white/10 bg-slate-900/45 text-white focus:border-sky-400'}`;

                                        if (field.type === 'timezone') {
                                            return (
                                                <label key={field.key} className="block">
                                                    <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{label}</span>
                                                    <select
                                                        value={value || 'America/La_Paz'}
                                                        onChange={(event) => updateField(field.key, event.target.value)}
                                                        className={sharedClass}
                                                    >
                                                        {timezoneOptions.map((timezone) => (
                                                            <option key={timezone} value={timezone}>{timezone}</option>
                                                        ))}
                                                    </select>
                                                </label>
                                            );
                                        }

                                        return (
                                            <label key={field.key} className="block">
                                                <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{label}</span>
                                                {field.multiline ? (
                                                    <textarea
                                                        value={value}
                                                        onChange={(event) => updateField(field.key, event.target.value)}
                                                        rows={field.key === 'message' ? 4 : 3}
                                                        className={sharedClass}
                                                    />
                                                ) : (
                                                    <input
                                                        type={['url', 'date', 'time'].includes(field.type) ? field.type : 'text'}
                                                        value={value}
                                                        onChange={(event) => updateField(field.key, event.target.value)}
                                                        className={sharedClass}
                                                    />
                                                )}
                                            </label>
                                        );
                                    })}
                                </div>
                            </div>

                            <div className={`rounded-[2rem] border p-5 ${isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'}`}>
                                <h3 className={`text-lg font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{current.panels.visibility}</h3>
                                <div className="mt-5 space-y-3">
                                    {visibilityFields.map((field) => {
                                        const label = current.fields[field.label_key] ?? field.key;
                                        const checked = normalizeVisibility(editorState._visibility)[field.key];

                                        return (
                                            <label key={field.key} className={`flex cursor-pointer items-center justify-between gap-4 rounded-2xl border px-4 py-3 text-sm ${isLight ? 'border-slate-200 bg-slate-50 text-slate-700' : 'border-white/10 bg-slate-900/45 text-white/75'}`}>
                                                <span>{label}</span>
                                                <input
                                                    type="checkbox"
                                                    checked={checked}
                                                    onChange={(event) => updateVisibility(field.key, event.target.checked)}
                                                    className="h-4 w-4 cursor-pointer rounded border-white/20 bg-transparent"
                                                />
                                            </label>
                                        );
                                    })}
                                </div>
                            </div>

                            <div className={`rounded-[2rem] border p-5 ${isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'}`}>
                                <div className="flex items-center justify-between gap-3">
                                    <h3 className={`text-lg font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{current.panels.media}</h3>
                                    <button
                                        type="button"
                                        onClick={addGalleryItem}
                                        className={`rounded-full px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] ${isLight ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-white/8 text-white/75 hover:bg-white/12'} transition`}
                                    >
                                        {current.fields.add_gallery_image}
                                    </button>
                                </div>

                                <div className="mt-5 space-y-4">
                                    <label className="block">
                                        <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{current.fields.hero_image_url}</span>
                                        <input type="url" value={normalizeMedia(editorState._media).hero.url} onChange={(event) => updateMedia('hero.url', event.target.value)} className={`w-full rounded-2xl border px-4 py-3 text-sm outline-none transition ${isLight ? 'border-slate-200 bg-slate-50 text-slate-900 focus:border-sky-400' : 'border-white/10 bg-slate-900/45 text-white focus:border-sky-400'}`} />
                                    </label>
                                    <label className="block">
                                        <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{current.fields.hero_image_alt}</span>
                                        <input value={normalizeMedia(editorState._media).hero.alt} onChange={(event) => updateMedia('hero.alt', event.target.value)} className={`w-full rounded-2xl border px-4 py-3 text-sm outline-none transition ${isLight ? 'border-slate-200 bg-slate-50 text-slate-900 focus:border-sky-400' : 'border-white/10 bg-slate-900/45 text-white focus:border-sky-400'}`} />
                                    </label>
                                    <label className="block">
                                        <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{current.fields.background_image_url}</span>
                                        <input type="url" value={normalizeMedia(editorState._media).background.url} onChange={(event) => updateMedia('background.url', event.target.value)} className={`w-full rounded-2xl border px-4 py-3 text-sm outline-none transition ${isLight ? 'border-slate-200 bg-slate-50 text-slate-900 focus:border-sky-400' : 'border-white/10 bg-slate-900/45 text-white focus:border-sky-400'}`} />
                                    </label>
                                    <label className="block">
                                        <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{current.fields.background_image_alt}</span>
                                        <input value={normalizeMedia(editorState._media).background.alt} onChange={(event) => updateMedia('background.alt', event.target.value)} className={`w-full rounded-2xl border px-4 py-3 text-sm outline-none transition ${isLight ? 'border-slate-200 bg-slate-50 text-slate-900 focus:border-sky-400' : 'border-white/10 bg-slate-900/45 text-white focus:border-sky-400'}`} />
                                    </label>

                                    {normalizeMedia(editorState._media).gallery.map((item, index) => (
                                        <div key={index} className={`rounded-2xl border p-3 ${isLight ? 'border-slate-200 bg-slate-50' : 'border-white/10 bg-slate-900/45'}`}>
                                            <div className="flex items-center justify-between gap-3">
                                                <span className={`text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>Gallery {index + 1}</span>
                                                <button type="button" onClick={() => removeGalleryItem(index)} className="text-xs font-semibold text-rose-400">
                                                    {current.fields.remove_gallery_image}
                                                </button>
                                            </div>
                                            <div className="mt-3 space-y-3">
                                                <input type="url" value={item.url} onChange={(event) => updateGalleryItem(index, 'url', event.target.value)} placeholder={current.fields.gallery_image_url} className={`w-full rounded-xl border px-3 py-2 text-sm outline-none ${isLight ? 'border-slate-200 bg-white text-slate-900' : 'border-white/10 bg-slate-950/45 text-white'}`} />
                                                <input value={item.alt} onChange={(event) => updateGalleryItem(index, 'alt', event.target.value)} placeholder={current.fields.gallery_image_alt} className={`w-full rounded-xl border px-3 py-2 text-sm outline-none ${isLight ? 'border-slate-200 bg-white text-slate-900' : 'border-white/10 bg-slate-950/45 text-white'}`} />
                                                <input value={item.caption} onChange={(event) => updateGalleryItem(index, 'caption', event.target.value)} placeholder={current.fields.gallery_image_caption} className={`w-full rounded-xl border px-3 py-2 text-sm outline-none ${isLight ? 'border-slate-200 bg-white text-slate-900' : 'border-white/10 bg-slate-950/45 text-white'}`} />
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div className={`rounded-[2rem] border p-5 ${isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'}`}>
                                <h3 className={`text-lg font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{current.panels.style}</h3>
                                <div className="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                                    {styleFields.map((field) => {
                                        const label = current.fields[field.label_key] ?? field.key;
                                        const value = editorState[field.key] ?? '';

                                        if (field.type === 'select') {
                                            return (
                                                <label key={field.key} className="block sm:col-span-2 xl:col-span-1">
                                                    <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{label}</span>
                                                    <select
                                                        value={value}
                                                        onChange={(event) => updateField(field.key, event.target.value)}
                                                        className={`w-full rounded-2xl border px-4 py-3 text-sm outline-none transition ${isLight ? 'border-slate-200 bg-slate-50 text-slate-900 focus:border-sky-400' : 'border-white/10 bg-slate-900/45 text-white focus:border-sky-400'}`}
                                                    >
                                                        {template.availableFonts.map((font) => (
                                                            <option key={font} value={font}>{font}</option>
                                                        ))}
                                                    </select>
                                                </label>
                                            );
                                        }

                                        return (
                                            <label key={field.key} className="block">
                                                <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{label}</span>
                                                <div className={`flex items-center gap-3 rounded-2xl border px-3 py-2 ${isLight ? 'border-slate-200 bg-slate-50' : 'border-white/10 bg-slate-900/45'}`}>
                                                    <input type="color" value={value} onChange={(event) => updateField(field.key, event.target.value)} className="h-10 w-12 rounded-xl border-0 bg-transparent" />
                                                    <input value={value} onChange={(event) => updateField(field.key, event.target.value)} className={`w-full bg-transparent text-sm outline-none ${isLight ? 'text-slate-900' : 'text-white'}`} />
                                                </div>
                                            </label>
                                        );
                                    })}
                                </div>
                            </div>

                            <div className={`rounded-[2rem] border p-5 ${isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'}`}>
                                <h3 className={`text-lg font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{current.panels.tips}</h3>
                                <div className="mt-4 space-y-3">
                                    {current.tips.map((tip) => (
                                        <div key={tip} className={`rounded-2xl px-4 py-3 text-sm leading-7 ${isLight ? 'bg-slate-50 text-slate-600' : 'bg-white/6 text-white/75'}`}>
                                            {tip}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </aside>

                        <section className={`rounded-[2rem] border p-5 ${isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'}`}>
                            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 className={`text-lg font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{current.panels.preview}</h3>
                                    <p className={`mt-2 text-sm ${isLight ? 'text-slate-500' : 'text-white/55'}`}>{template.description}</p>
                                    {invitation?.editToken ? (
                                        <p className={`mt-2 text-xs uppercase tracking-[0.18em] ${isLight ? 'text-slate-400' : 'text-white/35'}`}>
                                            {current.preview.edit_token}: {invitation.editToken}
                                        </p>
                                    ) : null}
                                </div>
                                <div className="flex flex-wrap gap-3">
                                    <a
                                        href={catalogHref}
                                        className={`rounded-2xl border px-5 py-3 text-sm font-semibold transition ${isLight ? 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' : 'border-white/10 bg-white/6 text-white hover:bg-white/10'}`}
                                    >
                                        {current.actions.open_catalog}
                                    </a>
                                    <button
                                        type="button"
                                        onClick={() => persistInvitation(false)}
                                        disabled={isSaving}
                                        className={`rounded-2xl border px-5 py-3 text-sm font-semibold transition ${isLight ? 'border-slate-200 bg-slate-100 text-slate-700 hover:bg-slate-200' : 'border-white/10 bg-white/8 text-white hover:bg-white/12'}`}
                                    >
                                        {current.actions.save}
                                    </button>
                                    <button
                                        id="editor-download"
                                        type="button"
                                        onClick={downloadHtml}
                                        disabled={isSaving}
                                        className={`rounded-2xl px-5 py-3 text-sm font-semibold transition ${isLight ? 'bg-indigo-600 text-white shadow-[0_18px_34px_rgba(79,70,229,0.22)] hover:bg-indigo-500' : 'bg-indigo-500 text-white shadow-[0_18px_34px_rgba(99,102,241,0.22)] hover:bg-indigo-400'}`}
                                    >
                                        {isSaving ? current.status.saving : current.actions.download}
                                    </button>
                                </div>
                            </div>

                            {statusMessage ? (
                                <div className={`mt-4 rounded-2xl px-4 py-3 text-sm ${isLight ? 'bg-slate-50 text-slate-600' : 'bg-white/6 text-white/75'}`}>
                                    {statusMessage}
                                </div>
                            ) : null}

                            <div className={`mt-5 overflow-hidden rounded-[1.8rem] border ${isLight ? 'border-slate-200 bg-slate-100' : 'border-white/10 bg-slate-950/70'}`}>
                                <div className="flex items-center gap-2 px-4 py-3">
                                    <span className="h-3 w-3 rounded-full bg-rose-400" />
                                    <span className="h-3 w-3 rounded-full bg-amber-300" />
                                    <span className="h-3 w-3 rounded-full bg-emerald-400" />
                                </div>
                                <iframe
                                    ref={iframeRef}
                                    title={`${template.name} preview`}
                                    srcDoc={htmlDocument}
                                    onLoad={updatePreviewHeight}
                                    className="w-full bg-white"
                                    style={{ height: `${previewHeight}px` }}
                                />
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
