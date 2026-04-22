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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m21 16-9 5-9-5"></path><path d="m21 12-9 5-9-5 9-5 9 5Z"></path></svg>
                        <span>{{ trans('admin.template_categories.breadcrumb') }}</span>
                    </div>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[color:var(--admin-text)] sm:text-[2.7rem]">{{ trans('admin.template_categories.title') }}</h1>
                    <p class="mt-3 max-w-3xl text-base leading-8 text-[color:var(--admin-text-soft)]">{{ trans('admin.template_categories.subtitle') }}</p>
                </div>

                <div class="flex w-full max-w-5xl flex-col gap-3 lg:items-end">
                    <a
                        href="{{ route('admin.template-categories.create', request()->has('lang') ? ['lang' => request()->query('lang')] : []) }}"
                        class="inline-flex h-11 cursor-pointer items-center justify-center rounded-[1.1rem] bg-[color:var(--admin-primary)] px-4 text-sm font-semibold text-white shadow-[0_18px_34px_rgba(79,124,255,0.22)] transition hover:brightness-110"
                    >
                        {{ trans('admin.template_categories.create.cta') }}
                    </a>

                    <form method="GET" action="{{ route('admin.template-categories.index') }}" class="w-full max-w-xl">
                        @if (request()->has('lang'))
                            <input type="hidden" name="lang" value="{{ request()->query('lang') }}">
                        @endif
                        <div class="flex h-12 items-center gap-3 rounded-[1.2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-strong)] px-4">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-[color:var(--admin-muted)]"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
                            <input
                                type="search"
                                name="search"
                                value="{{ $search }}"
                                placeholder="{{ trans('admin.template_categories.search_placeholder') }}"
                                class="min-w-0 flex-1 bg-transparent text-sm text-[color:var(--admin-text)] outline-none placeholder:text-[color:var(--admin-muted)]"
                            >
                            <button type="submit" class="inline-flex h-9 cursor-pointer items-center justify-center rounded-xl bg-[color:var(--admin-primary-soft)] px-3 text-sm font-semibold text-[color:var(--admin-primary)] transition hover:bg-[color:var(--admin-primary-soft)]/80">
                                {{ trans('admin.template_categories.search_action') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] shadow-[var(--admin-shadow)]">
            <div class="flex items-center justify-between gap-3 border-b border-[color:var(--admin-border)] px-5 py-4">
                <div>
                    <h2 class="text-xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.template_categories.table_title') }}</h2>
                    <p class="mt-1 text-sm text-[color:var(--admin-text-soft)]">
                        {{ trans_choice('admin.template_categories.results', $categories->total(), ['count' => $categories->total()]) }}
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[color:var(--admin-border)]">
                    <thead class="bg-[color:var(--admin-surface-soft)]">
                        <tr>
                            @foreach (['name', 'key', 'status', 'templates', 'actions'] as $column)
                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">
                                    {{ trans("admin.template_categories.columns.{$column}") }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[color:var(--admin-border)]">
                        @forelse ($categories as $category)
                            @php($translation = $category->translations->first())
                            <tr class="bg-transparent transition hover:bg-[color:var(--admin-surface-soft)]">
                                <td class="px-5 py-4 align-top">
                                    <div class="min-w-[16rem]">
                                        <p class="text-sm font-semibold text-[color:var(--admin-text)]">{{ $translation?->name ?? $category->key }}</p>
                                        <p class="mt-1 text-sm text-[color:var(--admin-text-soft)]">{{ $translation?->description ?? trans('admin.template_categories.empty_description') }}</p>
                                        <p class="mt-2 text-xs uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">/{{ $translation?->slug ?? $category->key }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="inline-flex rounded-full border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-text-soft)]">
                                        {{ $category->key }}
                                    </div>
                                    <p class="mt-2 text-xs text-[color:var(--admin-muted)]">{{ trans('admin.template_categories.order_label', ['order' => $category->sort_order]) }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $category->is_active ? 'bg-emerald-400/10 text-[color:var(--admin-positive)]' : 'bg-slate-400/10 text-[color:var(--admin-text-soft)]' }}">
                                        {{ $category->is_active ? trans('admin.template_categories.badges.active') : trans('admin.template_categories.badges.inactive') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="rounded-[1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-3 py-2 text-sm">
                                        <p class="font-semibold text-[color:var(--admin-text)]">{{ number_format($category->templates_count) }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.16em] text-[color:var(--admin-muted)]">{{ trans('admin.template_categories.templates_label') }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="flex min-w-[12rem] flex-wrap gap-2">
                                        <a
                                            href="{{ route('admin.template-categories.edit', array_merge(['templateCategory' => $category], request()->has('lang') ? ['lang' => request()->query('lang')] : [])) }}"
                                            class="inline-flex h-9 items-center justify-center rounded-xl border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-3 text-sm font-semibold text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]"
                                        >
                                            {{ trans('admin.template_categories.actions.edit') }}
                                        </a>

                                        <form method="POST" action="{{ route('admin.template-categories.destroy', array_merge(['templateCategory' => $category], request()->has('lang') ? ['lang' => request()->query('lang')] : [])) }}" onsubmit="return confirm('{{ trans('admin.template_categories.actions.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex h-9 cursor-pointer items-center justify-center rounded-xl bg-rose-400/10 px-3 text-sm font-semibold text-rose-300 transition hover:bg-rose-400/15">
                                                {{ trans('admin.template_categories.actions.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <p class="text-lg font-semibold text-[color:var(--admin-text)]">{{ trans('admin.template_categories.empty_title') }}</p>
                                    <p class="mt-2 text-sm text-[color:var(--admin-text-soft)]">{{ trans('admin.template_categories.empty_text') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="border-t border-[color:var(--admin-border)] px-5 py-4">
                    {{ $categories->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
