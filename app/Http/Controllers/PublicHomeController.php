<?php

namespace App\Http\Controllers;

use App\Support\Localization\PublicViewData;
use Illuminate\View\View;

class PublicHomeController extends Controller
{
    public function __invoke(): View
    {
        return view('public.index', PublicViewData::make('home'));
    }
}
