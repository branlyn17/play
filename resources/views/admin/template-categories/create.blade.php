@extends('layouts.admin')

@section('content')
    <div class="space-y-5">
        <section class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="flex items-center gap-3 text-sm text-[color:var(--admin-muted)]">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m21 16-9 5-9-5"></path><path d="m21 12-9 5-9-5 9-5 9 5Z"></path></svg>
                        <span>{{ trans('admin.template_categories.create.breadcrumb') }}</span>
                    </div>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[color:var(--admin-text)] sm:text-[2.7rem]">{{ trans('admin.template_categories.create.title') }}</h1>
                    <p class="mt-3 max-w-3xl text-base leading-8 text-[color:var(--admin-text-soft)]">{{ trans('admin.template_categories.create.subtitle') }}</p>
                </div>

                <a
                    href="{{ route('admin.template-categories.index', request()->has('lang') ? ['lang' => request()->query('lang')] : []) }}"
                    class="inline-flex h-11 items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-5 text-sm font-semibold text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]"
                >
                    {{ trans('admin.template_categories.create.back') }}
                </a>
            </div>
        </section>

        @include('admin.template-categories._form', [
            'action' => route('admin.template-categories.store', request()->has('lang') ? ['lang' => request()->query('lang')] : []),
            'submitLabel' => trans('admin.template_categories.create.submit'),
        ])
    </div>
@endsection
