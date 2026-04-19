<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('locales.supported', []));
        $default = config('locales.default', 'es');
        $requested = $request->query('lang');

        if (is_string($requested) && in_array($requested, $supported, true)) {
            $request->session()->put('admin_locale', $requested);
        }

        $locale = $request->session()->get('admin_locale', $default);

        if (! in_array($locale, $supported, true)) {
            $locale = $default;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
