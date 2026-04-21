@extends('layouts.admin')

@section('content')
    <div class="space-y-5">
        @if (session('status'))
            <section class="rounded-[1.8rem] border border-emerald-400/30 bg-emerald-400/10 px-5 py-4 text-sm text-emerald-100 shadow-[var(--admin-shadow)]">
                {{ session('status') }}
            </section>
        @endif

        <section class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="flex items-center gap-3 text-sm text-[color:var(--admin-muted)]">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 4l9 5.5"></path><path d="M5 10.5V20h14v-9.5"></path></svg>
                        <span>{{ trans('admin.templates.breadcrumb') }}</span>
                    </div>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[color:var(--admin-text)] sm:text-[2.7rem]">{{ trans('admin.templates.title') }}</h1>
                    <p class="mt-3 max-w-3xl text-base leading-8 text-[color:var(--admin-text-soft)]">{{ trans('admin.templates.subtitle') }}</p>
                </div>

                <div class="flex w-full max-w-5xl flex-col gap-3 lg:items-end">
                    <a
                        href="{{ route('admin.templates.create', request()->has('lang') ? ['lang' => request()->query('lang')] : []) }}"
                        class="inline-flex h-11 cursor-pointer items-center justify-center rounded-[1.1rem] bg-[color:var(--admin-primary)] px-4 text-sm font-semibold text-white shadow-[0_18px_34px_rgba(79,124,255,0.22)] transition hover:brightness-110"
                    >
                        {{ trans('admin.templates.create.cta') }}
                    </a>

                    <form method="GET" action="{{ route('admin.templates.index') }}" class="w-full max-w-xl">
                        @if (request()->has('lang'))
                            <input type="hidden" name="lang" value="{{ request()->query('lang') }}">
                        @endif
                        <div class="flex h-12 items-center gap-3 rounded-[1.2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-strong)] px-4">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-[color:var(--admin-muted)]"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
                            <input
                                type="search"
                                name="search"
                                value="{{ $search }}"
                                placeholder="{{ trans('admin.templates.search_placeholder') }}"
                                class="min-w-0 flex-1 bg-transparent text-sm text-[color:var(--admin-text)] outline-none placeholder:text-[color:var(--admin-muted)]"
                            >
                            <button type="submit" class="inline-flex h-9 cursor-pointer items-center justify-center rounded-xl bg-[color:var(--admin-primary-soft)] px-3 text-sm font-semibold text-[color:var(--admin-primary)] transition hover:bg-[color:var(--admin-primary-soft)]/80">
                                {{ trans('admin.templates.search_action') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] shadow-[var(--admin-shadow)]">
            <div class="flex items-center justify-between gap-3 border-b border-[color:var(--admin-border)] px-5 py-4">
                <div>
                    <h2 class="text-xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.templates.table_title') }}</h2>
                    <p class="mt-1 text-sm text-[color:var(--admin-text-soft)]">
                        {{ trans_choice('admin.templates.results', $templates->total(), ['count' => $templates->total()]) }}
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[color:var(--admin-border)]">
                    <thead class="bg-[color:var(--admin-surface-soft)]">
                        <tr>
                            @foreach (['name', 'code', 'category', 'status', 'metrics'] as $column)
                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">
                                    {{ trans("admin.templates.columns.{$column}") }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[color:var(--admin-border)]">
                        @forelse ($templates as $template)
                            @php
                                $translation = $template->translations->first();
                                $categoryTranslation = $template->category?->translations->first();
                            @endphp
                            <tr class="bg-transparent transition hover:bg-[color:var(--admin-surface-soft)]">
                                <td class="px-5 py-4 align-top">
                                    <div class="min-w-[16rem]">
                                        <p class="text-sm font-semibold text-[color:var(--admin-text)]">{{ $translation?->name ?? strtoupper($template->code) }}</p>
                                        <p class="mt-1 text-sm text-[color:var(--admin-text-soft)]">{{ $translation?->teaser ?? trans('admin.templates.empty_teaser') }}</p>
                                        <p class="mt-2 text-xs uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">/{{ $translation?->slug ?? $template->code }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="inline-flex rounded-full border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-text-soft)]">
                                        {{ $template->code }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div>
                                        <p class="text-sm font-medium text-[color:var(--admin-text)]">{{ $categoryTranslation?->name ?? trans('admin.templates.uncategorized') }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ strtoupper($template->default_locale) }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $template->is_active ? 'bg-emerald-400/10 text-[color:var(--admin-positive)]' : 'bg-slate-400/10 text-[color:var(--admin-text-soft)]' }}">
                                            {{ $template->is_active ? trans('admin.templates.badges.active') : trans('admin.templates.badges.inactive') }}
                                        </span>
                                        @if ($template->is_featured)
                                            <span class="inline-flex rounded-full bg-[color:var(--admin-primary-soft)] px-3 py-1 text-xs font-semibold text-[color:var(--admin-primary)]">
                                                {{ trans('admin.templates.badges.featured') }}
                                            </span>
                                        @endif
                                        @if ($template->is_premium)
                                            <span class="inline-flex rounded-full bg-amber-400/10 px-3 py-1 text-xs font-semibold text-amber-300">
                                                {{ trans('admin.templates.badges.premium') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="grid min-w-[11rem] grid-cols-3 gap-2 text-sm">
                                        <div class="rounded-[1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-3 py-2">
                                            <p class="text-xs uppercase tracking-[0.16em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.metrics.views') }}</p>
                                            <p class="mt-1 font-semibold text-[color:var(--admin-text)]">{{ number_format($template->view_count) }}</p>
                                        </div>
                                        <div class="rounded-[1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-3 py-2">
                                            <p class="text-xs uppercase tracking-[0.16em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.metrics.downloads') }}</p>
                                            <p class="mt-1 font-semibold text-[color:var(--admin-text)]">{{ number_format($template->download_count) }}</p>
                                        </div>
                                        <div class="rounded-[1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-3 py-2">
                                            <p class="text-xs uppercase tracking-[0.16em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.metrics.uses') }}</p>
                                            <p class="mt-1 font-semibold text-[color:var(--admin-text)]">{{ number_format($template->use_count) }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <p class="text-lg font-semibold text-[color:var(--admin-text)]">{{ trans('admin.templates.empty_title') }}</p>
                                    <p class="mt-2 text-sm text-[color:var(--admin-text-soft)]">{{ trans('admin.templates.empty_text') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($templates->hasPages())
                <div class="border-t border-[color:var(--admin-border)] px-5 py-4">
                    {{ $templates->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
