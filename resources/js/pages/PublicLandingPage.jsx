import { useEffect, useMemo, useState } from 'react';

import FeatureGrid from '../components/public/FeatureGrid';
import HeroSection from '../components/public/HeroSection';
import PublicLayout from '../components/public/PublicLayout';

function normalizeHomeContent(content = {}) {
    const hero = content.hero ?? {};

    return {
        header: content.header ?? {},
        hero: {
            badge: hero.badge ?? '',
            titleTop: hero.title_top ?? '',
            titleAccent: hero.title_accent ?? '',
            subtitle: hero.subtitle ?? '',
            primaryAction: hero.primary_action ?? '',
            secondaryAction: hero.secondary_action ?? '',
            bullets: hero.bullets ?? [],
            sliderLabel: hero.slider_label ?? '',
            previewLabel: hero.preview_label ?? '',
        },
        slides: content.slides ?? [],
        cards: content.cards ?? [],
        footer: content.footer ?? {},
    };
}

export default function PublicLandingPage({
    appName,
    locale = 'es',
    locales = [],
    navigation = [],
    shared = {},
    content = {},
}) {
    const [theme, setTheme] = useState('dark');
    const [activeIndex, setActiveIndex] = useState(0);
    const current = useMemo(() => normalizeHomeContent(content), [content]);

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
        if (current.slides.length <= 1) {
            return undefined;
        }

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
