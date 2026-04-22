<?php

namespace App\Support\Analytics;

use Illuminate\Support\Str;

class DeviceDetector
{
    public function detect(?string $userAgent): array
    {
        $userAgent = (string) $userAgent;
        $lower = Str::lower($userAgent);

        return [
            'device_type' => $this->deviceType($lower),
            'browser' => $this->browser($lower),
            'platform' => $this->platform($lower),
        ];
    }

    private function deviceType(string $userAgent): string
    {
        if (Str::contains($userAgent, ['ipad', 'tablet'])) {
            return 'tablet';
        }

        if (Str::contains($userAgent, ['mobile', 'iphone', 'android'])) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function browser(string $userAgent): string
    {
        return match (true) {
            Str::contains($userAgent, 'edg/') => 'Edge',
            Str::contains($userAgent, 'chrome/') && ! Str::contains($userAgent, 'edg/') => 'Chrome',
            Str::contains($userAgent, 'firefox/') => 'Firefox',
            Str::contains($userAgent, 'safari/') && ! Str::contains($userAgent, 'chrome/') => 'Safari',
            default => 'Unknown',
        };
    }

    private function platform(string $userAgent): string
    {
        return match (true) {
            Str::contains($userAgent, 'windows') => 'Windows',
            Str::contains($userAgent, ['iphone', 'ipad', 'ios']) => 'iOS',
            Str::contains($userAgent, 'android') => 'Android',
            Str::contains($userAgent, 'mac os') => 'macOS',
            Str::contains($userAgent, 'linux') => 'Linux',
            default => 'Unknown',
        };
    }
}
