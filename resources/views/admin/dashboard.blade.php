@extends('layouts.admin')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[2rem] border border-white/10 bg-white/6 p-6 shadow-[0_24px_70px_rgba(15,23,42,0.22)]">
            <p class="text-sm uppercase tracking-[0.28em] text-slate-400">Bienvenido</p>
            <h1 class="mt-3 text-4xl font-semibold text-white">Hola, {{ $user->display_name }}.</h1>
            <p class="mt-4 max-w-2xl text-base leading-8 text-slate-300">
                Este es el panel base del superadmin. Dejé una estructura inicial con sidebar para que desde aquí podamos ir creciendo hacia templates, usuarios, métricas, catálogo y traducciones.
            </p>
        </div>
    </section>
@endsection
