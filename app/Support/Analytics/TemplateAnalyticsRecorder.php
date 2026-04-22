<?php

namespace App\Support\Analytics;

use App\Models\Invitation;
use App\Models\Template;
use App\Models\TemplateMetricDailySummary;
use App\Models\TemplateMetricEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TemplateAnalyticsRecorder
{
    public function __construct(
        private readonly GeoLocationResolver $locationResolver,
        private readonly DeviceDetector $deviceDetector,
    ) {}

    public function record(Template $template, string $eventType, ?Invitation $invitation = null): void
    {
        if (! config('analytics.enabled', true)) {
            return;
        }

        $request = app()->bound('request') ? request() : null;
        $occurredAt = now();
        $location = $request instanceof Request ? $this->locationResolver->resolve($request) : $this->emptyLocation();
        $device = $request instanceof Request ? $this->deviceDetector->detect($request->userAgent()) : $this->emptyDevice();
        $locale = $invitation?->locale ?: app()->getLocale();

        DB::transaction(function () use ($template, $invitation, $eventType, $locale, $request, $location, $device, $occurredAt) {
            TemplateMetricEvent::create(array_merge([
                'template_id' => $template->getKey(),
                'invitation_id' => $invitation?->getKey(),
                'event_type' => $eventType,
                'locale' => $locale,
                'ip_hash' => $this->ipHash($request),
                'ip_version' => $this->ipVersion($request?->ip()),
                'user_agent_hash' => $this->userAgentHash($request),
                'referrer' => $this->limitNullable($request?->headers->get('referer'), 2000),
                'utm_source' => $this->limitNullable($request?->query('utm_source'), 255),
                'utm_medium' => $this->limitNullable($request?->query('utm_medium'), 255),
                'utm_campaign' => $this->limitNullable($request?->query('utm_campaign'), 255),
                'utm_content' => $this->limitNullable($request?->query('utm_content'), 255),
                'utm_term' => $this->limitNullable($request?->query('utm_term'), 255),
                'metadata' => [
                    'route' => $request?->route()?->getName(),
                    'path' => $request?->path(),
                ],
                'occurred_at' => $occurredAt,
            ], $location, $device));

            $summary = TemplateMetricDailySummary::firstOrCreate([
                'template_id' => $template->getKey(),
                'metric_date' => $occurredAt->toDateString(),
                'event_type' => $eventType,
                'locale' => (string) $locale,
                'country_code' => $location['country_code'] ?? '',
                'region_code' => $location['region_code'] ?? '',
                'city' => $location['city'] ?? '',
            ]);

            $summary->increment('total');
        });
    }

    private function emptyLocation(): array
    {
        return [
            'country_code' => null,
            'country_name' => null,
            'region_code' => null,
            'region_name' => null,
            'city' => null,
            'timezone' => null,
            'latitude' => null,
            'longitude' => null,
            'accuracy_radius_km' => null,
        ];
    }

    private function emptyDevice(): array
    {
        return [
            'device_type' => null,
            'browser' => null,
            'platform' => null,
        ];
    }

    private function ipHash(?Request $request): ?string
    {
        $ip = $request?->ip();

        if (! $ip) {
            return null;
        }

        return hash_hmac('sha256', $ip, (string) config('analytics.ip_hash_salt'));
    }

    private function ipVersion(?string $ip): ?int
    {
        if (! $ip) {
            return null;
        }

        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 6 : 4;
    }

    private function userAgentHash(?Request $request): ?string
    {
        $userAgent = $request?->userAgent();

        if (! $userAgent) {
            return null;
        }

        return hash('sha256', $userAgent);
    }

    private function limitNullable(mixed $value, int $limit): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : Str::limit($value, $limit, '');
    }
}
