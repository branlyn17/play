<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-white antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(125,211,252,0.08),_transparent_24%),linear-gradient(180deg,#020617,#0f172a)]">
            <div class="mx-auto grid min-h-screen max-w-7xl lg:grid-cols-[280px_minmax(0,1fr)]">
                <aside class="border-b border-white/10 bg-slate-950/60 px-5 py-6 backdrop-blur-2xl lg:border-b-0 lg:border-r">
                    <div class="rounded-[1.6rem] border border-white/10 bg-white/6 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Admin panel</p>
                        <p class="mt-3 text-2xl font-semibold text-white">{{ config('app.name') }}</p>
                        <p class="mt-2 text-sm text-slate-300">{{ auth()->user()->display_name }}</p>
                    </div>

                    <nav class="mt-6 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl border border-white/10 bg-white/8 px-4 py-3 text-sm font-semibold text-white">
                            Dashboard
                        </a>
                    </nav>

                    <form method="POST" action="{{ route('logout') }}" class="mt-6">
                        @csrf
                        <button
                            type="submit"
                            class="w-full rounded-2xl border border-white/10 bg-white/6 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10"
                        >
                            Cerrar sesion
                        </button>
                    </form>
                </aside>

                <main class="px-4 py-6 sm:px-6 lg:px-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
