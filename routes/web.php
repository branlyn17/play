<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\PublicTemplateInvitationController;
use App\Http\Controllers\PublicTemplateEditorController;
use App\Support\Localization\PublicPage;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/'.PublicPage::defaultLocale());

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/admin', DashboardController::class)
        ->middleware(['set.admin.locale', 'role:superadmin'])
        ->name('admin.dashboard');
});

foreach (PublicPage::supportedLocales() as $locale) {
    Route::prefix($locale)
        ->middleware('set.locale')
        ->group(function () use ($locale) {
            Route::get('/', PublicHomeController::class)->name(PublicPage::routeName('home', $locale));
            Route::get(PublicPage::slug('catalog', $locale), PublicCatalogController::class)->name(PublicPage::routeName('catalog', $locale));
            Route::get(PublicPage::slug('catalog', $locale).'/{slug}', PublicTemplateEditorController::class)->name(PublicPage::routeName('catalog.show', $locale));
            Route::post(PublicPage::slug('catalog', $locale).'/{slug}/save', [PublicTemplateInvitationController::class, 'store'])->name(PublicPage::routeName('catalog.save', $locale));
        });
}
