<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PublicCatalogController extends Controller
{
    public function __invoke(): View
    {
        return view('public.catalog', [
            'title' => config('app.name').' | Catalogo',
            'metaDescription' => 'Catalogo visual de invitaciones digitales con filtros y colecciones destacadas.',
            'page' => 'public-catalog',
            'props' => [
                'appName' => config('app.name'),
            ],
        ]);
    }
}
