import { useEffect, useMemo, useState } from 'react';

import PublicLayout from '../components/public/PublicLayout';

const fontLinks = {
    Sora: 'https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap',
    Manrope: 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap',
    'Cormorant Garamond': 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&display=swap',
};

const accentPalette = {
    sky: '#38bdf8',
    indigo: '#6366f1',
    cyan: '#06b6d4',
    rose: '#f43f5e',
    slate: '#334155',
};

function normalizeContent(content = {}) {
    return {
        header: content.header ?? {},
        hero: content.hero ?? {},
        actions: content.actions ?? {},
        panels: content.panels ?? {},
        fields: content.fields ?? {},
        stats: content.stats ?? {},
        tips: content.tips ?? [],
    };
}

function buildInitialState(template, locale) {
    const defaults = template.defaultContent ?? {};
    const savedState = template.savedState ?? {};
    const baseHeadline = locale === 'es' ? `${template.name} para tu gran momento` : `${template.name} for your next big moment`;
    const baseSubtitle = locale === 'es'
        ? 'Edita cada detalle y comparte una invitacion que se sienta unica.'
        : 'Edit every detail and share an invitation that feels unique.';

    return {
        eventLabel: savedState.eventLabel ?? defaults.eventLabel ?? template.categoryName,
        headline: savedState.headline ?? defaults.headline ?? baseHeadline,
        subheadline: savedState.subheadline ?? defaults.subheadline ?? baseSubtitle,
        hosts: savedState.hosts ?? defaults.hosts ?? (locale === 'es' ? 'Andrea y Miguel' : 'Andrea and Michael'),
        dateLabel: savedState.dateLabel ?? defaults.dateLabel ?? (locale === 'es' ? 'Sabado 18 de octubre, 2026' : 'Saturday, October 18, 2026'),
        timeLabel: savedState.timeLabel ?? defaults.timeLabel ?? '07:30 PM',
        venueLabel: savedState.venueLabel ?? defaults.venueLabel ?? (locale === 'es' ? 'Jardines del Lago, Cochabamba' : 'Lake Gardens, Santa Cruz'),
        message: savedState.message ?? defaults.message ?? template.description ?? template.teaser ?? '',
        closing: savedState.closing ?? defaults.closing ?? (locale === 'es' ? 'Confirma tu asistencia y comparte este momento con nosotros.' : 'Confirm your attendance and share this moment with us.'),
        buttonLabel: savedState.buttonLabel ?? defaults.buttonLabel ?? (locale === 'es' ? 'Confirmar asistencia' : 'Confirm attendance'),
        accentColor: savedState.accentColor ?? defaults.accentColor ?? accentPalette[template.designTokens?.accent] ?? '#6366f1',
        backgroundColor: savedState.backgroundColor ?? defaults.backgroundColor ?? '#eef6ff',
        surfaceColor: savedState.surfaceColor ?? defaults.surfaceColor ?? '#ffffff',
        textColor: savedState.textColor ?? defaults.textColor ?? '#0f172a',
        fontFamily: savedState.fontFamily ?? defaults.fontFamily ?? template.availableFonts?.[0] ?? 'Sora',
    };
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function generateHtmlDocument(state, template) {
    const fontLink = fontLinks[state.fontFamily] ?? fontLinks.Sora;
    const safe = Object.fromEntries(Object.entries(state).map(([key, value]) => [key, escapeHtml(value)]));
    const previewGradient = template.designTokens?.catalog_background
        ?? 'linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)';

    return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>${safe.headline}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="${fontLink}" rel="stylesheet">
    <style>
        :root {
            --accent: ${safe.accentColor};
            --background: ${safe.backgroundColor};
            --surface: ${safe.surfaceColor};
            --text: ${safe.textColor};
            --font: '${escapeHtml(state.fontFamily)}', sans-serif;
            --gradient: ${previewGradient};
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: var(--font);
            background: radial-gradient(circle at top, rgba(255,255,255,0.8), transparent 35%), var(--background);
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
                <span class="pill">${safe.eventLabel}</span>
                <h1>${safe.headline}</h1>
                <p class="subtitle">${safe.subheadline}</p>
            </div>
        </section>
        <section class="content">
            <div class="card">
                <p class="label">Hosts</p>
                <p class="value">${safe.hosts}</p>
            </div>
            <div class="meta">
                <div class="card">
                    <p class="label">Date</p>
                    <p class="value">${safe.dateLabel}</p>
                </div>
                <div class="card">
                    <p class="label">Time</p>
                    <p class="value">${safe.timeLabel}</p>
                </div>
                <div class="card">
                    <p class="label">Venue</p>
                    <p class="value">${safe.venueLabel}</p>
                </div>
            </div>
            <div class="card">
                <p class="message">${safe.message}</p>
            </div>
            <div class="footer">
                <p class="message" style="max-width: 560px;">${safe.closing}</p>
                <a class="cta" href="#">${safe.buttonLabel}</a>
            </div>
        </section>
    </main>
</body>
</html>`;
}

export default function PublicTemplateEditorPage({
    appName,
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
    const current = useMemo(() => normalizeContent(content), [content]);
    const initialState = useMemo(() => buildInitialState(template, locale), [template, locale]);
    const [editorState, setEditorState] = useState(initialState);
    const [downloadCount, setDownloadCount] = useState(template.downloadCount);
    const [isSaving, setIsSaving] = useState(false);
    const [statusMessage, setStatusMessage] = useState('');

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
    const htmlDocument = useMemo(() => generateHtmlDocument(editorState, template), [editorState, template]);
    const catalogHref = navigation.find((item) => item.key === 'catalog')?.href ?? '#';

    function updateField(key, value) {
        setEditorState((currentState) => ({
            ...currentState,
            [key]: value,
        }));
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

            setStatusMessage(downloaded ? 'Draft saved and HTML downloaded.' : 'Draft saved.');

            return payload;
        } catch (error) {
            setStatusMessage('We could not save the draft this time.');
            return null;
        } finally {
            setIsSaving(false);
        }
    }

    async function downloadHtml() {
        await persistInvitation(true);

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
                                    {template.isPremium ? 'Premium' : 'Base'}
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
                                    {[
                                        ['eventLabel', current.fields.event_label],
                                        ['headline', current.fields.headline],
                                        ['subheadline', current.fields.subheadline],
                                        ['hosts', current.fields.hosts],
                                        ['dateLabel', current.fields.date_label],
                                        ['timeLabel', current.fields.time_label],
                                        ['venueLabel', current.fields.venue_label],
                                        ['message', current.fields.message],
                                        ['closing', current.fields.closing],
                                        ['buttonLabel', current.fields.button_label],
                                    ].map(([key, label]) => (
                                        <label key={key} className="block">
                                            <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{label}</span>
                                            {key === 'message' || key === 'closing' || key === 'subheadline' ? (
                                                <textarea
                                                    value={editorState[key]}
                                                    onChange={(event) => updateField(key, event.target.value)}
                                                    rows={key === 'message' ? 4 : 3}
                                                    className={`w-full rounded-2xl border px-4 py-3 text-sm outline-none transition ${isLight ? 'border-slate-200 bg-slate-50 text-slate-900 focus:border-sky-400' : 'border-white/10 bg-slate-900/45 text-white focus:border-sky-400'}`}
                                                />
                                            ) : (
                                                <input
                                                    value={editorState[key]}
                                                    onChange={(event) => updateField(key, event.target.value)}
                                                    className={`w-full rounded-2xl border px-4 py-3 text-sm outline-none transition ${isLight ? 'border-slate-200 bg-slate-50 text-slate-900 focus:border-sky-400' : 'border-white/10 bg-slate-900/45 text-white focus:border-sky-400'}`}
                                                />
                                            )}
                                        </label>
                                    ))}
                                </div>
                            </div>

                            <div className={`rounded-[2rem] border p-5 ${isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'}`}>
                                <h3 className={`text-lg font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{current.panels.style}</h3>
                                <div className="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                                    {[
                                        ['accentColor', current.fields.accent_color],
                                        ['backgroundColor', current.fields.background_color],
                                        ['surfaceColor', current.fields.surface_color],
                                        ['textColor', current.fields.text_color],
                                    ].map(([key, label]) => (
                                        <label key={key} className="block">
                                            <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{label}</span>
                                            <div className={`flex items-center gap-3 rounded-2xl border px-3 py-2 ${isLight ? 'border-slate-200 bg-slate-50' : 'border-white/10 bg-slate-900/45'}`}>
                                                <input type="color" value={editorState[key]} onChange={(event) => updateField(key, event.target.value)} className="h-10 w-12 rounded-xl border-0 bg-transparent" />
                                                <input value={editorState[key]} onChange={(event) => updateField(key, event.target.value)} className={`w-full bg-transparent text-sm outline-none ${isLight ? 'text-slate-900' : 'text-white'}`} />
                                            </div>
                                        </label>
                                    ))}

                                    <label className="block sm:col-span-2 xl:col-span-1">
                                        <span className={`mb-2 block text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{current.fields.font_family}</span>
                                        <select
                                            value={editorState.fontFamily}
                                            onChange={(event) => updateField('fontFamily', event.target.value)}
                                            className={`w-full rounded-2xl border px-4 py-3 text-sm outline-none transition ${isLight ? 'border-slate-200 bg-slate-50 text-slate-900 focus:border-sky-400' : 'border-white/10 bg-slate-900/45 text-white focus:border-sky-400'}`}
                                        >
                                            {template.availableFonts.map((font) => (
                                                <option key={font} value={font}>{font}</option>
                                            ))}
                                        </select>
                                    </label>
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
                                            Edit token: {invitation.editToken}
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
                                        id="editor-download"
                                        type="button"
                                        onClick={downloadHtml}
                                        disabled={isSaving}
                                        className={`rounded-2xl px-5 py-3 text-sm font-semibold transition ${isLight ? 'bg-indigo-600 text-white shadow-[0_18px_34px_rgba(79,70,229,0.22)] hover:bg-indigo-500' : 'bg-indigo-500 text-white shadow-[0_18px_34px_rgba(99,102,241,0.22)] hover:bg-indigo-400'}`}
                                    >
                                        {isSaving ? 'Saving...' : current.actions.download}
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
                                    title={`${template.name} preview`}
                                    srcDoc={htmlDocument}
                                    className="h-[760px] w-full bg-white"
                                />
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
