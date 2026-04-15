import { useEffect, useMemo, useState } from 'react';

import PublicLayout from '../components/public/PublicLayout';
import { locales as localeFallback, navigation } from '../data/publicSite';

const filtersByLocale = {
    es: ['Todos', 'Boda', 'XV', 'Eventos', 'Minimal', 'Premium'],
    en: ['All', 'Wedding', 'Sweet 15', 'Events', 'Minimal', 'Premium'],
    pt: ['Todos', 'Casamento', '15 anos', 'Eventos', 'Minimal', 'Premium'],
};

const catalogContent = {
    es: {
        header: {
            kicker: 'Public site',
            cta: 'Usar plantilla',
        },
        hero: {
            eyebrow: 'Catalogo visual',
            title: 'Explora plantillas listas para enamorar a primera vista.',
            subtitle:
                'Una galeria publica para descubrir estilos, filtrar colecciones y visualizar como crecera Invita Plus.',
            primaryAction: 'Ver destacadas',
            secondaryAction: 'Filtrar ahora',
        },
        footer: {
            left: 'Catalogo publico base',
            right: 'Plantillas, filtros y tarjetas listas para convertirse en un catalogo real.',
        },
        templates: [
            {
                id: 'aura',
                category: 'Boda',
                mood: 'Minimal',
                title: 'Aura',
                price: 'Premium',
                description: 'Luz suave, capas limpias y una presentacion elegante.',
                badge: 'Nueva',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.15), rgba(255,255,255,0.03)), radial-gradient(circle at top left, rgba(191,219,254,0.55), transparent 32%), radial-gradient(circle at bottom right, rgba(129,140,248,0.28), transparent 30%), linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)',
            },
            {
                id: 'luna',
                category: 'XV',
                mood: 'Premium',
                title: 'Luna',
                price: 'Premium',
                description: 'Brillo intenso, tono nocturno y acabado editorial.',
                badge: 'Top',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(96,165,250,0.24), transparent 28%), radial-gradient(circle at bottom left, rgba(244,114,182,0.18), transparent 24%), linear-gradient(135deg, #1e1b4b, #172554, #0f172a)',
            },
            {
                id: 'sky',
                category: 'Eventos',
                mood: 'Minimal',
                title: 'Sky',
                price: 'Base',
                description: 'Azul claro, aire fresco y una lectura muy limpia.',
                badge: 'Popular',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03)), radial-gradient(circle at top, rgba(186,230,253,0.5), transparent 30%), radial-gradient(circle at bottom right, rgba(125,211,252,0.22), transparent 24%), linear-gradient(135deg, #f0f9ff, #dbeafe, #e0e7ff)',
            },
            {
                id: 'nova',
                category: 'Boda',
                mood: 'Premium',
                title: 'Nova',
                price: 'Premium',
                description: 'Una portada cinematica con presencia fuerte.',
                badge: 'Featured',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02)), radial-gradient(circle at top left, rgba(125,211,252,0.25), transparent 28%), radial-gradient(circle at bottom right, rgba(99,102,241,0.24), transparent 26%), linear-gradient(135deg, #0f172a, #172554, #312e81)',
            },
            {
                id: 'brisa',
                category: 'Eventos',
                mood: 'Minimal',
                title: 'Brisa',
                price: 'Base',
                description: 'Visual aireado, claro y centrado en la tipografia.',
                badge: 'Light',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)), radial-gradient(circle at top left, rgba(224,242,254,0.8), transparent 34%), radial-gradient(circle at bottom right, rgba(147,197,253,0.26), transparent 26%), linear-gradient(135deg, #ffffff, #eff6ff, #dbeafe)',
            },
            {
                id: 'orion',
                category: 'XV',
                mood: 'Premium',
                title: 'Orion',
                price: 'Premium',
                description: 'Un estilo mas oscuro para una coleccion de alto impacto.',
                badge: 'Night',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(96,165,250,0.18), transparent 28%), radial-gradient(circle at bottom left, rgba(59,130,246,0.16), transparent 22%), linear-gradient(135deg, #020617, #0f172a, #1e293b)',
            },
        ],
    },
    en: {
        header: {
            kicker: 'Public site',
            cta: 'Use template',
        },
        hero: {
            eyebrow: 'Visual catalog',
            title: 'Explore templates ready to impress at first glance.',
            subtitle:
                'A public gallery to discover styles, filter collections and preview how Invita Plus can scale.',
            primaryAction: 'View featured',
            secondaryAction: 'Filter now',
        },
        footer: {
            left: 'Public catalog base',
            right: 'Templates, filters and cards ready to become a real public catalog.',
        },
        templates: [
            {
                id: 'aura',
                category: 'Wedding',
                mood: 'Minimal',
                title: 'Aura',
                price: 'Premium',
                description: 'Soft light, clean layers and an elegant presentation.',
                badge: 'New',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.15), rgba(255,255,255,0.03)), radial-gradient(circle at top left, rgba(191,219,254,0.55), transparent 32%), radial-gradient(circle at bottom right, rgba(129,140,248,0.28), transparent 30%), linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)',
            },
            {
                id: 'luna',
                category: 'Sweet 15',
                mood: 'Premium',
                title: 'Luna',
                price: 'Premium',
                description: 'Bold glow, night palette and editorial finish.',
                badge: 'Top',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(96,165,250,0.24), transparent 28%), radial-gradient(circle at bottom left, rgba(244,114,182,0.18), transparent 24%), linear-gradient(135deg, #1e1b4b, #172554, #0f172a)',
            },
            {
                id: 'sky',
                category: 'Events',
                mood: 'Minimal',
                title: 'Sky',
                price: 'Base',
                description: 'Light blue atmosphere and a clean reading experience.',
                badge: 'Popular',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03)), radial-gradient(circle at top, rgba(186,230,253,0.5), transparent 30%), radial-gradient(circle at bottom right, rgba(125,211,252,0.22), transparent 24%), linear-gradient(135deg, #f0f9ff, #dbeafe, #e0e7ff)',
            },
            {
                id: 'nova',
                category: 'Wedding',
                mood: 'Premium',
                title: 'Nova',
                price: 'Premium',
                description: 'A cinematic front page with stronger presence.',
                badge: 'Featured',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02)), radial-gradient(circle at top left, rgba(125,211,252,0.25), transparent 28%), radial-gradient(circle at bottom right, rgba(99,102,241,0.24), transparent 26%), linear-gradient(135deg, #0f172a, #172554, #312e81)',
            },
            {
                id: 'brisa',
                category: 'Events',
                mood: 'Minimal',
                title: 'Brisa',
                price: 'Base',
                description: 'Airy layout with more breathing room and strong typography.',
                badge: 'Light',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)), radial-gradient(circle at top left, rgba(224,242,254,0.8), transparent 34%), radial-gradient(circle at bottom right, rgba(147,197,253,0.26), transparent 26%), linear-gradient(135deg, #ffffff, #eff6ff, #dbeafe)',
            },
            {
                id: 'orion',
                category: 'Sweet 15',
                mood: 'Premium',
                title: 'Orion',
                price: 'Premium',
                description: 'A darker direction for high-impact premium collections.',
                badge: 'Night',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(96,165,250,0.18), transparent 28%), radial-gradient(circle at bottom left, rgba(59,130,246,0.16), transparent 22%), linear-gradient(135deg, #020617, #0f172a, #1e293b)',
            },
        ],
    },
    pt: {
        header: {
            kicker: 'Public site',
            cta: 'Usar modelo',
        },
        hero: {
            eyebrow: 'Catalogo visual',
            title: 'Explore modelos prontos para impressionar logo no primeiro olhar.',
            subtitle:
                'Uma galeria publica para descobrir estilos, filtrar colecoes e visualizar como Invita Plus pode crescer.',
            primaryAction: 'Ver destaques',
            secondaryAction: 'Filtrar agora',
        },
        footer: {
            left: 'Catalogo publico base',
            right: 'Modelos, filtros e cards prontos para virar um catalogo real.',
        },
        templates: [
            {
                id: 'aura',
                category: 'Casamento',
                mood: 'Minimal',
                title: 'Aura',
                price: 'Premium',
                description: 'Luz suave, camadas limpas e uma apresentacao elegante.',
                badge: 'Nova',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.15), rgba(255,255,255,0.03)), radial-gradient(circle at top left, rgba(191,219,254,0.55), transparent 32%), radial-gradient(circle at bottom right, rgba(129,140,248,0.28), transparent 30%), linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)',
            },
            {
                id: 'luna',
                category: '15 anos',
                mood: 'Premium',
                title: 'Luna',
                price: 'Premium',
                description: 'Brilho intenso, paleta noturna e acabamento editorial.',
                badge: 'Top',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(96,165,250,0.24), transparent 28%), radial-gradient(circle at bottom left, rgba(244,114,182,0.18), transparent 24%), linear-gradient(135deg, #1e1b4b, #172554, #0f172a)',
            },
            {
                id: 'sky',
                category: 'Eventos',
                mood: 'Minimal',
                title: 'Sky',
                price: 'Base',
                description: 'Atmosfera azul clara e leitura muito limpa.',
                badge: 'Popular',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03)), radial-gradient(circle at top, rgba(186,230,253,0.5), transparent 30%), radial-gradient(circle at bottom right, rgba(125,211,252,0.22), transparent 24%), linear-gradient(135deg, #f0f9ff, #dbeafe, #e0e7ff)',
            },
            {
                id: 'nova',
                category: 'Casamento',
                mood: 'Premium',
                title: 'Nova',
                price: 'Premium',
                description: 'Uma capa cinematografica com presenca mais forte.',
                badge: 'Featured',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02)), radial-gradient(circle at top left, rgba(125,211,252,0.25), transparent 28%), radial-gradient(circle at bottom right, rgba(99,102,241,0.24), transparent 26%), linear-gradient(135deg, #0f172a, #172554, #312e81)',
            },
            {
                id: 'brisa',
                category: 'Eventos',
                mood: 'Minimal',
                title: 'Brisa',
                price: 'Base',
                description: 'Visual mais arejado com tipografia em destaque.',
                badge: 'Light',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)), radial-gradient(circle at top left, rgba(224,242,254,0.8), transparent 34%), radial-gradient(circle at bottom right, rgba(147,197,253,0.26), transparent 26%), linear-gradient(135deg, #ffffff, #eff6ff, #dbeafe)',
            },
            {
                id: 'orion',
                category: '15 anos',
                mood: 'Premium',
                title: 'Orion',
                price: 'Premium',
                description: 'Uma direcao mais escura para colecoes premium de alto impacto.',
                badge: 'Night',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(96,165,250,0.18), transparent 28%), radial-gradient(circle at bottom left, rgba(59,130,246,0.16), transparent 22%), linear-gradient(135deg, #020617, #0f172a, #1e293b)',
            },
        ],
    },
};

export default function PublicCatalogPage({
    appName,
    locale: routeLocale = 'es',
    locales: localeOptions = localeFallback,
    navigation: navItems = [],
}) {
    const [theme, setTheme] = useState('dark');
    const [activeFilter, setActiveFilter] = useState(filtersByLocale.es[0]);

    const locale = routeLocale;
    const current = catalogContent[locale];
    const filters = filtersByLocale[locale];

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
        setActiveFilter(filtersByLocale[locale][0]);
    }, [locale]);

    const filteredTemplates = useMemo(() => {
        if (activeFilter === filters[0]) {
            return current.templates;
        }

        return current.templates.filter((template) => template.category === activeFilter || template.mood === activeFilter);
    }, [activeFilter, current.templates, filters]);

    const isLight = theme === 'light';

    return (
        <PublicLayout
            appName={appName}
            footerCopy={current.footer}
            theme={theme}
            headerProps={{
                navItems: navItems.length > 0 ? navItems : navigation[locale],
                locale,
                locales: localeOptions,
                onLocaleChange: (code) => {
                    const target = localeOptions.find((item) => item.code === code);

                    if (target?.href) {
                        window.location.href = target.href;
                    }
                },
                onThemeToggle: () => setTheme((value) => (value === 'dark' ? 'light' : 'dark')),
                labels: { kicker: current.header.kicker, cta: current.header.cta },
            }}
        >
            <section className="px-2 pt-4">
                <div className="mx-auto max-w-6xl">
                    <div className="text-center">
                        <div
                            className={`mx-auto inline-flex items-center gap-2 rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] ${
                                isLight ? 'border-sky-200 bg-white/80 text-sky-700' : 'border-white/10 bg-white/8 text-sky-200'
                            }`}
                        >
                            <span className={`h-2 w-2 rounded-full ${isLight ? 'bg-sky-500' : 'bg-sky-300'}`} />
                            {current.hero.eyebrow}
                        </div>

                        <h1 className={`mx-auto mt-8 max-w-4xl text-5xl font-semibold leading-tight tracking-tight text-balance sm:text-6xl lg:text-7xl ${isLight ? 'text-slate-950' : 'text-white'}`}>
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
                                onClick={() => setActiveFilter(filters[1] ?? filters[0])}
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

                    <div className={`mt-12 rounded-[2rem] border p-4 sm:p-5 ${isLight ? 'border-slate-200 bg-white/75' : 'border-white/10 bg-white/6'}`}>
                        <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p className={`text-sm uppercase tracking-[0.26em] ${isLight ? 'text-slate-500' : 'text-white/55'}`}>
                                    Filtros
                                </p>
                                <p className={`mt-2 text-lg font-semibold ${isLight ? 'text-slate-900' : 'text-white'}`}>
                                    {filteredTemplates.length} plantillas visibles
                                </p>
                            </div>

                            <div className="flex flex-wrap gap-2">
                                {filters.map((filter) => (
                                    <button
                                        key={filter}
                                        type="button"
                                        onClick={() => setActiveFilter(filter)}
                                        className={`rounded-full px-4 py-2 text-sm font-semibold transition ${
                                            activeFilter === filter
                                                ? isLight
                                                    ? 'bg-indigo-600 text-white shadow-[0_12px_24px_rgba(79,70,229,0.2)]'
                                                    : 'bg-white text-slate-950'
                                                : isLight
                                                  ? 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                                                  : 'bg-white/6 text-white/70 hover:bg-white/10 hover:text-white'
                                        }`}
                                    >
                                        {filter}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>

                    <div id="templates" className="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        {filteredTemplates.map((template) => (
                            <article
                                key={template.id}
                                className={`overflow-hidden rounded-[1.8rem] border transition hover:-translate-y-1 hover:shadow-[0_20px_50px_rgba(15,23,42,0.18)] ${
                                    isLight ? 'border-slate-200 bg-white/80' : 'border-white/10 bg-white/6'
                                }`}
                            >
                                <div className="h-64 p-4">
                                    <div
                                        className="relative h-full overflow-hidden rounded-[1.35rem] border border-white/20"
                                        style={{ background: template.background }}
                                    >
                                        <div className={`absolute inset-0 ${isLight ? 'bg-[linear-gradient(180deg,rgba(255,255,255,0.08),rgba(255,255,255,0.14))]' : 'bg-[linear-gradient(180deg,rgba(15,23,42,0.05),rgba(15,23,42,0.18))]'}`} />

                                        <div className="relative flex h-full flex-col justify-between p-5">
                                            <div className="flex items-center justify-between">
                                                <div className={`rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] ${isLight ? 'bg-white/80 text-slate-700' : 'bg-white/12 text-white/80'}`}>
                                                    {template.category}
                                                </div>
                                                <div className={`rounded-full px-3 py-1 text-[11px] uppercase tracking-[0.22em] ${isLight ? 'bg-slate-900/8 text-slate-600' : 'bg-black/15 text-white/60'}`}>
                                                    {template.price}
                                                </div>
                                            </div>

                                            <div>
                                                <p className={`text-sm uppercase tracking-[0.28em] ${isLight ? 'text-slate-600' : 'text-white/65'}`}>
                                                    {appName}
                                                </p>
                                                <h2 className={`mt-3 text-4xl font-semibold tracking-tight ${isLight ? 'text-slate-950' : 'text-white'}`}>
                                                    {template.title}
                                                </h2>
                                                <div className={`mt-4 inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] ${isLight ? 'bg-white/75 text-slate-700' : 'bg-white/10 text-white/78'}`}>
                                                    {template.badge}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div className="px-5 pb-5">
                                    <div className="flex items-center justify-between gap-3">
                                        <div>
                                            <p className={`text-xl font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{template.title}</p>
                                            <p className={`mt-2 text-sm ${isLight ? 'text-slate-500' : 'text-white/55'}`}>{template.mood}</p>
                                        </div>
                                        <a
                                            href="#"
                                            className={`rounded-full px-4 py-2 text-sm font-semibold transition ${
                                                isLight
                                                    ? 'bg-indigo-600 text-white hover:bg-indigo-500'
                                                    : 'bg-indigo-500 text-white hover:bg-indigo-400'
                                            }`}
                                        >
                                            Ver
                                        </a>
                                    </div>

                                    <p className={`mt-4 text-base leading-7 ${isLight ? 'text-slate-600' : 'text-white/68'}`}>
                                        {template.description}
                                    </p>
                                </div>
                            </article>
                        ))}
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
