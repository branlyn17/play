import { useEffect, useMemo, useState } from 'react';

import FeatureGrid from '../components/public/FeatureGrid';
import HeroSection from '../components/public/HeroSection';
import PublicLayout from '../components/public/PublicLayout';
import { createTranslator } from '../lib/i18n';

function normalizeHomeContent(content = {}, t) {
    const hero = content.hero ?? {};
    const slides = Array.isArray(content.slides) ? content.slides : [];
    const cards = Array.isArray(content.cards) ? content.cards : [];
    const slideIds = slides.length > 0 ? slides.map((slide, index) => slide.id ?? `slide_${index}`) : ['aura', 'nova', 'sky'];
    const cardIndexes = cards.length > 0 ? cards.map((_, index) => index) : [0, 1, 2];
    const heroBullets = Array.isArray(hero.bullets) && hero.bullets.length > 0 ? hero.bullets : ['', ''];

    return {
        header: {
            kicker: t('public.home.header.kicker', content.header?.kicker ?? ''),
            cta: t('public.home.header.cta', content.header?.cta ?? ''),
        },
        hero: {
            badge: t('public.home.hero.badge', hero.badge ?? ''),
            titleTop: t('public.home.hero.title_top', hero.title_top ?? ''),
            titleAccent: t('public.home.hero.title_accent', hero.title_accent ?? ''),
            subtitle: t('public.home.hero.subtitle', hero.subtitle ?? ''),
            primaryAction: t('public.home.hero.primary_action', hero.primary_action ?? ''),
            secondaryAction: t('public.home.hero.secondary_action', hero.secondary_action ?? ''),
            bullets: heroBullets.map((bullet, index) => t(`public.home.hero.bullets.${index}`, bullet ?? '')).filter(Boolean),
            sliderLabel: t('public.home.hero.slider_label', hero.slider_label ?? ''),
            previewLabel: t('public.home.hero.preview_label', hero.preview_label ?? ''),
        },
        slides: slideIds.map((id, index) => {
            const fallback = slides[index] ?? {};

            return {
                id,
                category: t(`public.home.slides.${id}.category`, fallback.category ?? ''),
                title: t(`public.home.slides.${id}.title`, fallback.title ?? ''),
                caption: t(`public.home.slides.${id}.caption`, fallback.caption ?? ''),
                label: t(`public.home.slides.${id}.label`, fallback.label ?? ''),
                preview: t(`public.home.slides.${id}.preview`, fallback.preview ?? ''),
                microcopy: t(`public.home.slides.${id}.microcopy`, fallback.microcopy ?? ''),
                year: t(`public.home.slides.${id}.year`, fallback.year ?? ''),
                background: t(`public.home.slides.${id}.background`, fallback.background ?? ''),
            };
        }),
        cards: cardIndexes.map((index) => {
            const fallback = cards[index] ?? {};

            return {
                icon: t(`public.home.cards.${index}.icon`, fallback.icon ?? ''),
                title: t(`public.home.cards.${index}.title`, fallback.title ?? ''),
                text: t(`public.home.cards.${index}.text`, fallback.text ?? ''),
            };
        }),
        footer: {
            left: t('public.home.footer.left', content.footer?.left ?? ''),
            right: t('public.home.footer.right', content.footer?.right ?? ''),
        },
        uiLabels: {
            theme_toggle: t('public.shared.header.theme_toggle', ''),
            language: t('public.shared.header.language', ''),
            menu: t('public.shared.header.menu', ''),
            close: t('public.shared.header.close', ''),
            dashboard: t('common.dashboard', ''),
            logout: t('actions.logout', ''),
        },
    };
}

export default function PublicLandingPage({
    appName,
    auth = {},
    i18n = {},
    locale = 'es',
    locales = [],
    navigation = [],
    shared = {},
    content = {},
    featuredSlides = [],
    catalogHref = '#',
}) {
    const [theme, setTheme] = useState('dark');
    const [activeIndex, setActiveIndex] = useState(0);
    const t = useMemo(() => createTranslator(i18n), [i18n]);
    const current = useMemo(() => normalizeHomeContent(content, t), [content, t]);
    const slides = featuredSlides.length > 0 ? featuredSlides : current.slides;
    const direction = i18n.direction ?? 'ltr';

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
    }, [locale, featuredSlides.length]);

    useEffect(() => {
        if (slides.length <= 1) {
            return undefined;
        }

        const interval = window.setInterval(() => {
            setActiveIndex((currentIndex) => (currentIndex + 1) % slides.length);
        }, 4200);

        return () => window.clearInterval(interval);
    }, [slides.length]);

    return (
        <PublicLayout
            appName={appName}
            auth={auth}
            direction={direction}
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
                uiLabels: {
                    ...(shared.header ?? {}),
                    ...current.uiLabels,
                },
            }}
        >
            <HeroSection
                appName={appName}
                copy={current.hero}
                slides={slides}
                activeIndex={activeIndex}
                onSlideChange={setActiveIndex}
                theme={theme}
                catalogHref={catalogHref}
            />
            <FeatureGrid items={current.cards} theme={theme} />
        </PublicLayout>
    );
}
