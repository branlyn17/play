import { useEffect, useMemo, useRef, useState } from 'react';

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

function submitLogout(logoutUrl) {
    if (!logoutUrl) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = logoutUrl;

    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = getCsrfToken();

    const redirect = document.createElement('input');
    redirect.type = 'hidden';
    redirect.name = 'redirect_to';
    redirect.value = `${window.location.pathname}${window.location.search}`;

    form.appendChild(token);
    form.appendChild(redirect);
    document.body.appendChild(form);
    form.submit();
}

function ThemeSunIcon() {
    return (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" className="h-4 w-4">
            <circle cx="12" cy="12" r="4" />
            <path d="M12 2v2" />
            <path d="M12 20v2" />
            <path d="m4.93 4.93 1.41 1.41" />
            <path d="m17.66 17.66 1.41 1.41" />
            <path d="M2 12h2" />
            <path d="M20 12h2" />
            <path d="m6.34 17.66-1.41 1.41" />
            <path d="m19.07 4.93-1.41 1.41" />
        </svg>
    );
}

function ThemeMoonIcon() {
    return (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" className="h-4 w-4">
            <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79Z" />
        </svg>
    );
}

export default function Header({ appName, auth = {}, navItems, locale, locales, onLocaleChange, labels, uiLabels = {}, theme, onThemeToggle }) {
    const isLight = theme === 'light';
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [isLocaleOpen, setIsLocaleOpen] = useState(false);
    const localeRef = useRef(null);
    const activeLocale = useMemo(() => locales.find((item) => item.code === locale) ?? locales[0], [locale, locales]);
    const headerUi = {
        themeToggle: uiLabels.theme_toggle ?? 'Toggle theme',
        language: uiLabels.language ?? 'Language',
        menu: uiLabels.menu ?? 'Menu',
        close: uiLabels.close ?? 'Close',
    };
    const userLabel = auth.displayName ?? '';
    const isAuthenticated = Boolean(auth.authenticated);
    const isSuperadmin = auth.primaryRole === 'superadmin';

    useEffect(() => {
        function handleClickOutside(event) {
            if (localeRef.current && !localeRef.current.contains(event.target)) {
                setIsLocaleOpen(false);
            }
        }

        document.addEventListener('mousedown', handleClickOutside);

        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    useEffect(() => {
        setIsMenuOpen(false);
    }, [locale, theme, isAuthenticated]);

    return (
        <header
            className={`sticky top-0 z-20 mb-6 rounded-[1.8rem] border px-5 py-4 backdrop-blur-2xl transition-colors duration-500 ${
                isLight
                    ? 'border-slate-200/80 bg-white/70 shadow-[0_12px_40px_rgba(148,163,184,0.15)]'
                    : 'border-white/10 bg-white/6'
            }`}
        >
            <div className="flex items-center justify-between gap-4">
                <div className="flex items-center gap-3">
                    <div
                        className={`pulse-glow flex h-11 w-11 items-center justify-center rounded-2xl text-sm font-black tracking-[0.2em] ${
                            isLight
                                ? 'bg-indigo-600 text-white shadow-[0_12px_24px_rgba(79,70,229,0.28)]'
                                : 'bg-white/85 text-slate-950 shadow-[0_8px_30px_rgba(255,255,255,0.2)]'
                        }`}
                    >
                        IP
                    </div>
                    <div>
                        <p className={`text-sm uppercase tracking-[0.3em] ${isLight ? 'text-slate-500' : 'text-white/45'}`}>{labels.kicker}</p>
                        <p className={`text-lg font-semibold ${isLight ? 'text-slate-950' : 'text-white'}`}>{appName}</p>
                    </div>
                </div>

                <div className="hidden xl:flex xl:items-center xl:gap-4">
                    <nav className={`flex flex-wrap items-center gap-2 rounded-full px-2 py-1 ${isLight ? 'bg-slate-100/80' : 'bg-white/4'}`}>
                        {navItems.map((item) => (
                            <a
                                key={typeof item === 'string' ? item : item.label}
                                href={typeof item === 'string' ? '#' : item.href}
                                className={`rounded-full border border-transparent px-4 py-2 text-sm font-medium transition ${
                                    typeof item !== 'string' && item.active
                                        ? isLight
                                            ? 'bg-white text-slate-950 shadow-[0_10px_22px_rgba(148,163,184,0.18)]'
                                            : 'border-white/10 bg-white/10 text-white'
                                        : isLight
                                          ? 'text-slate-600 hover:bg-white hover:text-slate-950'
                                          : 'text-white/70 hover:border-white/10 hover:bg-white/6 hover:text-white'
                                }`}
                            >
                                {typeof item === 'string' ? item : item.label}
                            </a>
                        ))}
                    </nav>

                    <div className="flex items-center gap-3">
                        <button
                            type="button"
                            onClick={onThemeToggle}
                            className={`inline-flex h-11 w-11 items-center justify-center rounded-full border transition ${
                                isLight
                                    ? 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                    : 'border-white/10 bg-white/6 text-white/80 hover:bg-white/10'
                            }`}
                            aria-label={headerUi.themeToggle}
                        >
                            {isLight ? <ThemeMoonIcon /> : <ThemeSunIcon />}
                        </button>

                        <div className="relative" ref={localeRef}>
                            <button
                                type="button"
                                onClick={() => setIsLocaleOpen((value) => !value)}
                                className={`inline-flex items-center gap-3 rounded-full border px-4 py-2.5 text-sm font-semibold transition ${
                                    isLight
                                        ? 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                        : 'border-white/10 bg-white/6 text-white/85 hover:bg-white/10'
                                }`}
                            >
                                <span className="text-base">{activeLocale?.flag}</span>
                                <span>{activeLocale?.label}</span>
                                <span className={`${isLocaleOpen ? 'rotate-180' : ''} text-xs transition-transform`}>v</span>
                            </button>

                            {isLocaleOpen ? (
                                <div
                                    className={`absolute right-0 top-[calc(100%+0.6rem)] min-w-48 rounded-2xl border p-2 shadow-[0_18px_40px_rgba(15,23,42,0.14)] ${
                                        isLight ? 'border-slate-200 bg-white' : 'border-white/10 bg-slate-900/95'
                                    }`}
                                >
                                    {locales.map((item) => (
                                        <button
                                            key={item.code}
                                            type="button"
                                            onClick={() => {
                                                onLocaleChange(item.code);
                                                setIsLocaleOpen(false);
                                            }}
                                            className={`flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left transition ${
                                                locale === item.code
                                                    ? isLight
                                                        ? 'bg-indigo-50 text-indigo-700'
                                                        : 'bg-white/10 text-white'
                                                    : isLight
                                                      ? 'text-slate-700 hover:bg-slate-50'
                                                      : 'text-white/75 hover:bg-white/6 hover:text-white'
                                            }`}
                                        >
                                            <span className="text-base">{item.flag}</span>
                                            <span className="text-sm font-medium">{item.name}</span>
                                            <span className="ml-auto text-xs uppercase tracking-[0.2em] opacity-60">{item.label}</span>
                                        </button>
                                    ))}
                                </div>
                            ) : null}
                        </div>

                        {isAuthenticated ? (
                            <div className="flex items-center gap-3">
                                <div className={`rounded-full border px-4 py-2.5 text-sm font-semibold ${isLight ? 'border-slate-200 bg-white text-slate-700' : 'border-white/10 bg-white/6 text-white/90'}`}>
                                    {userLabel}
                                </div>

                                {isSuperadmin && auth.dashboardUrl ? (
                                    <a
                                        href={auth.dashboardUrl}
                                        className={`rounded-full px-5 py-3 text-sm font-semibold transition ${
                                            isLight
                                                ? 'bg-slate-900 text-white hover:bg-slate-800'
                                                : 'bg-white text-slate-950 hover:bg-slate-100'
                                        }`}
                                    >
                                        Panel
                                    </a>
                                ) : null}

                                <button
                                    type="button"
                                    onClick={() => submitLogout(auth.logoutUrl)}
                                    className={`rounded-full px-5 py-3 text-sm font-semibold transition ${
                                        isLight
                                            ? 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                            : 'bg-white/8 text-white hover:bg-white/12'
                                    }`}
                                >
                                    Salir
                                </button>
                            </div>
                        ) : (
                            <a
                                href={auth.loginUrl ?? '#'}
                                className={`rounded-full px-5 py-3 text-sm font-semibold transition ${
                                    isLight
                                        ? 'bg-indigo-600 text-white shadow-[0_16px_30px_rgba(79,70,229,0.22)] hover:bg-indigo-500'
                                        : 'bg-indigo-500 text-white shadow-[0_16px_30px_rgba(99,102,241,0.22)] hover:bg-indigo-400'
                                }`}
                            >
                                {labels.cta}
                            </a>
                        )}
                    </div>
                </div>

                <div className="flex items-center gap-2 xl:hidden">
                    <button
                        type="button"
                        onClick={onThemeToggle}
                        className={`inline-flex h-11 w-11 items-center justify-center rounded-full border transition ${
                            isLight
                                ? 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                : 'border-white/10 bg-white/6 text-white/80 hover:bg-white/10'
                        }`}
                        aria-label={headerUi.themeToggle}
                    >
                        {isLight ? <ThemeMoonIcon /> : <ThemeSunIcon />}
                    </button>

                    <button
                        type="button"
                        onClick={() => setIsMenuOpen((value) => !value)}
                        className={`inline-flex h-11 items-center justify-center rounded-full border px-4 text-sm font-semibold transition ${
                            isLight
                                ? 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                : 'border-white/10 bg-white/6 text-white hover:bg-white/10'
                        }`}
                        aria-label={headerUi.menu}
                    >
                        {isMenuOpen ? headerUi.close : headerUi.menu}
                    </button>
                </div>
            </div>

            {isMenuOpen ? (
                <div className={`mt-4 space-y-4 rounded-[1.5rem] border p-4 xl:hidden ${isLight ? 'border-slate-200 bg-white/75' : 'border-white/10 bg-white/6'}`}>
                    <nav className="grid gap-2">
                        {navItems.map((item) => (
                            <a
                                key={typeof item === 'string' ? item : item.label}
                                href={typeof item === 'string' ? '#' : item.href}
                                className={`rounded-2xl px-4 py-3 text-sm font-medium transition ${
                                    typeof item !== 'string' && item.active
                                        ? isLight
                                            ? 'bg-indigo-600 text-white'
                                            : 'bg-white text-slate-950'
                                        : isLight
                                          ? 'bg-slate-50 text-slate-700 hover:bg-slate-100'
                                          : 'bg-white/6 text-white/80 hover:bg-white/10 hover:text-white'
                                }`}
                            >
                                {typeof item === 'string' ? item : item.label}
                            </a>
                        ))}
                    </nav>

                    <div className="space-y-2">
                        <p className={`px-1 text-xs font-semibold uppercase tracking-[0.24em] ${isLight ? 'text-slate-500' : 'text-white/45'}`}>
                            {headerUi.language}
                        </p>
                        <div className="grid gap-2">
                            {locales.map((item) => (
                                <button
                                    key={item.code}
                                    type="button"
                                    onClick={() => {
                                        onLocaleChange(item.code);
                                        setIsMenuOpen(false);
                                    }}
                                    className={`flex items-center gap-3 rounded-2xl px-4 py-3 text-left transition ${
                                        locale === item.code
                                            ? isLight
                                                ? 'bg-indigo-600 text-white'
                                                : 'bg-white text-slate-950'
                                            : isLight
                                              ? 'bg-slate-50 text-slate-700 hover:bg-slate-100'
                                              : 'bg-white/6 text-white/80 hover:bg-white/10'
                                    }`}
                                >
                                    <span className="text-base">{item.flag}</span>
                                    <span className="font-medium">{item.name}</span>
                                    <span className="ml-auto text-xs uppercase tracking-[0.2em] opacity-60">{item.label}</span>
                                </button>
                            ))}
                        </div>
                    </div>

                    {isAuthenticated ? (
                        <div className="space-y-2">
                            <div className={`rounded-2xl px-4 py-3 text-sm font-semibold ${isLight ? 'bg-slate-50 text-slate-700' : 'bg-white/6 text-white'}`}>
                                {userLabel}
                            </div>

                            {isSuperadmin && auth.dashboardUrl ? (
                                <a
                                    href={auth.dashboardUrl}
                                    className={`block rounded-2xl px-5 py-3 text-center text-sm font-semibold transition ${
                                        isLight
                                            ? 'bg-slate-900 text-white hover:bg-slate-800'
                                            : 'bg-white text-slate-950 hover:bg-slate-100'
                                    }`}
                                >
                                    Panel
                                </a>
                            ) : null}

                            <button
                                type="button"
                                onClick={() => submitLogout(auth.logoutUrl)}
                                className={`block w-full rounded-2xl px-5 py-3 text-center text-sm font-semibold transition ${
                                    isLight
                                        ? 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                        : 'bg-white/8 text-white hover:bg-white/12'
                                }`}
                            >
                                Salir
                            </button>
                        </div>
                    ) : (
                        <a
                            href={auth.loginUrl ?? '#'}
                            className={`block rounded-2xl px-5 py-3 text-center text-sm font-semibold transition ${
                                isLight
                                    ? 'bg-indigo-600 text-white shadow-[0_16px_30px_rgba(79,70,229,0.22)] hover:bg-indigo-500'
                                    : 'bg-indigo-500 text-white shadow-[0_16px_30px_rgba(99,102,241,0.22)] hover:bg-indigo-400'
                            }`}
                        >
                            {labels.cta}
                        </a>
                    )}
                </div>
            ) : null}
        </header>
    );
}
