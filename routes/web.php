<?php

use App\Http\Controllers\PublicHomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicHomeController::class)->name('public.home');
