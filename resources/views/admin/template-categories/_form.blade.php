@php
    $formTranslations = old('translations', $translations);
    $routeQuery = request()->has('lang') ? ['lang' => request()->query('lang')] : [];
@endphp

@if ($errors->any())
    <section class="rounded-[1.8rem] border border-rose-400/30 bg-rose-400/10 px-5 py-4 text-sm text-rose-100 shadow-[var(--admin-shadow)]">
        <p class="font-semibold">{{ trans('admin.template_categories.validation_title') }}</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </section>
@endif

<form method="POST" action="{{ $action }}" class="space-y-5">
    @csrf
    @isset($method)
        @method($method)
    @endisset

    <section class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
        <div class="grid gap-5 lg:grid-cols-[1fr_0.7fr]">
            <div>
                <h2 class="text-xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.template_categories.form.base_title') }}</h2>
                <p class="mt-2 max-w-2xl text-sm leading-7 text-[color:var(--admin-text-soft)]">{{ trans('admin.template_categories.form.base_help') }}</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_categories.fields.key') }}</span>
                    <input
                        type="text"
                        name="key"
                        value="{{ old('key', $category->key) }}"
                        required
                        class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none transition focus:border-[color:var(--admin-primary)]"
                    >
                </label>

                <label class="block">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_categories.fields.sort_order') }}</span>
                    <input
                        type="number"
                        min="0"
                        name="sort_order"
                        value="{{ old('sort_order', $category->sort_order) }}"
                        class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none transition focus:border-[color:var(--admin-primary)]"
                    >
                </label>

                <label class="flex items-center gap-3 rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm font-semibold text-[color:var(--admin-text)] sm:col-span-2">
                    <input type="hidden" name="is_active" value="0">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked((bool) old('is_active', $category->is_active))
                        class="h-4 w-4 rounded border-[color:var(--admin-border)] bg-transparent"
                    >
                    <span>{{ trans('admin.template_categories.fields.is_active') }}</span>
                </label>
            </div>
        </div>
    </section>

    <section class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.template_categories.form.translations_title') }}</h2>
                <p class="mt-2 max-w-3xl text-sm leading-7 text-[color:var(--admin-text-soft)]">{{ trans('admin.template_categories.form.translations_help') }}</p>
            </div>
        </div>

        <div class="mt-6 space-y-5">
            @foreach ($locales as $locale => $meta)
                @php($localeValues = $formTranslations[$locale] ?? [])
                <div class="rounded-[1.6rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] p-5">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">{{ $meta['flag'] }}</span>
                        <div>
                            <h3 class="text-base font-semibold text-[color:var(--admin-text)]">{{ $meta['name'] }}</h3>
                            <p class="text-xs uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ $meta['label'] }}</p>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 lg:grid-cols-2">
                        <label class="block">
                            <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_categories.fields.name') }}</span>
                            <input
                                type="text"
                                name="translations[{{ $locale }}][name]"
                                value="{{ $localeValues['name'] ?? '' }}"
                                required
                                class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none transition focus:border-[color:var(--admin-primary)]"
                            >
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_categories.fields.slug') }}</span>
                            <input
                                type="text"
                                name="translations[{{ $locale }}][slug]"
                                value="{{ $localeValues['slug'] ?? '' }}"
                                class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none transition focus:border-[color:var(--admin-primary)]"
                            >
                        </label>

                        <label class="block lg:col-span-2">
                            <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_categories.fields.description') }}</span>
                            <textarea
                                name="translations[{{ $locale }}][description]"
                                rows="3"
                                class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-4 py-3 text-sm leading-7 text-[color:var(--admin-text)] outline-none transition focus:border-[color:var(--admin-primary)]"
                            >{{ $localeValues['description'] ?? '' }}</textarea>
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_categories.fields.seo_title') }}</span>
                            <input
                                type="text"
                                name="translations[{{ $locale }}][seo_title]"
                                value="{{ $localeValues['seo_title'] ?? '' }}"
                                class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none transition focus:border-[color:var(--admin-primary)]"
                            >
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_categories.fields.seo_description') }}</span>
                            <input
                                type="text"
                                name="translations[{{ $locale }}][seo_description]"
                                value="{{ $localeValues['seo_description'] ?? '' }}"
                                class="w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-4 py-3 text-sm text-[color:var(--admin-text)] outline-none transition focus:border-[color:var(--admin-primary)]"
                            >
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a
            href="{{ route('admin.template-categories.index', $routeQuery) }}"
            class="inline-flex h-11 items-center justify-center rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] px-5 text-sm font-semibold text-[color:var(--admin-text)] transition hover:bg-[color:var(--admin-surface-strong)]"
        >
            {{ trans('admin.template_categories.form.cancel') }}
        </a>
        <button
            type="submit"
            class="inline-flex h-11 cursor-pointer items-center justify-center rounded-[1.1rem] bg-[color:var(--admin-primary)] px-5 text-sm font-semibold text-white shadow-[0_18px_34px_rgba(79,124,255,0.22)] transition hover:brightness-110"
        >
            {{ $submitLabel }}
        </button>
    </div>
</form>
