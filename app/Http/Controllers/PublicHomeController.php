<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PublicHomeController extends Controller
{
    public function __invoke(): View
    {
        return view('public.index');
    }
}
