<?php

namespace App\Providers;

use App\Support\Localization\JsonFallbackTranslator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('translator', function ($translator, $app) {
            $custom = new JsonFallbackTranslator($translator->getLoader(), $translator->getLocale());
            $custom->setFallback($translator->getFallback());

            return $custom;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, string $ability) {
            return method_exists($user, 'hasRole') && $user->hasRole('superadmin') ? true : null;
        });
    }
}
