@extends('layouts.admin')

@section('content')
    @php
        $stats = trans('admin.dashboard.stats');
        $actionItems = trans('admin.dashboard.actions.items');
    @endphp

    <div class="space-y-5">
        <section class="grid gap-5 xl:grid-cols-[1.15fr_0.85fr]">
            <article class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <div class="flex items-center gap-3 text-sm text-[color:var(--admin-muted)]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 4l9 5.5"></path><path d="M5 10.5V20h14v-9.5"></path></svg>
                            <span>{{ trans('admin.dashboard.breadcrumb') }}</span>
                        </div>
                        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[color:var(--admin-text)] sm:text-5xl">{{ trans('admin.dashboard.title') }}</h1>
                        <p class="mt-4 max-w-3xl text-base leading-8 text-[color:var(--admin-text-soft)]">{{ trans('admin.dashboard.subtitle') }}</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-strong)] px-4 py-4 text-sm leading-7 text-[color:var(--admin-text-soft)] lg:max-w-xs">
                        {{ trans('admin.dashboard.workspace.text') }}
                    </div>
                </div>
            </article>

            <article class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
                <h2 class="text-2xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.dashboard.actions.title') }}</h2>
                <div class="mt-5 space-y-3">
                    @foreach ($actionItems as $item)
                        <div class="rounded-[1.3rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] px-4 py-4 text-sm leading-7 text-[color:var(--admin-text-soft)]">
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
            </article>
        </section>

        <section class="grid gap-4 xl:grid-cols-4">
            @foreach ($stats as $index => $card)
                @php($isPositive = $card['trend'] === 'positive')
                <article class="rounded-[1.7rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-5 shadow-[var(--admin-shadow)]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-[color:var(--admin-text-soft)]">{{ $card['label'] }}</p>
                            <p class="mt-4 text-[2.3rem] font-semibold leading-none text-[color:var(--admin-text)]">{{ $card['value'] }}</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-[1.2rem] {{ $isPositive ? 'bg-[color:var(--admin-primary-soft)] text-[color:var(--admin-positive)]' : 'bg-rose-400/10 text-[color:var(--admin-negative)]' }}">
                            @if ($index === 0)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22"></path><path d="m17 5-5-4-5 4"></path><path d="m17 19-5 4-5-4"></path></svg>
                            @elseif ($index === 1)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            @elseif ($index === 2)
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.6 12.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                            @else
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg>
                            @endif
                        </div>
                    </div>
                    <div class="mt-5 flex items-center gap-3 text-sm">
                        <span class="inline-flex rounded-full px-3 py-1 font-semibold {{ $isPositive ? 'bg-emerald-400/10 text-[color:var(--admin-positive)]' : 'bg-rose-400/10 text-[color:var(--admin-negative)]' }}">{{ $card['delta'] }}</span>
                        <span class="text-[color:var(--admin-text-soft)]">{{ $card['context'] }}</span>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="grid gap-5 xl:grid-cols-[1.12fr_0.88fr]">
            <article class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-2xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.dashboard.charts.revenue') }}</h2>
                    <span class="rounded-full bg-[color:var(--admin-primary-soft)] px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[color:var(--admin-primary)]">Live view</span>
                </div>
                <div class="mt-6 h-[360px] rounded-[1.7rem] border border-[color:var(--admin-border)] bg-[linear-gradient(180deg,rgba(79,124,255,0.12),rgba(79,124,255,0.02))] p-5">
                    <div class="relative h-full overflow-hidden rounded-[1.3rem] border border-dashed border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)]">
                        <div class="absolute inset-x-8 top-10 flex items-end gap-4 opacity-80">
                            @foreach ([74, 62, 40, 55, 38, 44, 63, 79, 71, 84, 92, 100] as $point)
                                <div class="flex-1">
                                    <div class="rounded-t-full bg-[color:var(--admin-primary)]/80" style="height: {{ $point }}%; min-height: 12px;"></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="absolute inset-x-0 bottom-0 h-32 bg-[linear-gradient(180deg,rgba(79,124,255,0),rgba(79,124,255,0.12))]"></div>
                        <div class="absolute left-6 right-6 top-6 h-px border-t border-dashed border-[color:var(--admin-border)]"></div>
                        <div class="absolute left-6 right-6 top-1/2 h-px border-t border-dashed border-[color:var(--admin-border)]"></div>
                        <div class="absolute left-6 right-6 bottom-12 h-px border-t border-dashed border-[color:var(--admin-border)]"></div>
                    </div>
                </div>
            </article>

            <article class="rounded-[2rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface)] p-6 shadow-[var(--admin-shadow)]">
                <h2 class="text-2xl font-semibold text-[color:var(--admin-text)]">{{ trans('admin.dashboard.charts.profit') }}</h2>
                <div class="mt-6 h-[360px] rounded-[1.7rem] border border-[color:var(--admin-border)] bg-[color:var(--admin-surface-soft)] p-5">
                    <div class="flex h-full items-end gap-3">
                        @foreach ([[22,12],[14,13],[68,8],[31,11],[39,13],[30,15],[35,17],[43,20],[33,16],[45,22],[51,24],[58,27]] as $bars)
                            <div class="flex flex-1 items-end justify-center gap-1.5">
                                <div class="w-full max-w-[18px] rounded-t-full bg-[color:var(--admin-primary)]" style="height: {{ $bars[0] }}%; min-height: 10px;"></div>
                                <div class="w-full max-w-[18px] rounded-t-full bg-slate-400/55" style="height: {{ $bars[1] }}%; min-height: 10px;"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>
        </section>
    </div>
@endsection
