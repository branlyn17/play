<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PublicHomeController extends Controller
{
    public function __invoke(): View
    {
        return view('public.index', [
            'title' => config('app.name'),
            'metaDescription' => 'Invitaciones digitales modernas para eventos, plantillas y experiencias publicas memorables.',
            'page' => 'public-home',
            'props' => [
                'appName' => config('app.name'),
            ],
        ]);
    }
}
