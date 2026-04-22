<?php

namespace App\Support\Analytics;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GeoLocationResolver
{
    public function resolve(Request $request): array
    {
        $countryCode = $this->cleanCountryCode(
            $request->headers->get('CF-IPCountry')
                ?: $request->headers->get('CloudFront-Viewer-Country')
                ?: $request->headers->get('X-Vercel-IP-Country')
                ?: $request->headers->get('X-Appengine-Country')
        );

        $regionCode = $this->cleanHeader(
            $request->headers->get('X-Vercel-IP-Country-Region')
                ?: $request->headers->get('CloudFront-Viewer-Country-Region')
                ?: $request->headers->get('X-Appengine-Region')
        );

        $city = $this->cleanHeader(
            $request->headers->get('X-Vercel-IP-City')
                ?: $request->headers->get('CloudFront-Viewer-City')
                ?: $request->headers->get('X-Appengine-City')
        );

        return [
            'country_code' => $countryCode,
            'country_name' => $countryCode ? $this->countryName($countryCode) : null,
            'region_code' => $regionCode,
            'region_name' => $regionCode,
            'city' => $city ? urldecode($city) : null,
            'timezone' => $this->cleanHeader(
                $request->headers->get('CloudFront-Viewer-Time-Zone')
                    ?: $request->headers->get('X-Vercel-IP-Timezone')
                    ?: $request->headers->get('X-Appengine-Timezone')
            ),
            'latitude' => $this->decimalHeader(
                $request->headers->get('CloudFront-Viewer-Latitude')
                    ?: $request->headers->get('X-Vercel-IP-Latitude')
            ),
            'longitude' => $this->decimalHeader(
                $request->headers->get('CloudFront-Viewer-Longitude')
                    ?: $request->headers->get('X-Vercel-IP-Longitude')
            ),
            'accuracy_radius_km' => null,
        ];
    }

    private function cleanCountryCode(?string $value): ?string
    {
        $value = Str::upper(trim((string) $value));

        if ($value === '' || $value === 'XX' || strlen($value) !== 2) {
            return null;
        }

        return $value;
    }

    private function cleanHeader(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' || Str::lower($value) === 'unknown' ? null : Str::limit($value, 180, '');
    }

    private function decimalHeader(?string $value): ?float
    {
        if ($value === null || ! is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }

    private function countryName(string $countryCode): string
    {
        return [
            'AR' => 'Argentina',
            'BO' => 'Bolivia',
            'BR' => 'Brazil',
            'CA' => 'Canada',
            'CL' => 'Chile',
            'CO' => 'Colombia',
            'ES' => 'Spain',
            'MX' => 'Mexico',
            'PE' => 'Peru',
            'US' => 'United States',
        ][$countryCode] ?? $countryCode;
    }
}
