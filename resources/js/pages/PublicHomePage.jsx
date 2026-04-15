import { useEffect, useState } from 'react';

import FeatureGrid from '../components/public/FeatureGrid';
import HeroSection from '../components/public/HeroSection';
import PublicLayout from '../components/public/PublicLayout';

const locales = [
    { code: 'es', label: 'ES' },
    { code: 'en', label: 'EN' },
    { code: 'pt', label: 'PT' },
];

const content = {
    es: {
        header: {
            kicker: 'Public site',
            navItems: ['Invitaciones', 'Plantillas', 'Eventos', 'Colecciones'],
        },
        hero: {
            badge: 'Experiencia digital',
            title: 'Invitaciones que se sienten ligeras, modernas y memorables.',
            subtitle: 'Una portada visual para sorprender desde el primer segundo.',
            tags: ['Responsive', 'Slider visual', 'Modo multilenguaje'],
            primaryAction: 'Ver coleccion',
            secondaryAction: 'Cambiar idioma',
            sliderLabel: 'Demo en movimiento',
            previewLabel: 'Preview',
        },
        slides: [
            {
                id: 'aurora',
                category: 'Boda',
                year: '2026',
                title: 'Aura',
                caption: 'Cristal, luz suave y una escena delicada para un evento elegante.',
                label: 'Glass invitation',
                preview: 'Ceremonia · 07 PM',
                microcopy: 'Una portada que cambia sola para mostrar estilos distintos.',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.18), rgba(255,255,255,0.04)), radial-gradient(circle at top left, rgba(125,211,252,0.45), transparent 32%), radial-gradient(circle at bottom right, rgba(244,114,182,0.34), transparent 30%), linear-gradient(135deg, #0f172a, #111827, #1e1b4b)',
            },
            {
                id: 'vela',
                category: 'XV',
                year: '2026',
                title: 'Vela',
                caption: 'Brillo rosado, capas traslucidas y una sensacion editorial.',
                label: 'Editorial mood',
                preview: 'Recepcion · 08 PM',
                microcopy: 'Cada slide puede representar una plantilla o coleccion publica.',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.14), rgba(255,255,255,0.04)), radial-gradient(circle at top right, rgba(251,191,36,0.38), transparent 30%), radial-gradient(circle at left center, rgba(244,114,182,0.35), transparent 32%), linear-gradient(135deg, #1f2937, #111827, #3b0764)',
            },
            {
                id: 'noir',
                category: 'Evento',
                year: '2026',
                title: 'Noir',
                caption: 'Oscuro, pulido y visualmente fuerte para propuestas mas premium.',
                label: 'Night showcase',
                preview: 'Gallery · RSVP',
                microcopy: 'Ahora es solo una demo visual; despues vendran datos e interaccion real.',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03)), radial-gradient(circle at top, rgba(45,212,191,0.32), transparent 30%), radial-gradient(circle at bottom left, rgba(59,130,246,0.28), transparent 32%), linear-gradient(135deg, #020617, #0f172a, #1e293b)',
            },
        ],
        cards: [
            { icon: '◌', title: 'Ligera', text: 'Menos bloques, mas presencia visual.' },
            { icon: '✦', title: 'Transparente', text: 'Capas glass y fondos con profundidad.' },
            { icon: '↺', title: 'Viva', text: 'Animaciones y slider automatico de muestra.' },
        ],
        footer: {
            left: 'Landing publica base',
            right: 'Plantilla inicial para evolucionar hacia una experiencia real.',
        },
    },
    en: {
        header: {
            kicker: 'Public site',
            navItems: ['Invitations', 'Templates', 'Events', 'Collections'],
        },
        hero: {
            badge: 'Digital experience',
            title: 'Invitations that feel lighter, modern and memorable.',
            subtitle: 'A visual front page designed to surprise at first glance.',
            tags: ['Responsive', 'Visual slider', 'Multilanguage mode'],
            primaryAction: 'View collection',
            secondaryAction: 'Change language',
            sliderLabel: 'Live showcase',
            previewLabel: 'Preview',
        },
        slides: [
            {
                id: 'aurora',
                category: 'Wedding',
                year: '2026',
                title: 'Aura',
                caption: 'Crystal layers, soft light and an elegant ceremony mood.',
                label: 'Glass invitation',
                preview: 'Ceremony · 07 PM',
                microcopy: 'The hero rotates automatically to reveal multiple directions.',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.18), rgba(255,255,255,0.04)), radial-gradient(circle at top left, rgba(125,211,252,0.45), transparent 32%), radial-gradient(circle at bottom right, rgba(244,114,182,0.34), transparent 30%), linear-gradient(135deg, #0f172a, #111827, #1e1b4b)',
            },
            {
                id: 'vela',
                category: 'Sweet 15',
                year: '2026',
                title: 'Vela',
                caption: 'A pink editorial scene with a soft and layered composition.',
                label: 'Editorial mood',
                preview: 'Reception · 08 PM',
                microcopy: 'Each slide can later represent a real collection or template.',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.14), rgba(255,255,255,0.04)), radial-gradient(circle at top right, rgba(251,191,36,0.38), transparent 30%), radial-gradient(circle at left center, rgba(244,114,182,0.35), transparent 32%), linear-gradient(135deg, #1f2937, #111827, #3b0764)',
            },
            {
                id: 'noir',
                category: 'Event',
                year: '2026',
                title: 'Noir',
                caption: 'Dark, polished and premium for stronger public presentations.',
                label: 'Night showcase',
                preview: 'Gallery · RSVP',
                microcopy: 'This is still a visual template, ready for future interaction.',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03)), radial-gradient(circle at top, rgba(45,212,191,0.32), transparent 30%), radial-gradient(circle at bottom left, rgba(59,130,246,0.28), transparent 32%), linear-gradient(135deg, #020617, #0f172a, #1e293b)',
            },
        ],
        cards: [
            { icon: '◌', title: 'Light', text: 'Less text, stronger visual direction.' },
            { icon: '✦', title: 'Transparent', text: 'Glass layers and atmospheric depth.' },
            { icon: '↺', title: 'Alive', text: 'Animated showcase with timed transitions.' },
        ],
        footer: {
            left: 'Public landing base',
            right: 'A first template ready to evolve into a real experience.',
        },
    },
    pt: {
        header: {
            kicker: 'Public site',
            navItems: ['Convites', 'Modelos', 'Eventos', 'Colecoes'],
        },
        hero: {
            badge: 'Experiencia digital',
            title: 'Convites com uma presenca mais leve, moderna e memoravel.',
            subtitle: 'Uma capa visual feita para surpreender logo no primeiro olhar.',
            tags: ['Responsivo', 'Slider visual', 'Modo multilingue'],
            primaryAction: 'Ver colecao',
            secondaryAction: 'Mudar idioma',
            sliderLabel: 'Mostra em movimento',
            previewLabel: 'Preview',
        },
        slides: [
            {
                id: 'aurora',
                category: 'Casamento',
                year: '2026',
                title: 'Aura',
                caption: 'Camadas de cristal, luz suave e uma atmosfera elegante.',
                label: 'Glass invitation',
                preview: 'Cerimonia · 07 PM',
                microcopy: 'O slider gira sozinho para mostrar direcoes visuais diferentes.',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.18), rgba(255,255,255,0.04)), radial-gradient(circle at top left, rgba(125,211,252,0.45), transparent 32%), radial-gradient(circle at bottom right, rgba(244,114,182,0.34), transparent 30%), linear-gradient(135deg, #0f172a, #111827, #1e1b4b)',
            },
            {
                id: 'vela',
                category: '15 anos',
                year: '2026',
                title: 'Vela',
                caption: 'Brilho rosado e composicao editorial para uma pagina marcante.',
                label: 'Editorial mood',
                preview: 'Recepcao · 08 PM',
                microcopy: 'Cada slide pode virar depois um modelo real ou uma colecao.',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.14), rgba(255,255,255,0.04)), radial-gradient(circle at top right, rgba(251,191,36,0.38), transparent 30%), radial-gradient(circle at left center, rgba(244,114,182,0.35), transparent 32%), linear-gradient(135deg, #1f2937, #111827, #3b0764)',
            },
            {
                id: 'noir',
                category: 'Evento',
                year: '2026',
                title: 'Noir',
                caption: 'Escuro, polido e premium para apresentacoes publicas mais fortes.',
                label: 'Night showcase',
                preview: 'Gallery · RSVP',
                microcopy: 'Por enquanto e uma demo visual pronta para crescer depois.',
                background:
                    'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03)), radial-gradient(circle at top, rgba(45,212,191,0.32), transparent 30%), radial-gradient(circle at bottom left, rgba(59,130,246,0.28), transparent 32%), linear-gradient(135deg, #020617, #0f172a, #1e293b)',
            },
        ],
        cards: [
            { icon: '◌', title: 'Leve', text: 'Pouco texto e mais presenca visual.' },
            { icon: '✦', title: 'Transparente', text: 'Camadas glass com mais profundidade.' },
            { icon: '↺', title: 'Viva', text: 'Animacoes e troca automatica de cenas.' },
        ],
        footer: {
            left: 'Landing publica base',
            right: 'Primeiro modelo pronto para evoluir com dados reais.',
        },
    },
};

export default function PublicHomePage({ appName }) {
    const [locale, setLocale] = useState('es');
    const [theme, setTheme] = useState('dark');
    const [activeIndex, setActiveIndex] = useState(0);

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
                navItems: current.header.navItems,
                locale,
                locales,
                onLocaleChange: setLocale,
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
            <FeatureGrid items={current.cards} />
        </PublicLayout>
    );
}
