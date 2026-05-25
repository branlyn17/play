@extends('layouts.auth')

@section('content')
    <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(125,211,252,0.18),_transparent_30%),linear-gradient(180deg,#0f172a,#020617)]">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute left-0 top-10 h-72 w-72 rounded-full bg-sky-400/10 blur-3xl"></div>
            <div class="absolute bottom-0 right-10 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid w-full gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                <section class="space-y-6">
                    <span class="inline-flex rounded-full border border-sky-300/20 bg-sky-300/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-sky-100">
                        {{ __('auth.login.secure_access_badge') }}
                    </span>

                    <div class="space-y-4">
                        <h1 class="max-w-2xl text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                            {{ __('auth.login.hero.title') }}
                        </h1>
                        <p class="max-w-xl text-lg leading-8 text-slate-300">
                            {{ __('auth.login.hero.description') }}
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[1.6rem] border border-white/10 bg-white/5 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ __('auth.login.superadmin_seeded_label') }}</p>
                            <p class="mt-3 text-lg font-semibold text-white">{{ __('auth.login.superadmin_seeded_name') }}</p>
                            <p class="mt-2 text-sm text-slate-300">{{ __('auth.login.superadmin_seeded_description') }}</p>
                        </div>
                        <div class="rounded-[1.6rem] border border-white/10 bg-white/5 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ __('auth.login.customer_seeded_label') }}</p>
                            <p class="mt-3 text-lg font-semibold text-white">{{ __('auth.login.customer_seeded_name') }}</p>
                            <p class="mt-2 text-sm text-slate-300">{{ __('auth.login.customer_seeded_description') }}</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-white/10 bg-white/6 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.28)] backdrop-blur-xl sm:p-8">
                    <div class="mb-6">
                        <p class="text-sm uppercase tracking-[0.28em] text-slate-400">{{ __('auth.login.form.badge') }}</p>
                        <h2 class="mt-3 text-3xl font-semibold text-white">{{ __('auth.login.form.title') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-300">
                            {{ __('auth.login.form.description') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

                        <div>
                            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('auth.login.form.email') }}</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-white outline-none transition focus:border-sky-400"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('auth.login.form.password') }}</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-white outline-none transition focus:border-sky-400"
                            >
                        </div>

                        <label class="flex items-center gap-3 text-sm text-slate-300">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-white/10 bg-slate-950/60 text-indigo-500 focus:ring-indigo-500">
                            {{ __('auth.login.form.remember') }}
                        </label>

                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_rgba(99,102,241,0.28)] transition hover:bg-indigo-400"
                        >
                            {{ __('auth.login.form.submit') }}
                        </button>
                    </form>

                    <div class="mt-6 rounded-[1.6rem] border border-white/10 bg-slate-950/45 p-4 text-sm text-slate-300">
                        <p class="font-semibold text-white">{{ __('auth.login.development_password_title') }}</p>
                        <p class="mt-2"><code class="rounded bg-white/5 px-2 py-1 text-sky-200">{{ __('auth.login.development_password_value') }}</code></p>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
