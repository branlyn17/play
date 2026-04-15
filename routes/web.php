<?php

use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\PublicHomeController;
use App\Support\Localization\PublicPage;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/'.PublicPage::defaultLocale());

foreach (PublicPage::supportedLocales() as $locale) {
    Route::prefix($locale)
        ->middleware('set.locale')
        ->group(function () use ($locale) {
            Route::get('/', PublicHomeController::class)->name(PublicPage::routeName('home', $locale));
            Route::get(PublicPage::slug('catalog', $locale), PublicCatalogController::class)->name(PublicPage::routeName('catalog', $locale));
        });
}
