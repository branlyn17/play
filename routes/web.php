<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TemplateCategoryController;
use App\Http\Controllers\Admin\TemplateCreateController;
use App\Http\Controllers\Admin\TemplateIndexController;
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
    Route::middleware(['set.admin.locale', 'role:superadmin'])
        ->prefix('/admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', DashboardController::class)->name('dashboard');

            Route::get('/templates', TemplateIndexController::class)->name('templates.index');
            Route::get('/templates/create', [TemplateCreateController::class, 'create'])->name('templates.create');
            Route::post('/templates', [TemplateCreateController::class, 'store'])->name('templates.store');
            Route::get('/templates/{template}/edit', [TemplateCreateController::class, 'edit'])->name('templates.edit');
            Route::put('/templates/{template}', [TemplateCreateController::class, 'update'])->name('templates.update');
            Route::get('/templates/{template}/download/html', [TemplateCreateController::class, 'downloadHtml'])->name('templates.download-html');
            Route::get('/templates/{template}/download/json', [TemplateCreateController::class, 'downloadJson'])->name('templates.download-json');

            Route::get('/template-categories', [TemplateCategoryController::class, 'index'])->name('template-categories.index');
            Route::get('/template-categories/create', [TemplateCategoryController::class, 'create'])->name('template-categories.create');
            Route::post('/template-categories', [TemplateCategoryController::class, 'store'])->name('template-categories.store');
            Route::get('/template-categories/{templateCategory}/edit', [TemplateCategoryController::class, 'edit'])->name('template-categories.edit');
            Route::put('/template-categories/{templateCategory}', [TemplateCategoryController::class, 'update'])->name('template-categories.update');
            Route::delete('/template-categories/{templateCategory}', [TemplateCategoryController::class, 'destroy'])->name('template-categories.destroy');
        });
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
