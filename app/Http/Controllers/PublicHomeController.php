<?php

namespace App\Http\Controllers;

use App\Support\Catalog\PublicHomeFeaturedSlides;
use App\Support\Localization\PublicPage;
use App\Support\Localization\PublicViewData;
use Illuminate\View\View;

class PublicHomeController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('public.index', PublicViewData::make('home', [
            'featuredSlides' => PublicHomeFeaturedSlides::make($locale),
            'catalogHref' => route(PublicPage::routeName('catalog', $locale)),
        ]));
    }
}
