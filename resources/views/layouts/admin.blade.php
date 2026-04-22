<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-admin-theme="dark" data-admin-sidebar="open" data-admin-fullscreen="off">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#4f7cff">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="InvitaAdmin">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <title>{{ $title ?? trans('admin.title') }}</title>
        <link rel="manifest" href="/manifest-admin.json">
        <link rel="apple-touch-icon" href="/admin-pwa/apple-touch-icon.png">

        <style>
            :root {
                --admin-bg: #081126;
                --admin-overlay: radial-gradient(circle at top left, rgba(71, 121, 255, 0.15), transparent 34%), linear-gradient(180deg, #091226 0%, #0b1429 100%);
                --admin-shell: rgba(13, 22, 41, 0.92);
                --admin-sidebar: rgba(12, 19, 36, 0.96);
                --admin-surface: rgba(255, 255, 255, 0.05);
                --admin-surface-strong: rgba(255, 255, 255, 0.08);
                --admin-surface-soft: rgba(255, 255, 255, 0.025);
                --admin-border: rgba(255, 255, 255, 0.08);
                --admin-text: #f8fafc;
                --admin-text-soft: rgba(226, 232, 240, 0.8);
                --admin-muted: rgba(148, 163, 184, 0.76);
                --admin-primary: #4f7cff;
                --admin-primary-soft: rgba(79, 124, 255, 0.16);
                --admin-positive: #4ade80;
                --admin-negative: #fb7185;
                --admin-shadow: 0 32px 90px rgba(2, 6, 23, 0.34);
                --admin-sidebar-width: 292px;
            }

            html[data-admin-theme='light'] {
                --admin-bg: #edf5ff;
                --admin-overlay: radial-gradient(circle at top left, rgba(79, 124, 255, 0.13), transparent 38%), linear-gradient(180deg, #f8fbff 0%, #edf4ff 100%);
                --admin-shell: rgba(255, 255, 255, 0.94);
                --admin-sidebar: rgba(255, 255, 255, 0.96);
                --admin-surface: rgba(15, 23, 42, 0.04);
                --admin-surface-strong: rgba(255, 255, 255, 0.86);
                --admin-surface-soft: rgba(79, 124, 255, 0.04);
                --admin-border: rgba(148, 163, 184, 0.22);
                --admin-text: #111827;
                --admin-text-soft: #4b5563;
                --admin-muted: #64748b;
                --admin-primary: #4f46e5;
                --admin-primary-soft: rgba(79, 70, 229, 0.12);
                --admin-positive: #16a34a;
                --admin-negative: #e11d48;
                --admin-shadow: 0 28px 80px rgba(148, 163, 184, 0.2);
            }

            [data-admin-shell] {
                transition: background-color 220ms ease, border-color 220ms ease, box-shadow 220ms ease;
            }

            [data-admin-submenu] {
                overflow: hidden;
                transition: max-height 240ms ease, opacity 220ms ease, margin-top 220ms ease;
            }

            [data-admin-scroll]::-webkit-scrollbar {
                width: 8px;
            }

            [data-admin-scroll]::-webkit-scrollbar-thumb {
                border-radius: 999px;
                background: rgba(148, 163, 184, 0.26);
            }

            #admin-sidebar {
                transform: translateX(-110%);
                translate: 0 0;
                opacity: 0;
                pointer-events: none;
                transition: transform 240ms ease, opacity 220ms ease;
            }

            @media (min-width: 1024px) {
                #admin-shell {
                    display: grid;
                    grid-template-columns: var(--admin-sidebar-width) minmax(0, 1fr);
                    gap: 1.5rem;
                    align-items: start;
                    transition: grid-template-columns 240ms ease;
                }

                html[data-admin-sidebar='closed'] #admin-shell {
                    grid-template-columns: 0 minmax(0, 1fr);
                    gap: 0;
                }

                #admin-sidebar {
                    position: sticky;
                    top: 1rem;
                    right: auto;
                    bottom: auto;
                    left: auto;
                    width: var(--admin-sidebar-width);
                    justify-self: start;
                    height: calc(100vh - 2rem);
                    transform: translateX(0);
                    translate: 0 0;
                    opacity: 1;
                    pointer-events: auto;
                }

                html[data-admin-sidebar='closed'] #admin-sidebar {
                    transform: translateX(calc(-100% - 24px));
                    translate: 0 0;
                    opacity: 0;
                    pointer-events: none;
                }
            }

            html[data-admin-fullscreen='on'] #admin-page-frame {
                max-width: none;
            }

            html[data-admin-fullscreen='on'] #admin-page-frame {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            @media (min-width: 640px) {
                html[data-admin-fullscreen='on'] #admin-page-frame {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen overflow-x-hidden bg-[color:var(--admin-bg)] text-[color:var(--admin-text)] antialiased">
        @php
            $supportedLocales = config('locales.supported', []);
            $localeQuery = request()->query();
            $sidebarSections = [
                ['key' => 'overview', 'icon' => 'grid', 'items' => ['dashboard'], 'expanded' => true],
                ['key' => 'catalog', 'icon' => 'cube', 'items' => ['templates', 'catalog', 'template_analytics', 'categories', 'plans'], 'expanded' => true],
                ['key' => 'sales', 'icon' => 'cart', 'items' => ['orders', 'revenue', 'billing'], 'expanded' => false],
                ['key' => 'operations', 'icon' => 'users', 'items' => ['customers', 'invitations', 'support'], 'expanded' => false],
                ['key' => 'growth', 'icon' => 'chart', 'items' => ['analytics', 'translations'], 'expanded' => false],
                ['key' => 'system', 'icon' => 'settings', 'items' => ['settings'], 'expanded' => false],
            ];
            $localeLinks = collect($supportedLocales)->map(function ($meta, $code) use ($localeQuery) {
                return [
                    'code' => $code,
                    'label' => $meta['label'],
                    'name' => $meta['name'],
                    'flag' => $meta['flag'],
                    'href' => request()->url().'?'.http_build_query(array_merge($localeQuery, ['lang' => $code])),
                ];
            })->values()->all();
            $adminRouteQuery = request()->has('lang') ? ['lang' => request()->query('lang')] : [];
            $sidebarItemRoutes = [
                'dashboard' => route('admin.dashboard', $adminRouteQuery),
                'templates' => route('admin.templates.index', $adminRouteQuery),
                'catalog' => route('admin.template-categories.index', $adminRouteQuery),
                'template_analytics' => route('admin.template-analytics.index', $adminRouteQuery),
            ];
            $activeSidebarItem = request()->routeIs('admin.templates.*')
                ? 'templates'
                : (request()->routeIs('admin.template-categories.*')
                    ? 'catalog'
                    : (request()->routeIs('admin.template-analytics.*') ? 'template_analytics' : 'dashboard'));
        @endphp

        <script>
            (function () {
                const savedTheme = window.localStorage.getItem('invita-plus-admin-theme');
                const savedSidebar = window.localStorage.getItem('invita-plus-admin-sidebar');
                const savedFullscreen = window.localStorage.getItem('invita-plus-admin-fullscreen');
                document.documentElement.dataset.adminTheme = savedTheme === 'light' || savedTheme === 'dark' ? savedTheme : 'dark';
                document.documentElement.dataset.adminSidebar = savedSidebar === 'closed' ? 'closed' : 'open';
                document.documentElement.dataset.adminFullscreen = savedFullscreen === 'on' ? 'on' : 'off';
            }());
        </script>

        <div class="min-h-screen bg-[var(--admin-overlay)]">
            <div id="admin-page-frame" class="mx-auto max-w-[1800px] px-4 py-4 sm:px-6 lg:px-8">
                <div id="admin-shell" class="relative">
                    <div id="admin-sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-slate-950/60 backdrop-blur-sm lg:hidden"></div>

                    <aside id="admin-sidebar" data-admin-shell class="fixed inset-y-4 left-4 z-40 flex w-[min(var(--admin-sidebar-width),calc(100vw-2rem))] flex-col rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-sidebar)] shadow-[var(--admin-shadow)] backdrop-blur-2xl lg:w-[var(--admin-sidebar-width)]">
                        <div class="flex items-center justify-between border-b border-[color:var(--admin-border)] px-4 py-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[color:var(--admin-primary)] text-base font-black text-white shadow-[0_18px_32px_rgba(79,124,255,0.3)]">T</div>
                                <p class="truncate text-[1.55rem] font-semibold tracking-tight text-[color:var(--admin-text)]">TailPanel</p>
                            </div>
                            <button id="admin-sidebar-close" type="button" class="inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-2xl bg-[color:var(--admin-primary-soft)] text-[color:var(--admin-primary)] transition hover:scale-[1.02]" aria-label="{{ trans('admin.sidebar.toggle') }}">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto px-3 py-3" data-admin-scroll>
                            <nav class="space-y-2">
                                @foreach ($sidebarSections as $section)
                                    @php($sectionIsActive = in_array($activeSidebarItem, $section['items'], true))
                                    <section class="rounded-[1.4rem] p-1">
                                        <button type="button" data-admin-accordion-trigger data-target="submenu-{{ $section['key'] }}" data-expanded="{{ $sectionIsActive || $section['expanded'] ? 'true' : 'false' }}" class="flex w-full items-center gap-3 rounded-[1.2rem] px-3 py-2.5 text-left transition {{ $sectionIsActive ? 'border border-[color:var(--admin-primary)]/60 bg-[color:var(--admin-primary-soft)] text-[color:var(--admin-primary)]' : 'hover:bg-[color:var(--admin-surface)]' }}">
                                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl {{ $sectionIsActive ? 'bg-[color:var(--admin-primary)] text-white' : 'text-[color:var(--admin-text-soft)]' }}">
                                                @switch($section['icon'])
                                                    @case('grid')
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                                        @break
                                                    @case('cube')
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m21 16-9 5-9-5"></path><path d="m21 12-9 5-9-5 9-5 9 5Z"></path><path d="m12 7 9-5 9 5-9 5-9-5Z" transform="translate(-9 0)"></path></svg>
                                                        @break
                                                    @case('cart')
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.6 12.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                                        @break
                                                    @case('users')
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                                        @break
                                                    @case('chart')
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg>
                                                        @break
                                                    @default
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09A1.65 1.65 0 0 0 10.91 3H11a2 2 0 1 1 4 0h.09a1.65 1.65 0 0 0 1.51 1c.3.05.61-.01.87-.17.26-.16.47-.38.62-.65l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09A1.65 1.65 0 0 0 21 10.91V11a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"></path></svg>
                                                @endswitch
                                            </span>
                                            <span class="min-w-0 flex-1 text-[1rem] font-semibold {{ $sectionIsActive ? 'text-[color:var(--admin-primary)]' : 'text-[color:var(--admin-text)]' }}">{{ trans("admin.sidebar.sections.{$section['key']}") }}</span>
                                            <span class="text-[color:var(--admin-muted)] transition-transform duration-200" data-admin-chevron>
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"></path></svg>
                                            </span>
                                        </button>

                                        <div id="submenu-{{ $section['key'] }}" data-admin-submenu class="mt-0 opacity-0" style="max-height: 0;">
                                            <div class="ml-4 space-y-0.5 border-l border-[color:var(--admin-border)] pl-4 pt-2">
                                                @foreach ($section['items'] as $item)
                                                    @php($itemIsActive = $activeSidebarItem === $item)
                                                    @if (isset($sidebarItemRoutes[$item]))
                                                        <a href="{{ $sidebarItemRoutes[$item] }}" class="group flex w-full items-center gap-2.5 rounded-xl px-2.5 py-2 text-left transition {{ $itemIsActive ? 'bg-[color:var(--admin-primary-soft)]' : 'hover:bg-[color:var(--admin-surface)]' }}">
                                                            <span class="h-2 w-2 rounded-full transition {{ $itemIsActive ? 'bg-[color:var(--admin-primary)]' : 'bg-[color:var(--admin-border)] group-hover:bg-[color:var(--admin-primary)]' }}"></span>
                                                            <span class="text-[0.95rem] font-medium {{ $itemIsActive ? 'text-[color:var(--admin-text)]' : 'text-[color:var(--admin-text-soft)]' }}">{{ trans("admin.sidebar.items.{$item}") }}</span>
                                                        </a>
                                                    @else
                                                        <button type="button" class="group flex w-full items-center gap-2.5 rounded-xl px-2.5 py-2 text-left transition hover:bg-[color:var(--admin-surface)]">
                                                            <span class="h-2 w-2 rounded-full bg-[color:var(--admin-border)] transition group-hover:bg-[color:var(--admin-primary)]"></span>
                                                            <span class="text-[0.95rem] font-medium text-[color:var(--admin-text-soft)]">{{ trans("admin.sidebar.items.{$item}") }}</span>
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </section>
                                @endforeach
                            </nav>
                        </div>

                        <div class="border-t border-[color:var(--admin-border)] px-3 py-3">
                            <div class="flex items-center gap-3 rounded-[1.2rem] bg-[color:var(--admin-surface)] px-3 py-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-[color:var(--admin-primary)] text-sm font-bold text-white">{{ strtoupper(substr(auth()->user()->display_name, 0, 2)) }}</div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-[color:var(--admin-text)]">{{ auth()->user()->display_name }}</p>
                                    <p class="truncate text-xs text-[color:var(--admin-text-soft)]">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <div id="admin-main" class="min-w-0 space-y-5">
                        <header data-admin-shell class="relative rounded-[1.8rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-shell)] p-2 shadow-[var(--admin-shadow)] backdrop-blur-2xl sm:p-3">
                            <div class="flex items-center justify-between gap-2 lg:gap-3">
                                <div class="flex shrink-0 items-center">
                                    <button id="admin-sidebar-toggle" type="button" class="relative z-10 inline-flex h-11 w-11 shrink-0 cursor-pointer select-none items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[linear-gradient(180deg,rgba(79,124,255,0.22),rgba(79,124,255,0.12))] text-[color:var(--admin-primary)] shadow-[0_10px_24px_rgba(79,124,255,0.14)] transition duration-200 hover:-translate-y-0.5 hover:border-[color:var(--admin-primary)]/35 hover:bg-[linear-gradient(180deg,rgba(79,124,255,0.28),rgba(79,124,255,0.16))] active:scale-[0.98]" aria-label="{{ trans('admin.sidebar.toggle') }}">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M3 12h18"></path><path d="M3 18h18"></path></svg>
                                    </button>
                                </div>

                                <div class="hidden min-w-0 flex-1"></div>

                                <div class="hidden shrink-0 items-center gap-2 lg:flex">
                                    <button id="admin-install-toggle" type="button" class="hidden h-11 cursor-pointer items-center justify-center gap-2 rounded-[1.1rem] border border-[color:var(--admin-primary)]/40 bg-[color:var(--admin-primary-soft)] px-3 text-sm font-semibold text-[color:var(--admin-primary)] transition hover:bg-[color:var(--admin-surface-strong)]" aria-label="{{ trans('admin.pwa.install') }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"></path><path d="m7 10 5 5 5-5"></path><path d="M5 21h14"></path></svg>
                                        <span>{{ trans('admin.pwa.install_short') }}</span>
                                    </button>
                                    <button id="admin-fullscreen-toggle" type="button" class="inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]" aria-label="{{ trans('admin.fullscreen_toggle') }}">
                                        <span id="admin-fullscreen-icon" aria-hidden="true"></span>
                                    </button>
                                    <div class="relative" data-admin-locale-wrap>
                                        <button id="admin-locale-toggle-desktop" type="button" class="inline-flex h-11 cursor-pointer items-center justify-center gap-2 rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-3 text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]" aria-label="{{ trans('admin.language') }}">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"></path></svg>
                                            <span class="text-sm font-semibold">{{ $supportedLocales[app()->getLocale()]['label'] ?? strtoupper(app()->getLocale()) }}</span>
                                        </button>
                                    </div>
                                    <button id="admin-theme-toggle" type="button" class="inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]" aria-label="{{ trans('admin.theme_toggle') }}">
                                        <span id="admin-theme-icon" aria-hidden="true"></span>
                                    </button>
                                    <div class="relative" data-admin-profile-wrap>
                                        <button id="admin-profile-toggle" type="button" class="inline-flex h-11 cursor-pointer items-center gap-3 rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-3 text-left transition hover:bg-[color:var(--admin-surface-strong)]">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[color:var(--admin-primary)] text-sm font-bold text-white">{{ strtoupper(substr(auth()->user()->display_name, 0, 2)) }}</div>
                                            <div class="hidden min-w-0 xl:block">
                                                <p class="truncate text-sm font-semibold leading-none text-[color:var(--admin-text)]">{{ auth()->user()->display_name }}</p>
                                                <p class="mt-1 truncate text-xs text-[color:var(--admin-text-soft)]">{{ auth()->user()->email }}</p>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex min-w-0 shrink-0 items-center justify-end gap-2 lg:hidden">
                                    <button id="admin-install-toggle-mobile" type="button" class="hidden h-11 w-11 cursor-pointer items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-primary)]/40 bg-[color:var(--admin-primary-soft)] text-[color:var(--admin-primary)] transition hover:bg-[color:var(--admin-surface-strong)]" aria-label="{{ trans('admin.pwa.install') }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"></path><path d="m7 10 5 5 5-5"></path><path d="M5 21h14"></path></svg>
                                    </button>
                                    <button id="admin-fullscreen-toggle-mobile" type="button" class="inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]" aria-label="{{ trans('admin.fullscreen_toggle') }}">
                                        <span id="admin-fullscreen-icon-mobile" aria-hidden="true"></span>
                                    </button>
                                    <div class="relative" data-admin-locale-wrap>
                                        <button id="admin-locale-toggle" type="button" class="inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]" aria-label="{{ trans('admin.language') }}">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"></path></svg>
                                        </button>
                                    </div>
                                    <button id="admin-theme-toggle-mobile" type="button" class="inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]" aria-label="{{ trans('admin.theme_toggle') }}">
                                        <span id="admin-theme-icon-mobile" aria-hidden="true"></span>
                                    </button>
                                    <button id="admin-profile-toggle-mobile" type="button" class="inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full bg-[color:var(--admin-primary)] text-sm font-bold text-white">{{ strtoupper(substr(auth()->user()->display_name, 0, 2)) }}</button>
                                </div>
                            </div>

                            <div id="admin-pwa-ios-help" class="absolute right-4 top-[calc(100%+0.75rem)] z-30 hidden max-w-[300px] rounded-[1.5rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-shell)] p-4 text-sm leading-6 text-[color:var(--admin-text-soft)] shadow-[var(--admin-shadow)]">
                                <p class="font-semibold text-[color:var(--admin-text)]">{{ trans('admin.pwa.ios_title') }}</p>
                                <p class="mt-2">{{ trans('admin.pwa.ios_help') }}</p>
                            </div>

                            <div id="admin-locale-menu" class="absolute right-4 top-[calc(100%+0.75rem)] z-30 hidden min-w-56 rounded-[1.5rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-shell)] p-2 shadow-[var(--admin-shadow)]">
                                @foreach ($localeLinks as $item)
                                    <a href="{{ $item['href'] }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface)]">
                                        <span class="text-base">{{ $item['flag'] }}</span>
                                        <span>{{ $item['name'] }}</span>
                                        <span class="ml-auto text-xs uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ $item['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>

                            <div id="admin-profile-menu" class="absolute right-4 top-[calc(100%+0.8rem)] z-30 hidden min-w-[250px] rounded-[1.5rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-shell)] p-2 shadow-[var(--admin-shadow)]">
                                <div class="rounded-[1.15rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-4 py-4">
                                    <p class="text-base font-semibold text-[color:var(--admin-text)]">{{ auth()->user()->display_name }}</p>
                                    <p class="mt-1 text-sm text-[color:var(--admin-text-soft)]">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="mt-2 space-y-1.5">
                                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface)]">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        <span>{{ trans('admin.profile_menu.profile') }}</span>
                                    </a>
                                    <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface)]">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09A1.65 1.65 0 0 0 10.91 3H11a2 2 0 1 1 4 0h.09a1.65 1.65 0 0 0 1.51 1c.3.05.61-.01.87-.17.26-.16.47-.38.62-.65l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09A1.65 1.65 0 0 0 21 10.91V11a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"></path></svg>
                                        <span>{{ trans('admin.profile_menu.settings') }}</span>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-rose-300 transition hover:bg-rose-400/10">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                            <span>{{ trans('admin.profile_menu.logout') }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </header>

                        <main>
                            @yield('content')
                        </main>
                    </div>
                </div>
            </div>
        </div>
        <script>
            (function () {
                const root = document.documentElement;
                const sidebar = document.getElementById('admin-sidebar');
                const sidebarToggle = document.getElementById('admin-sidebar-toggle');
                const sidebarClose = document.getElementById('admin-sidebar-close');
                const sidebarBackdrop = document.getElementById('admin-sidebar-backdrop');
                const themeButton = document.getElementById('admin-theme-toggle');
                const themeButtonMobile = document.getElementById('admin-theme-toggle-mobile');
                const themeIcon = document.getElementById('admin-theme-icon');
                const themeIconMobile = document.getElementById('admin-theme-icon-mobile');
                const fullscreenButton = document.getElementById('admin-fullscreen-toggle');
                const fullscreenButtonMobile = document.getElementById('admin-fullscreen-toggle-mobile');
                const fullscreenIcon = document.getElementById('admin-fullscreen-icon');
                const fullscreenIconMobile = document.getElementById('admin-fullscreen-icon-mobile');
                const installButton = document.getElementById('admin-install-toggle');
                const installButtonMobile = document.getElementById('admin-install-toggle-mobile');
                const iosInstallHelp = document.getElementById('admin-pwa-ios-help');
                const localeMenu = document.getElementById('admin-locale-menu');
                const localeToggleDesktop = document.getElementById('admin-locale-toggle-desktop');
                const localeToggleMobile = document.getElementById('admin-locale-toggle');
                const profileMenu = document.getElementById('admin-profile-menu');
                const profileToggleDesktop = document.getElementById('admin-profile-toggle');
                const profileToggleMobile = document.getElementById('admin-profile-toggle-mobile');
                const desktopQuery = window.matchMedia('(min-width: 1024px)');
                const sidebarStorageKey = 'invita-plus-admin-sidebar';
                const fullscreenStorageKey = 'invita-plus-admin-fullscreen';
                const headerShell = document.querySelector('[data-admin-shell]');
                const localeButtons = [localeToggleDesktop, localeToggleMobile].filter(Boolean);
                const profileButtons = [profileToggleDesktop, profileToggleMobile].filter(Boolean);
                const installButtons = [installButton, installButtonMobile].filter(Boolean);
                let mobileSidebarOpen = false;
                let deferredInstallPrompt = null;

                function isStandaloneMode() {
                    return window.matchMedia('(display-mode: standalone)').matches
                        || window.matchMedia('(display-mode: fullscreen)').matches
                        || window.navigator.standalone === true;
                }

                function isIosDevice() {
                    return /iphone|ipad|ipod/i.test(window.navigator.userAgent);
                }

                function showInstallButtons() {
                    if (isStandaloneMode()) return;

                    installButtons.forEach(function (button) {
                        button.classList.remove('hidden');
                        button.classList.add(button === installButton ? 'inline-flex' : 'inline-flex');
                    });
                }

                function hideInstallButtons() {
                    installButtons.forEach(function (button) {
                        button.classList.add('hidden');
                        button.classList.remove('inline-flex');
                    });
                    iosInstallHelp?.classList.add('hidden');
                }

                function renderThemeIcon(theme) {
                    const icon = theme === 'light'
                        ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79Z"></path></svg>'
                        : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path></svg>';
                    if (themeIcon) themeIcon.innerHTML = icon;
                    if (themeIconMobile) themeIconMobile.innerHTML = icon;
                }

                function renderFullscreenIcon(isFullscreen) {
                    const icon = isFullscreen
                        ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3a2 2 0 0 1-2 2H3"></path><path d="M21 8h-3a2 2 0 0 1-2-2V3"></path><path d="M3 16h3a2 2 0 0 1 2 2v3"></path><path d="M16 21v-3a2 2 0 0 1 2-2h3"></path></svg>'
                        : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3"></path><path d="M21 8V5a2 2 0 0 0-2-2h-3"></path><path d="M3 16v3a2 2 0 0 0 2 2h3"></path><path d="M16 21h3a2 2 0 0 0 2-2v-3"></path></svg>';

                    if (fullscreenIcon) fullscreenIcon.innerHTML = icon;
                    if (fullscreenIconMobile) fullscreenIconMobile.innerHTML = icon;
                }

                function applyTheme(theme) {
                    root.dataset.adminTheme = theme;
                    window.localStorage.setItem('invita-plus-admin-theme', theme);
                    renderThemeIcon(theme);
                }

                function applyFullscreenPreference(isFullscreen) {
                    root.dataset.adminFullscreen = isFullscreen ? 'on' : 'off';
                    window.localStorage.setItem(fullscreenStorageKey, isFullscreen ? 'on' : 'off');
                    renderFullscreenIcon(isFullscreen);
                }

                async function toggleFullscreen() {
                    try {
                        if (document.fullscreenElement) {
                            await document.exitFullscreen();
                            applyFullscreenPreference(false);
                            return;
                        }

                        if (root.dataset.adminFullscreen === 'on') {
                            applyFullscreenPreference(false);
                            return;
                        }

                        if (document.documentElement.requestFullscreen) {
                            await document.documentElement.requestFullscreen();
                        }

                        applyFullscreenPreference(true);
                    } catch (error) {
                        applyFullscreenPreference(root.dataset.adminFullscreen !== 'on');
                    }
                }

                function setDesktopSidebarState(open) {
                    root.dataset.adminSidebar = open ? 'open' : 'closed';
                    window.localStorage.setItem(sidebarStorageKey, open ? 'open' : 'closed');
                }

                function syncMobileSidebar() {
                    if (desktopQuery.matches) {
                        sidebar.style.transform = '';
                        sidebar.style.opacity = '';
                        sidebar.style.pointerEvents = '';
                        sidebarBackdrop.classList.add('hidden');
                        return;
                    }

                    sidebar.style.transform = mobileSidebarOpen ? 'translateX(0)' : 'translateX(-110%)';
                    sidebar.style.opacity = mobileSidebarOpen ? '1' : '0';
                    sidebar.style.pointerEvents = mobileSidebarOpen ? 'auto' : 'none';
                    sidebarBackdrop.classList.toggle('hidden', !mobileSidebarOpen);
                }

                function positionFloatingMenu(menu, trigger) {
                    if (!menu || !trigger || !headerShell) return;

                    const shellRect = headerShell.getBoundingClientRect();
                    const triggerRect = trigger.getBoundingClientRect();
                    const rightOffset = Math.max(16, shellRect.right - triggerRect.right);

                    menu.style.left = 'auto';
                    menu.style.right = rightOffset + 'px';
                }

                function toggleFloatingMenu(menu, trigger, companionMenu) {
                    if (!menu || !trigger) return;

                    const willOpen = menu.classList.contains('hidden');
                    companionMenu?.classList.add('hidden');
                    iosInstallHelp?.classList.add('hidden');

                    if (!willOpen) {
                        menu.classList.add('hidden');
                        return;
                    }

                    positionFloatingMenu(menu, trigger);
                    menu.classList.remove('hidden');
                }

                applyTheme(window.localStorage.getItem('invita-plus-admin-theme') || 'dark');
                setDesktopSidebarState(window.localStorage.getItem(sidebarStorageKey) !== 'closed');
                applyFullscreenPreference(window.localStorage.getItem(fullscreenStorageKey) === 'on');
                syncMobileSidebar();

                if ('serviceWorker' in navigator && (window.isSecureContext || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) {
                    navigator.serviceWorker.register('/admin-sw.js', { scope: '/admin' }).catch(function () {});
                }

                window.addEventListener('beforeinstallprompt', function (event) {
                    event.preventDefault();
                    deferredInstallPrompt = event;
                    showInstallButtons();
                });

                window.addEventListener('appinstalled', function () {
                    deferredInstallPrompt = null;
                    hideInstallButtons();
                });

                if (isIosDevice() && !isStandaloneMode()) {
                    showInstallButtons();
                }

                installButtons.forEach(function (button) {
                    button?.addEventListener('click', async function () {
                        if (deferredInstallPrompt) {
                            deferredInstallPrompt.prompt();
                            await deferredInstallPrompt.userChoice;
                            deferredInstallPrompt = null;
                            hideInstallButtons();
                            return;
                        }

                        if (isIosDevice()) {
                            localeMenu?.classList.add('hidden');
                            profileMenu?.classList.add('hidden');
                            positionFloatingMenu(iosInstallHelp, button);
                            iosInstallHelp?.classList.toggle('hidden');
                        }
                    });
                });

                [themeButton, themeButtonMobile].forEach(function (button) {
                    button?.addEventListener('click', function () {
                        applyTheme(root.dataset.adminTheme === 'dark' ? 'light' : 'dark');
                    });
                });

                [fullscreenButton, fullscreenButtonMobile].forEach(function (button) {
                    button?.addEventListener('click', function () {
                        toggleFullscreen();
                    });
                });

                document.addEventListener('fullscreenchange', function () {
                    renderFullscreenIcon(Boolean(document.fullscreenElement) || root.dataset.adminFullscreen === 'on');
                });

                function handleSidebarToggle(event) {
                    event?.preventDefault();
                    event?.stopPropagation();

                    if (desktopQuery.matches) {
                        setDesktopSidebarState(root.dataset.adminSidebar !== 'open');
                    } else {
                        mobileSidebarOpen = !mobileSidebarOpen;
                        syncMobileSidebar();
                    }
                }

                sidebarToggle?.addEventListener('click', handleSidebarToggle);

                sidebarClose?.addEventListener('click', function () {
                    if (desktopQuery.matches) {
                        setDesktopSidebarState(false);
                    } else {
                        mobileSidebarOpen = false;
                        syncMobileSidebar();
                    }
                });

                sidebarBackdrop?.addEventListener('click', function () {
                    mobileSidebarOpen = false;
                    syncMobileSidebar();
                });

                localeButtons.forEach(function (button) {
                    button?.addEventListener('click', function () {
                        toggleFloatingMenu(localeMenu, button, profileMenu);
                    });
                });

                profileButtons.forEach(function (button) {
                    button?.addEventListener('click', function () {
                        toggleFloatingMenu(profileMenu, button, localeMenu);
                    });
                });

                document.addEventListener('mousedown', function (event) {
                    const clickedLocaleControl = localeButtons.some(function (button) {
                        return button.contains(event.target);
                    });
                    const clickedProfileControl = profileButtons.some(function (button) {
                        return button.contains(event.target);
                    });

                    if (localeMenu && !localeMenu.contains(event.target) && !clickedLocaleControl) {
                        localeMenu.classList.add('hidden');
                    }

                    if (profileMenu && !profileMenu.contains(event.target) && !clickedProfileControl) {
                        profileMenu.classList.add('hidden');
                    }

                    if (iosInstallHelp && !iosInstallHelp.contains(event.target) && !installButtons.some((button) => button.contains(event.target))) {
                        iosInstallHelp.classList.add('hidden');
                    }
                });

                document.querySelectorAll('[data-admin-accordion-trigger]').forEach(function (trigger) {
                    const panel = document.getElementById(trigger.dataset.target);
                    const chevron = trigger.querySelector('[data-admin-chevron]');

                    function setAccordion(expanded) {
                        trigger.dataset.expanded = expanded ? 'true' : 'false';
                        if (chevron) chevron.style.transform = expanded ? 'rotate(180deg)' : 'rotate(0deg)';
                        if (!panel) return;
                        if (expanded) {
                            panel.classList.remove('opacity-0');
                            panel.classList.add('opacity-100');
                            panel.style.marginTop = '0.35rem';
                            panel.style.maxHeight = panel.scrollHeight + 'px';
                        } else {
                            panel.classList.remove('opacity-100');
                            panel.classList.add('opacity-0');
                            panel.style.marginTop = '0';
                            panel.style.maxHeight = '0px';
                        }
                    }

                    setAccordion(trigger.dataset.expanded === 'true');
                    trigger.addEventListener('click', function () {
                        setAccordion(trigger.dataset.expanded !== 'true');
                    });
                });

                desktopQuery.addEventListener('change', function (event) {
                    if (!event.matches) {
                        mobileSidebarOpen = false;
                    }
                    localeMenu?.classList.add('hidden');
                    profileMenu?.classList.add('hidden');
                    iosInstallHelp?.classList.add('hidden');
                    syncMobileSidebar();
                });

                window.addEventListener('resize', function () {
                    localeMenu?.classList.add('hidden');
                    profileMenu?.classList.add('hidden');
                    iosInstallHelp?.classList.add('hidden');
                });
            }());
        </script>
    </body>
</html>
