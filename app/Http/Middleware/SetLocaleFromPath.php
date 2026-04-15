<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromPath
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);
        $supported = array_keys(config('locales.supported', []));

        if (in_array($locale, $supported, true)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale(config('locales.default', config('app.locale')));
        }

        return $next($request);
    }
}
