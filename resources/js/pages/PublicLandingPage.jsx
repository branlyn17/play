import { useEffect, useState } from 'react';

import FeatureGrid from '../components/public/FeatureGrid';
import HeroSection from '../components/public/HeroSection';
import PublicLayout from '../components/public/PublicLayout';
import { navigation } from '../data/publicSite';

const locales = [
    { code: 'es', label: 'ES', flag: '🇪🇸', name: 'Español' },
    { code: 'en', label: 'EN', flag: '🇺🇸', name: 'English' },
    { code: 'pt', label: 'PT', flag: '🇧🇷', name: 'Português' },
];

const sharedSlides = [
    {
        id: 'soft-blue',
        year: '2026',
        background:
            'linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02)), radial-gradient(circle at top left, rgba(125,211,252,0.55), transparent 30%), radial-gradient(circle at bottom right, rgba(99,102,241,0.35), transparent 26%), linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)',
    },
    {
        id: 'deep-indigo',
        year: '2026',
        background:
            'linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(125,211,252,0.26), transparent 30%), radial-gradient(circle at bottom left, rgba(244,114,182,0.2), transparent 24%), linear-gradient(135deg, #1e1b4b, #172554, #0f172a)',
    },
    {
        id: 'sky-glow',
        year: '2026',
        background:
            'linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02)), radial-gradient(circle at top, rgba(191,219,254,0.5), transparent 30%), radial-gradient(circle at bottom right, rgba(56,189,248,0.25), transparent 26%), linear-gradient(135deg, #e0f2fe, #dbeafe, #e9d5ff)',
    },
];

const content = {
    es: {
        header: {
            kicker: 'Public site',
            navItems: ['Como funciona', 'Catalogo', 'Precios', 'Login'],
            cta: 'Empezar gratis',
        },
        hero: {
            badge: 'Invita Plus',
            titleTop: 'Invitaciones que',
            titleAccent: 'todos amaran.',
            subtitle:
                'Disena, envia y gestiona invitaciones digitales para cualquier evento. Plantillas profesionales, RSVP y acceso global.',
            primaryAction: 'Ver Plantillas',
            secondaryAction: 'Ver Demo',
            bullets: ['Sin tarjeta de credito', 'Descarga instantanea'],
            sliderLabel: 'Vista previa',
            previewLabel: 'Demo',
        },
        slides: [
            {
                ...sharedSlides[0],
                category: 'Boda',
                title: 'Aura',
                caption: 'Una composicion suave en azul claro para una invitacion elegante.',
                label: 'Coleccion destacada',
                preview: 'Ceremonia - 07 PM',
                microcopy: 'Cambios automaticos de plantilla para mostrar estilos reales.',
            },
            {
                ...sharedSlides[1],
                category: 'XV',
                title: 'Nova',
                caption: 'Una escena oscura con brillo premium para eventos memorables.',
                label: 'Modo nocturno',
                preview: 'Recepcion - 08 PM',
                microcopy: 'La vista rota sola y luego podra conectarse a datos reales.',
            },
            {
                ...sharedSlides[2],
                category: 'Evento',
                title: 'Sky',
                caption: 'Una portada clara y moderna para propuestas mas frescas.',
                label: 'Edicion moderna',
                preview: 'RSVP - Activo',
                microcopy: 'La misma base puede adaptarse a varias colecciones publicas.',
            },
        ],
        cards: [
            { icon: 'L', title: 'Lista', text: 'Base limpia para crecer hacia una home real.' },
            { icon: 'R', title: 'Responsive', text: 'Pensada para desktop, tablet y movil.' },
            { icon: 'T', title: 'Tema', text: 'Claro u oscuro con persistencia al recargar.' },
        ],
        footer: {
            left: 'Landing publica base',
            right: 'Tema persistente, slider visual y estructura lista para seguir creciendo.',
        },
    },
    en: {
        header: {
            kicker: 'Public site',
            navItems: ['How it works', 'Catalog', 'Pricing', 'Login'],
            cta: 'Start free',
        },
        hero: {
            badge: 'Invita Plus',
            titleTop: 'Invitations that',
            titleAccent: 'everyone will love.',
            subtitle:
                'Design, send and manage digital invitations for any event. Professional templates, RSVP and global access.',
            primaryAction: 'View Templates',
            secondaryAction: 'Watch Demo',
            bullets: ['No credit card', 'Instant access'],
            sliderLabel: 'Preview',
            previewLabel: 'Demo',
        },
        slides: [
            {
                ...sharedSlides[0],
                category: 'Wedding',
                title: 'Aura',
                caption: 'A soft light-blue composition for an elegant invitation.',
                label: 'Featured collection',
                preview: 'Ceremony - 07 PM',
                microcopy: 'Templates rotate automatically to show multiple directions.',
            },
            {
                ...sharedSlides[1],
                category: 'Sweet 15',
                title: 'Nova',
                caption: 'A darker premium scene for memorable celebrations.',
                label: 'Night mode',
                preview: 'Reception - 08 PM',
                microcopy: 'This showcase can later connect to real invitation data.',
            },
            {
                ...sharedSlides[2],
                category: 'Event',
                title: 'Sky',
                caption: 'A brighter modern cover for fresher public pages.',
                label: 'Modern edit',
                preview: 'RSVP - Active',
                microcopy: 'One visual base that can scale into multiple collections.',
            },
        ],
        cards: [
            { icon: 'L', title: 'Launch-ready', text: 'A cleaner base for a stronger public home.' },
            { icon: 'R', title: 'Responsive', text: 'Built for desktop, tablet and mobile.' },
            { icon: 'T', title: 'Theme', text: 'Light and dark mode persisted across reloads.' },
        ],
        footer: {
            left: 'Public landing base',
            right: 'Persistent theme, visual slider and a stronger foundation for growth.',
        },
    },
    pt: {
        header: {
            kicker: 'Public site',
            navItems: ['Como funciona', 'Catalogo', 'Precos', 'Login'],
            cta: 'Comecar gratis',
        },
        hero: {
            badge: 'Invita Plus',
            titleTop: 'Convites que',
            titleAccent: 'todos vao amar.',
            subtitle:
                'Crie, envie e gerencie convites digitais para qualquer evento. Modelos profissionais, RSVP e acesso global.',
            primaryAction: 'Ver Modelos',
            secondaryAction: 'Ver Demo',
            bullets: ['Sem cartao de credito', 'Acesso imediato'],
            sliderLabel: 'Preview',
            previewLabel: 'Demo',
        },
        slides: [
            {
                ...sharedSlides[0],
                category: 'Casamento',
                title: 'Aura',
                caption: 'Uma composicao azul clara para um convite elegante.',
                label: 'Colecao destaque',
                preview: 'Cerimonia - 07 PM',
                microcopy: 'Os modelos trocam sozinhos para mostrar direcoes diferentes.',
            },
            {
                ...sharedSlides[1],
                category: '15 anos',
                title: 'Nova',
                caption: 'Uma cena mais escura e premium para celebracoes marcantes.',
                label: 'Modo noturno',
                preview: 'Recepcao - 08 PM',
                microcopy: 'Essa vitrine pode depois se conectar com dados reais.',
            },
            {
                ...sharedSlides[2],
                category: 'Evento',
                title: 'Sky',
                caption: 'Uma capa clara e moderna para paginas publicas mais leves.',
                label: 'Edicao moderna',
                preview: 'RSVP - Ativo',
                microcopy: 'A mesma base visual pode crescer para varias colecoes.',
            },
        ],
        cards: [
            { icon: 'L', title: 'Leve', text: 'Uma base mais limpa para a home publica.' },
            { icon: 'R', title: 'Responsiva', text: 'Pensada para desktop, tablet e celular.' },
            { icon: 'T', title: 'Tema', text: 'Modo claro ou escuro salvo ao recarregar.' },
        ],
        footer: {
            left: 'Landing publica base',
            right: 'Tema persistente, slider visual e uma base pronta para crescer.',
        },
    },
};

export default function PublicLandingPage({ appName, locale: routeLocale = 'es', locales: localeOptions = locales, navigation: navItems = [] }) {
    const [theme, setTheme] = useState('dark');
    const [activeIndex, setActiveIndex] = useState(0);

    const locale = routeLocale;
    const current = content[locale];

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
        setActiveIndex(0);
    }, [locale]);

    useEffect(() => {
        const interval = window.setInterval(() => {
            setActiveIndex((currentIndex) => (currentIndex + 1) % current.slides.length);
        }, 4200);

        return () => window.clearInterval(interval);
    }, [current.slides.length]);

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
            <HeroSection
                appName={appName}
                copy={current.hero}
                slides={current.slides}
                activeIndex={activeIndex}
                onSlideChange={setActiveIndex}
                theme={theme}
            />
            <FeatureGrid items={current.cards} theme={theme} />
        </PublicLayout>
    );
}
