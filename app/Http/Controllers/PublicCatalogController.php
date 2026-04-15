<?php

namespace App\Http\Controllers;

use App\Support\Localization\PublicPage;
use Illuminate\View\View;

class PublicCatalogController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('public.catalog', [
            'title' => config('app.name').' | Catalogo',
            'metaDescription' => 'Catalogo visual de invitaciones digitales con filtros y colecciones destacadas.',
            'page' => 'public-catalog',
            'props' => [
                'appName' => config('app.name'),
                'locale' => $locale,
                'locales' => PublicPage::localeOptions('catalog'),
                'navigation' => PublicPage::navigation($locale, 'catalog'),
            ],
        ]);
    }
}
