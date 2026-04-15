<?php

namespace App\Http\Controllers;

use App\Support\Localization\PublicViewData;
use Illuminate\View\View;

class PublicCatalogController extends Controller
{
    public function __invoke(): View
    {
        return view('public.catalog', PublicViewData::make('catalog'));
    }
}
