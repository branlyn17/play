<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateMetricEvent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TemplateAnalyticsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'event_type' => ['nullable', Rule::in(['all', TemplateMetricEvent::TYPE_VIEW, TemplateMetricEvent::TYPE_USE, TemplateMetricEvent::TYPE_DOWNLOAD])],
            'country_code' => ['nullable', 'string', 'max:2'],
            'locale' => ['nullable', 'string', 'max:8'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $locale = app()->getLocale();
        $search = trim((string) ($validated['search'] ?? ''));
        $eventType = $validated['event_type'] ?? 'all';
        $countryCode = strtoupper((string) ($validated['country_code'] ?? ''));
        $selectedLocale = (string) ($validated['locale'] ?? '');
        $dateFrom = $validated['date_from'] ?? now()->subDays(30)->toDateString();
        $dateTo = $validated['date_to'] ?? now()->toDateString();

        $baseQuery = TemplateMetricEvent::query()
            ->whereBetween('occurred_at', [$dateFrom.' 00:00:00', $dateTo.' 23:59:59'])
            ->when($eventType !== 'all', fn ($query) => $query->where('event_type', $eventType))
            ->when($countryCode !== '', fn ($query) => $query->where('country_code', $countryCode))
            ->when($selectedLocale !== '', fn ($query) => $query->where('locale', $selectedLocale))
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('template', function ($templateQuery) use ($search) {
                    $templateQuery
                        ->where('code', 'like', "%{$search}%")
                        ->orWhereHas('translations', function ($translationQuery) use ($search) {
                            $translationQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%");
                        });
                });
            });

        $totals = (clone $baseQuery)
            ->selectRaw("SUM(CASE WHEN event_type = ? THEN 1 ELSE 0 END) as views", [TemplateMetricEvent::TYPE_VIEW])
            ->selectRaw("SUM(CASE WHEN event_type = ? THEN 1 ELSE 0 END) as uses", [TemplateMetricEvent::TYPE_USE])
            ->selectRaw("SUM(CASE WHEN event_type = ? THEN 1 ELSE 0 END) as downloads", [TemplateMetricEvent::TYPE_DOWNLOAD])
            ->selectRaw('COUNT(*) as total')
            ->first();

        $rows = (clone $baseQuery)
            ->select([
                'template_id',
                'locale',
                'country_code',
                'country_name',
                'region_code',
                'region_name',
                'city',
                DB::raw("SUM(CASE WHEN event_type = '".TemplateMetricEvent::TYPE_VIEW."' THEN 1 ELSE 0 END) as views"),
                DB::raw("SUM(CASE WHEN event_type = '".TemplateMetricEvent::TYPE_USE."' THEN 1 ELSE 0 END) as uses"),
                DB::raw("SUM(CASE WHEN event_type = '".TemplateMetricEvent::TYPE_DOWNLOAD."' THEN 1 ELSE 0 END) as downloads"),
                DB::raw('COUNT(*) as total'),
                DB::raw('MAX(occurred_at) as last_activity_at'),
            ])
            ->groupBy('template_id', 'locale', 'country_code', 'country_name', 'region_code', 'region_name', 'city')
            ->orderByDesc('total')
            ->orderByDesc('last_activity_at')
            ->paginate(15)
            ->withQueryString();

        $templates = Template::query()
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->whereIn('id', $rows->getCollection()->pluck('template_id')->filter()->unique())
            ->get()
            ->keyBy('id');

        $countries = TemplateMetricEvent::query()
            ->whereNotNull('country_code')
            ->select('country_code', 'country_name')
            ->distinct()
            ->orderBy('country_name')
            ->get();

        return view('admin.template-analytics.index', [
            'title' => trans('admin.template_analytics.title').' | Invita Plus',
            'rows' => $rows,
            'templates' => $templates,
            'countries' => $countries,
            'filters' => [
                'search' => $search,
                'event_type' => $eventType,
                'country_code' => $countryCode,
                'locale' => $selectedLocale,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
            'totals' => [
                'views' => (int) ($totals->views ?? 0),
                'uses' => (int) ($totals->uses ?? 0),
                'downloads' => (int) ($totals->downloads ?? 0),
                'total' => (int) ($totals->total ?? 0),
            ],
            'supportedLocales' => config('locales.supported', []),
        ]);
    }
}
