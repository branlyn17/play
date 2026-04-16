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
                        Acceso seguro
                    </span>

                    <div class="space-y-4">
                        <h1 class="max-w-2xl text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                            Ingresa a Invita Plus con una base lista para crecer.
                        </h1>
                        <p class="max-w-xl text-lg leading-8 text-slate-300">
                            Los clientes vuelven al sitio publico con su nombre visible. El superadmin entra al panel para gestionar el proyecto desde una base mas profesional.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[1.6rem] border border-white/10 bg-white/5 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Superadmin seeded</p>
                            <p class="mt-3 text-lg font-semibold text-white">Branlyn</p>
                            <p class="mt-2 text-sm text-slate-300">Acceso al panel admin con sidebar base.</p>
                        </div>
                        <div class="rounded-[1.6rem] border border-white/10 bg-white/5 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Customer seeded</p>
                            <p class="mt-3 text-lg font-semibold text-white">Anahi</p>
                            <p class="mt-2 text-sm text-slate-300">Vuelve al sitio y muestra su nombre en la interfaz publica.</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-white/10 bg-white/6 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.28)] backdrop-blur-xl sm:p-8">
                    <div class="mb-6">
                        <p class="text-sm uppercase tracking-[0.28em] text-slate-400">Login</p>
                        <h2 class="mt-3 text-3xl font-semibold text-white">Iniciar sesion</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-300">
                            Usa una de las cuentas seed para probar el flujo por rol.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

                        <div>
                            <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Email</label>
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
                            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Password</label>
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
                            Recordarme en este dispositivo
                        </label>

                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_rgba(99,102,241,0.28)] transition hover:bg-indigo-400"
                        >
                            Entrar
                        </button>
                    </form>

                    <div class="mt-6 rounded-[1.6rem] border border-white/10 bg-slate-950/45 p-4 text-sm text-slate-300">
                        <p class="font-semibold text-white">Password de desarrollo</p>
                        <p class="mt-2"><code class="rounded bg-white/5 px-2 py-1 text-sky-200">InvitaPlus123!</code></p>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
