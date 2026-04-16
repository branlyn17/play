import { useEffect, useMemo, useState } from 'react';

import PublicLayout from '../components/public/PublicLayout';

const fontLinks = {
    Sora: 'https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap',
    Manrope: 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap',
    'Cormorant Garamond': 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&display=swap',
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

function buildInitialState(template) {
    const defaults = {
        ...(template.defaultContent?.content ?? {}),
        ...(template.defaultContent?.style ?? {}),
    };
    const savedState = { ...(template.savedState ?? {}) };

    delete savedState._meta;

    return {
        ...defaults,
        ...savedState,
    };
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

function generateHtmlDocument(state, template, locale, dictionary) {
    const fontLink = fontLinks[state.fontFamily] ?? fontLinks.Sora;
    const previewGradient = template.designTokens?.catalog_background
        ?? 'linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)';
    const labels = normalizeDictionary(dictionary).labels;
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
                <span class="pill">${escapeHtml(state.eventLabel)}</span>
                <h1>${safeTitle}</h1>
                <p class="subtitle">${formatHtmlText(state.subheadline)}</p>
            </div>
        </section>
        <section class="content">
            <div class="card">
                <p class="label">${escapeHtml(labels.hosts)}</p>
                <p class="value">${formatHtmlText(state.hosts)}</p>
            </div>
            <div class="meta">
                <div class="card">
                    <p class="label">${escapeHtml(labels.date)}</p>
                    <p class="value">${formatHtmlText(state.dateLabel)}</p>
                </div>
                <div class="card">
                    <p class="label">${escapeHtml(labels.time)}</p>
                    <p class="value">${formatHtmlText(state.timeLabel)}</p>
                </div>
                <div class="card">
                    <p class="label">${escapeHtml(labels.venue)}</p>
                    <p class="value">${formatHtmlText(state.venueLabel)}</p>
                </div>
            </div>
            <div class="card">
                <p class="message">${formatHtmlText(state.message)}</p>
            </div>
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
    const dictionary = useMemo(() => normalizeDictionary(template.dictionary), [template.dictionary]);
    const contentFields = useMemo(() => (template.editorFields ?? []).filter((field) => field.group === 'content'), [template.editorFields]);
    const styleFields = useMemo(() => (template.editorFields ?? []).filter((field) => field.group === 'style'), [template.editorFields]);
    const initialState = useMemo(() => buildInitialState(template), [template]);
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
    const htmlDocument = useMemo(() => generateHtmlDocument(editorState, template, locale, dictionary), [dictionary, editorState, locale, template]);
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
