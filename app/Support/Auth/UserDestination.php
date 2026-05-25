<?php

namespace App\Support\Auth;

use App\Models\User;
use App\Support\Localization\LocaleConfig;
use App\Support\Localization\PublicPage;

class UserDestination
{
    public static function publicHome(?string $locale = null): string
    {
        $resolvedLocale = $locale ?: LocaleConfig::current();

        return route(PublicPage::routeName('home', $resolvedLocale));
    }

    public static function for(User $user, ?string $fallback = null): string
    {
        if ($user->hasRole('superadmin')) {
            return route('admin.dashboard', ['locale' => LocaleConfig::current()]);
        }

        return $fallback ?: self::publicHome();
    }

    public static function authPayload(?User $user = null): array
    {
        $resolvedUser = $user ?: auth()->user();

        if (! $resolvedUser instanceof User) {
            return [
                'authenticated' => false,
                'loginUrl' => route('login', [
                    'locale' => LocaleConfig::current(),
                    'redirect' => request()->getRequestUri(),
                ]),
            ];
        }

        return [
            'authenticated' => true,
            'displayName' => $resolvedUser->display_name ?: $resolvedUser->name,
            'primaryRole' => $resolvedUser->getRoleNames()->first(),
            'dashboardUrl' => $resolvedUser->hasRole('superadmin')
                ? route('admin.dashboard', ['locale' => LocaleConfig::current()])
                : null,
            'logoutUrl' => route('logout', ['locale' => LocaleConfig::current()]),
        ];
    }

    public static function isSafePublicRedirect(?string $redirect): bool
    {
        return filled($redirect) && str_starts_with($redirect, '/');
    }
}
