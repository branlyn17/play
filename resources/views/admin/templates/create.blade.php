@extends('layouts.admin')

@section('content')
    <div class="space-y-5">
        <section class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <div class="flex items-center gap-3 text-sm text-[color:var(--admin-muted)]">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 4l9 5.5"></path><path d="M5 10.5V20h14v-9.5"></path></svg>
                        <span>{{ trans('admin.templates.create.breadcrumb') }}</span>
                    </div>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[color:var(--admin-text)] sm:text-[2.7rem]">{{ trans('admin.templates.create.title') }}</h1>
                    <p class="mt-3 max-w-3xl text-base leading-8 text-[color:var(--admin-text-soft)]">{{ trans('admin.templates.create.subtitle') }}</p>
                </div>

                <a
                    href="{{ route('admin.templates.index', request()->has('lang') ? ['lang' => request()->query('lang')] : []) }}"
                    class="inline-flex h-11 cursor-pointer items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-4 text-sm font-semibold text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]"
                >
                    {{ trans('admin.templates.create.back') }}
                </a>
            </div>
        </section>

        @if ($errors->any())
            <section class="rounded-[1.8rem] border border-rose-400/30 bg-rose-400/10 px-5 py-4 text-sm text-rose-100 shadow-[var(--admin-shadow)]">
                <p class="font-semibold">{{ trans('admin.templates.create.validation_title') }}</p>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-rose-100/90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </section>
        @endif

        <form method="POST" action="{{ route('admin.templates.store', request()->has('lang') ? ['lang' => request()->query('lang')] : []) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <section class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
                <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
                    <div class="space-y-5">
                        <div>
                            <h2 class="text-xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.templates.create.sections.base') }}</h2>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--admin-text-soft)]">{{ trans('admin.templates.create.base_help') }}</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.fields.code') }}</span>
                                <input name="code" value="{{ old('code') }}" class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none">
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.fields.category') }}</span>
                                <select name="category_key" class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->key }}" @selected(old('category_key') === $category->key)>
                                            {{ $category->translations->first()?->name ?? strtoupper($category->key) }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.fields.sort_order') }}</span>
                                <input type="number" min="1" name="sort_order" value="{{ old('sort_order') }}" class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none">
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.fields.catalog_accent') }}</span>
                                <select name="catalog_accent" class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none">
                                    @foreach ($colorTokens as $token)
                                        <option value="{{ $token }}" @selected(old('catalog_accent') === $token)>{{ ucfirst($token) }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <label class="block">
                            <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.fields.catalog_background') }}</span>
                            <textarea name="catalog_background" rows="4" class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none">{{ old('catalog_background') }}</textarea>
                        </label>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <label class="flex items-center gap-3 rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)]">
                                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="h-4 w-4 rounded border-white/20 bg-transparent">
                                <span>{{ trans('admin.templates.create.fields.is_active') }}</span>
                            </label>
                            <label class="flex items-center gap-3 rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)]">
                                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured')) class="h-4 w-4 rounded border-white/20 bg-transparent">
                                <span>{{ trans('admin.templates.create.fields.is_featured') }}</span>
                            </label>
                            <label class="flex items-center gap-3 rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)]">
                                <input type="checkbox" name="is_premium" value="1" @checked(old('is_premium')) class="h-4 w-4 rounded border-white/20 bg-transparent">
                                <span>{{ trans('admin.templates.create.fields.is_premium') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <h2 class="text-xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.templates.create.sections.upload') }}</h2>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--admin-text-soft)]">{{ trans('admin.templates.create.upload_help') }}</p>
                        </div>

                        <div class="grid gap-4">
                            <label class="block">
                                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.fields.html_file') }}</span>
                                <input type="file" name="source_html" accept=".html,.htm,text/html" class="block w-full rounded-[1.1rem] border border-dashed border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-6 text-sm text-[color:var(--admin-text)] file:mr-4 file:cursor-pointer file:rounded-xl file:border-0 file:bg-[color:var(--admin-primary-soft)] file:px-3 file:py-2 file:text-sm file:font-semibold file:text-[color:var(--admin-primary)]">
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.fields.json_file') }}</span>
                                <input type="file" name="source_payload" accept=".json,application/json" class="block w-full rounded-[1.1rem] border border-dashed border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-6 text-sm text-[color:var(--admin-text)] file:mr-4 file:cursor-pointer file:rounded-xl file:border-0 file:bg-[color:var(--admin-primary-soft)] file:px-3 file:py-2 file:text-sm file:font-semibold file:text-[color:var(--admin-primary)]">
                            </label>
                        </div>

                        <div class="rounded-[1.3rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] p-4">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.sections.json_contract') }}</h3>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--admin-text-soft)]">{{ trans('admin.templates.create.json_contract_help') }}</p>
                        </div>

                        <div class="rounded-[1.3rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] p-4">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.sections.placeholders') }}</h3>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--admin-text-soft)]">{{ trans('admin.templates.create.placeholders_help') }}</p>
                            <p class="mt-3 text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.required_placeholders') }}</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach ($requiredPlaceholders as $placeholder)
                                    <code class="rounded-full border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-3 py-1 text-xs text-[color:var(--admin-text)]">{{ $placeholder }}</code>
                                @endforeach
                            </div>
                            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.templates.create.optional_placeholders') }}</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach ($optionalPlaceholders as $placeholder)
                                    <code class="rounded-full border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-3 py-1 text-xs text-[color:var(--admin-text-soft)]">{{ $placeholder }}</code>
                                @endforeach
                            </div>
                            <p class="mt-5 text-sm leading-7 text-[color:var(--admin-text-soft)]">{{ trans('admin.templates.create.optional_sections_help') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex h-12 cursor-pointer items-center justify-center rounded-[1.1rem] bg-[color:var(--admin-primary)] px-5 text-sm font-semibold text-white shadow-[0_18px_34px_rgba(79,124,255,0.22)] transition hover:brightness-110">
                    {{ trans('admin.templates.create.submit') }}
                </button>
            </div>
        </form>
    </div>
@endsection
