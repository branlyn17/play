<?php

namespace App\Http\Middleware;

use App\Support\Localization\LocaleConfig;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestedLocale = $request->route('locale') ?: $request->segment(1);
        $locale = is_string($requestedLocale) && LocaleConfig::has($requestedLocale)
            ? $requestedLocale
            : LocaleConfig::default();

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
