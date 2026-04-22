import { useEffect, useMemo, useState } from 'react';

import PublicLayout from '../components/public/PublicLayout';

function normalizeCatalogContent(content = {}) {
    const hero = content.hero ?? {};

    return {
        header: content.header ?? {},
        hero: {
            eyebrow: hero.eyebrow ?? '',
            title: hero.title ?? '',
            subtitle: hero.subtitle ?? '',
            primaryAction: hero.primary_action ?? '',
            secondaryAction: hero.secondary_action ?? '',
        },
        filtersLabel: content.filters_label ?? '',
        visibleTemplatesLabel: content.visible_templates_label ?? '',
        activeCategoriesLabel: content.active_categories_label ?? '',
        allFilterLabel: content.all_filter_label ?? '',
        categorySectionLabel: content.category_section_label ?? 'Coleccion',
        categoryTemplatesLabel: content.category_templates_label ?? 'plantillas',
        collectionHint: content.collection_hint ?? 'Desliza para explorar esta coleccion',
        scrollPreviousLabel: content.scroll_previous_label ?? 'Anterior',
        scrollNextLabel: content.scroll_next_label ?? 'Siguiente',
        footer: content.footer ?? {},
        viewLabel: content.view_label ?? '',
        viewsLabel: content.views_label ?? '',
        downloadsLabel: content.downloads_label ?? '',
        usesLabel: content.uses_label ?? '',
        premiumLabel: content.premium_label ?? '',
        baseLabel: content.base_label ?? '',
        emptyStateTitle: content.empty_state_title ?? '',
        emptyStateText: content.empty_state_text ?? '',
    };
}

function formatMetric(value) {
    return new Intl.NumberFormat().format(Number(value ?? 0));
}

function scrollCarousel(sectionKey, direction) {
    const carousel = document.querySelector(`[data-catalog-carousel="${sectionKey}"]`);

    if (!carousel) {
        return;
    }

    const distance = Math.min(carousel.clientWidth * 0.86, 760);
    carousel.scrollBy({
        left: direction === 'next' ? distance : -distance,
        behavior: 'smooth',
    });
}

function TemplateCard({ appName, current, isLight, template }) {
    return (
        <a
            href={template.href}
            className={`group flex w-[82vw] shrink-0 snap-start flex-col overflow-hidden rounded-[1.8rem] border transition duration-300 hover:-translate-y-1 hover:shadow-[0_28px_70px_rgba(15,23,42,0.22)] sm:w-[22rem] lg:w-[24rem] ${
                isLight ? 'border-slate-200 bg-white/86' : 'border-white/10 bg-white/6'
            }`}
        >
            <div className="h-64 p-4">
                <div
                    className="relative h-full overflow-hidden rounded-[1.35rem] border border-white/20"
                    style={{ background: template.background }}
                >
                    {template.previewImageUrl ? (
                        <img
                            src={template.thumbnailImageUrl ?? template.previewImageUrl}
                            alt={template.name}
                            loading="lazy"
                            className="absolute inset-0 h-full w-full object-cover object-top transition duration-700 group-hover:scale-[1.04]"
                        />
                    ) : null}

                    <div
                        className={`absolute inset-0 ${
                            template.previewImageUrl
                                ? isLight
                                    ? 'bg-[linear-gradient(180deg,rgba(255,255,255,0.02),rgba(15,23,42,0.16))]'
                                    : 'bg-[linear-gradient(180deg,rgba(15,23,42,0.02),rgba(15,23,42,0.3))]'
                                : isLight
                                  ? 'bg-[linear-gradient(180deg,rgba(255,255,255,0.08),rgba(255,255,255,0.14))]'
                                  : 'bg-[linear-gradient(180deg,rgba(15,23,42,0.05),rgba(15,23,42,0.18))]'
                        }`}
                    />

                    <div className="relative flex h-full flex-col justify-between p-5">
                        <div className="flex items-center justify-between gap-3">
                            <div
                                className={`rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] ${
                                    isLight ? 'bg-white/82 text-slate-700' : 'bg-white/12 text-white/80'
                                }`}
                            >
                                {template.categoryName}
                            </div>
                            <div
                                className={`rounded-full px-3 py-1 text-[11px] uppercase tracking-[0.22em] ${
                                    template.isPremium
                                        ? isLight
                                            ? 'bg-indigo-600 text-white'
                                            : 'bg-indigo-500 text-white'
                                        : isLight
                                          ? 'bg-slate-900/8 text-slate-600'
                                          : 'bg-black/15 text-white/60'
                                }`}
                            >
                                {template.isPremium ? current.premiumLabel : current.baseLabel}
                            </div>
                        </div>

                        {!template.previewImageUrl ? (
                            <div>
                                <p className={`text-sm uppercase tracking-[0.28em] ${isLight ? 'text-slate-600' : 'text-white/65'}`}>
                                    {appName}
                                </p>
                                <h3 className={`mt-3 text-4xl font-semibold tracking-tight ${isLight ? 'text-slate-950' : 'text-white'}`}>
                                    {template.name}
                                </h3>
                                <div
                                    className={`mt-4 inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] ${
                                        isLight ? 'bg-white/75 text-slate-700' : 'bg-white/10 text-white/78'
                                    }`}
                                >
                                    {template.badge}
                                </div>
                            </div>
                        ) : (
                            <div className="flex justify-end">
                                <div className={`rounded-2xl px-4 py-3 text-right backdrop-blur-xl ${isLight ? 'bg-white/82 text-slate-800' : 'bg-slate-950/36 text-white'}`}>
                                    <p className="text-[10px] font-semibold uppercase tracking-[0.24em] opacity-70">{current.viewLabel}</p>
                                    <p className="mt-1 text-sm font-semibold">{template.name}</p>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            <div className="flex flex-1 flex-col px-5 pb-5">
                <div>
                    <p className={`text-xl font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{template.name}</p>
                    <p className={`mt-2 text-sm ${isLight ? 'text-slate-500' : 'text-white/55'}`}>{template.mood}</p>
                </div>

                <p className={`mt-4 line-clamp-3 text-base leading-7 ${isLight ? 'text-slate-600' : 'text-white/68'}`}>
                    {template.description || template.teaser}
                </p>

                <div
                    className={`mt-auto grid grid-cols-3 gap-2 rounded-[1.2rem] border px-3 py-3 text-center text-xs ${
                        isLight ? 'border-slate-200 bg-slate-50 text-slate-600' : 'border-white/10 bg-slate-900/35 text-white/70'
                    }`}
                >
                    <div>
                        <p className="font-semibold">{formatMetric(template.viewCount)}</p>
                        <p className="mt-1 uppercase tracking-[0.16em]">{current.viewsLabel}</p>
                    </div>
                    <div>
                        <p className="font-semibold">{formatMetric(template.downloadCount)}</p>
                        <p className="mt-1 uppercase tracking-[0.16em]">{current.downloadsLabel}</p>
                    </div>
                    <div>
                        <p className="font-semibold">{formatMetric(template.useCount)}</p>
                        <p className="mt-1 uppercase tracking-[0.16em]">{current.usesLabel}</p>
                    </div>
                </div>
            </div>
        </a>
    );
}

function CategoryCarousel({ appName, category, current, isLight }) {
    return (
        <section
            className={`overflow-hidden rounded-[2.2rem] border ${
                isLight
                    ? 'border-slate-200 bg-white/62 shadow-[0_24px_70px_rgba(148,163,184,0.18)]'
                    : 'border-white/10 bg-white/[0.045] shadow-[0_24px_70px_rgba(2,6,23,0.24)]'
            }`}
        >
            <div className="flex flex-col gap-5 border-b border-white/10 p-5 sm:p-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div
                        className={`inline-flex rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] ${
                            isLight ? 'bg-indigo-50 text-indigo-700' : 'bg-indigo-400/10 text-indigo-200'
                        }`}
                    >
                        {current.categorySectionLabel}
                    </div>
                    <h2 className={`mt-4 text-3xl font-semibold tracking-tight sm:text-4xl ${isLight ? 'text-slate-950' : 'text-white'}`}>
                        {category.name}
                    </h2>
                    <p className={`mt-3 max-w-2xl text-base leading-7 ${isLight ? 'text-slate-600' : 'text-white/68'}`}>
                        {category.description || current.collectionHint}
                    </p>
                </div>

                <div className="flex items-center justify-between gap-3 lg:justify-end">
                    <div className={`rounded-full px-4 py-2 text-sm font-semibold ${isLight ? 'bg-slate-100 text-slate-600' : 'bg-white/8 text-white/68'}`}>
                        {category.templates.length} {current.categoryTemplatesLabel}
                    </div>
                    <div className="flex gap-2">
                        <button
                            type="button"
                            onClick={() => scrollCarousel(category.key, 'previous')}
                            className={`inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full border transition hover:-translate-y-0.5 ${
                                isLight ? 'border-slate-200 bg-white text-slate-800 hover:bg-slate-50' : 'border-white/10 bg-slate-900/60 text-white hover:bg-white/10'
                            }`}
                            aria-label={current.scrollPreviousLabel}
                        >
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="m15 18-6-6 6-6" /></svg>
                        </button>
                        <button
                            type="button"
                            onClick={() => scrollCarousel(category.key, 'next')}
                            className={`inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full border transition hover:-translate-y-0.5 ${
                                isLight ? 'border-slate-200 bg-white text-slate-800 hover:bg-slate-50' : 'border-white/10 bg-slate-900/60 text-white hover:bg-white/10'
                            }`}
                            aria-label={current.scrollNextLabel}
                        >
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div
                data-catalog-carousel={category.key}
                className="flex snap-x gap-5 overflow-x-auto scroll-smooth px-5 py-6 [scrollbar-width:none] sm:px-6 [&::-webkit-scrollbar]:hidden"
            >
                {category.templates.map((template) => (
                    <TemplateCard
                        key={template.id}
                        appName={appName}
                        current={current}
                        isLight={isLight}
                        template={template}
                    />
                ))}
            </div>
        </section>
    );
}

export default function PublicCatalogPage({
    appName,
    auth = {},
    locale = 'es',
    locales = [],
    navigation = [],
    shared = {},
    content = {},
    categories = [],
    templates = [],
    categoryCount = 0,
}) {
    const [theme, setTheme] = useState('dark');
    const current = useMemo(() => normalizeCatalogContent(content), [content]);
    const filters = useMemo(() => [{ key: 'all', name: current.allFilterLabel }, ...categories], [categories, current.allFilterLabel]);
    const [activeFilter, setActiveFilter] = useState('all');

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
        setActiveFilter('all');
    }, [locale, categories]);

    const filteredTemplates = useMemo(() => {
        if (activeFilter === 'all') {
            return templates;
        }

        return templates.filter((template) => template.categoryKey === activeFilter);
    }, [activeFilter, templates]);

    const categorySections = useMemo(() => {
        const grouped = categories
            .map((category) => ({
                ...category,
                templates: templates.filter((template) => template.categoryKey === category.key),
            }))
            .filter((category) => category.templates.length > 0);

        if (activeFilter === 'all') {
            return grouped;
        }

        return grouped.filter((category) => category.key === activeFilter);
    }, [activeFilter, categories, templates]);

    const featuredCount = useMemo(() => templates.filter((template) => template.isFeatured).length, [templates]);
    const isLight = theme === 'light';

    return (
        <PublicLayout
            appName={appName}
            auth={auth}
            footerCopy={current.footer}
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
                <div className="mx-auto max-w-7xl">
                    <div className="text-center">
                        <div
                            className={`mx-auto inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] ${
                                isLight ? 'border-sky-200 bg-white/80 text-sky-700' : 'border-white/10 bg-white/8 text-sky-200'
                            }`}
                        >
                            <span className={`h-2 w-2 rounded-full ${isLight ? 'bg-sky-500' : 'bg-sky-300'}`} />
                            {current.hero.eyebrow}
                        </div>

                        <h1
                            className={`mx-auto mt-8 max-w-5xl text-5xl font-semibold leading-tight tracking-tight text-balance sm:text-6xl lg:text-7xl ${
                                isLight ? 'text-slate-950' : 'text-white'
                            }`}
                        >
                            {current.hero.title}
                        </h1>

                        <p className={`mx-auto mt-5 max-w-3xl text-lg leading-8 sm:text-xl ${isLight ? 'text-slate-600' : 'text-slate-300'}`}>
                            {current.hero.subtitle}
                        </p>

                        <div className="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                            <a
                                href="#templates"
                                className={`rounded-2xl px-8 py-4 text-lg font-semibold transition ${
                                    isLight
                                        ? 'bg-indigo-600 text-white shadow-[0_22px_40px_rgba(79,70,229,0.25)] hover:bg-indigo-500'
                                        : 'bg-indigo-500 text-white shadow-[0_22px_40px_rgba(99,102,241,0.28)] hover:bg-indigo-400'
                                }`}
                            >
                                {current.hero.primaryAction}
                            </a>
                            <button
                                type="button"
                                onClick={() => setActiveFilter(categories[0]?.key ?? 'all')}
                                className={`rounded-2xl border px-8 py-4 text-lg font-semibold transition ${
                                    isLight
                                        ? 'border-slate-200 bg-white/80 text-slate-800 hover:bg-white'
                                        : 'border-white/10 bg-slate-800/70 text-white hover:bg-slate-800'
                                }`}
                            >
                                {current.hero.secondaryAction}
                            </button>
                        </div>
                    </div>

                    <div
                        className={`mt-12 overflow-hidden rounded-[2.2rem] border ${
                            isLight ? 'border-slate-200 bg-white/78 shadow-[0_24px_70px_rgba(148,163,184,0.18)]' : 'border-white/10 bg-white/6'
                        }`}
                    >
                        <div className="grid gap-0 lg:grid-cols-[0.8fr_1.2fr]">
                            <div className={`grid grid-cols-3 gap-px ${isLight ? 'bg-slate-200' : 'bg-white/10'}`}>
                                {[
                                    [filteredTemplates.length, current.visibleTemplatesLabel],
                                    [categoryCount, current.activeCategoriesLabel],
                                    [featuredCount, current.premiumLabel],
                                ].map(([value, label]) => (
                                    <div key={label} className={`p-4 text-center ${isLight ? 'bg-white/86' : 'bg-slate-950/22'}`}>
                                        <p className={`text-2xl font-semibold tabular-nums ${isLight ? 'text-slate-950' : 'text-white'}`}>{value}</p>
                                        <p className={`mt-1 text-[10px] font-semibold uppercase tracking-[0.18em] ${isLight ? 'text-slate-500' : 'text-white/50'}`}>{label}</p>
                                    </div>
                                ))}
                            </div>

                            <div className="flex flex-col gap-3 p-4 lg:flex-row lg:items-center lg:justify-between lg:p-5">
                                <div>
                                    <p className={`text-sm uppercase tracking-[0.26em] ${isLight ? 'text-slate-500' : 'text-white/55'}`}>
                                        {current.filtersLabel}
                                    </p>
                                    <p className={`mt-2 text-sm ${isLight ? 'text-slate-600' : 'text-white/60'}`}>
                                        {current.collectionHint}
                                    </p>
                                </div>

                                <div className="flex flex-wrap gap-2">
                                    {filters.map((filter) => (
                                        <button
                                            key={filter.key}
                                            type="button"
                                            onClick={() => setActiveFilter(filter.key)}
                                            className={`rounded-full px-4 py-2 text-sm font-semibold transition ${
                                                activeFilter === filter.key
                                                    ? isLight
                                                        ? 'bg-indigo-600 text-white shadow-[0_12px_24px_rgba(79,70,229,0.2)]'
                                                        : 'bg-white text-slate-950'
                                                    : isLight
                                                      ? 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                                                      : 'bg-white/6 text-white/70 hover:bg-white/10 hover:text-white'
                                            }`}
                                        >
                                            {filter.name}
                                        </button>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>

                    {categorySections.length === 0 ? (
                        <div className={`mt-8 rounded-[1.8rem] border px-6 py-10 text-center ${isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'}`}>
                            <h2 className={`text-2xl font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{current.emptyStateTitle}</h2>
                            <p className={`mx-auto mt-3 max-w-2xl text-base leading-7 ${isLight ? 'text-slate-600' : 'text-white/70'}`}>{current.emptyStateText}</p>
                        </div>
                    ) : null}

                    <div id="templates" className="mt-8 space-y-7">
                        {categorySections.map((category) => (
                            <CategoryCarousel
                                key={category.key}
                                appName={appName}
                                category={category}
                                current={current}
                                isLight={isLight}
                            />
                        ))}
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
