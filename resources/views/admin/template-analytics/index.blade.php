@extends('layouts.admin')

@section('content')
    @php
        $langQuery = request()->has('lang') ? ['lang' => request()->query('lang')] : [];
        $eventOptions = [
            'all' => trans('admin.template_analytics.filters.all_events'),
            'view' => trans('admin.template_analytics.events.view'),
            'use' => trans('admin.template_analytics.events.use'),
            'download' => trans('admin.template_analytics.events.download'),
        ];
        $statCards = [
            ['key' => 'views', 'label' => trans('admin.template_analytics.stats.views'), 'value' => $totals['views']],
            ['key' => 'uses', 'label' => trans('admin.template_analytics.stats.uses'), 'value' => $totals['uses']],
            ['key' => 'downloads', 'label' => trans('admin.template_analytics.stats.downloads'), 'value' => $totals['downloads']],
            ['key' => 'total', 'label' => trans('admin.template_analytics.stats.total'), 'value' => $totals['total']],
        ];
    @endphp

    <div class="space-y-5">
        <section class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <div class="flex items-center gap-3 text-sm text-[color:var(--admin-muted)]">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg>
                        <span>{{ trans('admin.template_analytics.breadcrumb') }}</span>
                    </div>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[color:var(--admin-text)] sm:text-[2.7rem]">{{ trans('admin.template_analytics.title') }}</h1>
                    <p class="mt-3 max-w-3xl text-base leading-8 text-[color:var(--admin-text-soft)]">{{ trans('admin.template_analytics.subtitle') }}</p>
                </div>

                <div class="rounded-[1.5rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-3 text-sm leading-6 text-[color:var(--admin-text-soft)] xl:max-w-sm">
                    {{ trans('admin.template_analytics.location_note') }}
                </div>
            </div>
        </section>

        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($statCards as $card)
                <div class="rounded-[1.5rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-5 shadow-[var(--admin-shadow)]">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ $card['label'] }}</p>
                    <p class="mt-3 text-3xl font-semibold tabular-nums text-[color:var(--admin-text)]">{{ number_format($card['value']) }}</p>
                </div>
            @endforeach
        </section>

        <section class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-5 shadow-[var(--admin-shadow)]">
            <form method="GET" action="{{ route('admin.template-analytics.index') }}" class="grid gap-3 xl:grid-cols-[1.4fr_0.8fr_0.8fr_0.7fr_0.8fr_0.8fr_auto] xl:items-end">
                @if (request()->has('lang'))
                    <input type="hidden" name="lang" value="{{ request()->query('lang') }}">
                @endif

                <label class="block">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_analytics.filters.search') }}</span>
                    <input name="search" value="{{ $filters['search'] }}" placeholder="{{ trans('admin.template_analytics.filters.search_placeholder') }}" class="h-11 w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 text-sm text-[color:var(--admin-text)] outline-none placeholder:text-[color:var(--admin-muted)]">
                </label>

                <label class="block">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_analytics.filters.event') }}</span>
                    <select name="event_type" class="h-11 w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 text-sm text-[color:var(--admin-text)] outline-none">
                        @foreach ($eventOptions as $value => $label)
                            <option value="{{ $value }}" @selected($filters['event_type'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_analytics.filters.country') }}</span>
                    <select name="country_code" class="h-11 w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 text-sm text-[color:var(--admin-text)] outline-none">
                        <option value="">{{ trans('admin.template_analytics.filters.all_countries') }}</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->country_code }}" @selected($filters['country_code'] === $country->country_code)>{{ $country->country_name ?? $country->country_code }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_analytics.filters.locale') }}</span>
                    <select name="locale" class="h-11 w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 text-sm text-[color:var(--admin-text)] outline-none">
                        <option value="">{{ trans('admin.template_analytics.filters.all_locales') }}</option>
                        @foreach ($supportedLocales as $code => $meta)
                            <option value="{{ $code }}" @selected($filters['locale'] === $code)>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_analytics.filters.date_from') }}</span>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="h-11 w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 text-sm text-[color:var(--admin-text)] outline-none">
                </label>

                <label class="block">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ trans('admin.template_analytics.filters.date_to') }}</span>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="h-11 w-full rounded-[1.1rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 text-sm text-[color:var(--admin-text)] outline-none">
                </label>

                <button type="submit" class="inline-flex h-11 cursor-pointer items-center justify-center rounded-[1.1rem] bg-[color:var(--admin-primary)] px-5 text-sm font-semibold text-white transition hover:brightness-110">
                    {{ trans('admin.template_analytics.filters.apply') }}
                </button>
            </form>
        </section>

        <section class="overflow-hidden rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] shadow-[var(--admin-shadow)]">
            <div class="border-b border-[color:var(--admin-border)] px-5 py-4">
                <h2 class="text-xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.template_analytics.table_title') }}</h2>
                <p class="mt-1 text-sm text-[color:var(--admin-text-soft)]">{{ trans_choice('admin.template_analytics.results', $rows->total(), ['count' => $rows->total()]) }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[color:var(--admin-border)]">
                    <thead class="bg-[color:var(--admin-surface-soft)]">
                        <tr>
                            @foreach (['template', 'location', 'locale', 'views', 'uses', 'downloads', 'total', 'last_activity'] as $column)
                                <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">
                                    {{ trans("admin.template_analytics.columns.{$column}") }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[color:var(--admin-border)]">
                        @forelse ($rows as $row)
                            @php
                                $template = $templates->get($row->template_id);
                                $translation = $template?->translations->first();
                                $country = $row->country_name ?: ($row->country_code ?: trans('admin.template_analytics.unknown'));
                                $region = $row->region_name ?: $row->region_code;
                                $city = $row->city;
                            @endphp
                            <tr class="transition hover:bg-[color:var(--admin-surface-soft)]">
                                <td class="px-5 py-4 align-top">
                                    <p class="text-sm font-semibold text-[color:var(--admin-text)]">{{ $translation?->name ?? $template?->code ?? trans('admin.template_analytics.deleted_template') }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.18em] text-[color:var(--admin-muted)]">{{ $template?->code ?? 'deleted' }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <p class="text-sm font-semibold text-[color:var(--admin-text)]">{{ $country }}</p>
                                    <p class="mt-1 text-sm text-[color:var(--admin-text-soft)]">{{ collect([$region, $city])->filter()->implode(' / ') ?: trans('admin.template_analytics.no_region') }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <span class="inline-flex rounded-full border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--admin-text-soft)]">{{ $row->locale ?: trans('admin.template_analytics.unknown') }}</span>
                                </td>
                                <td class="px-5 py-4 align-top text-sm font-semibold tabular-nums text-[color:var(--admin-text)]">{{ number_format((int) $row->views) }}</td>
                                <td class="px-5 py-4 align-top text-sm font-semibold tabular-nums text-[color:var(--admin-text)]">{{ number_format((int) $row->uses) }}</td>
                                <td class="px-5 py-4 align-top text-sm font-semibold tabular-nums text-[color:var(--admin-text)]">{{ number_format((int) $row->downloads) }}</td>
                                <td class="px-5 py-4 align-top text-sm font-semibold tabular-nums text-[color:var(--admin-primary)]">{{ number_format((int) $row->total) }}</td>
                                <td class="px-5 py-4 align-top text-sm text-[color:var(--admin-text-soft)]">{{ optional($row->last_activity_at ? \Illuminate\Support\Carbon::parse($row->last_activity_at) : null)->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-14 text-center">
                                    <p class="text-lg font-semibold text-[color:var(--admin-text)]">{{ trans('admin.template_analytics.empty_title') }}</p>
                                    <p class="mt-2 text-sm text-[color:var(--admin-text-soft)]">{{ trans('admin.template_analytics.empty_text') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($rows->hasPages())
                <div class="border-t border-[color:var(--admin-border)] px-5 py-4">
                    {{ $rows->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
