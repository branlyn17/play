<?php

use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\PublicHomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicHomeController::class)->name('public.home');
Route::get('/catalogo', PublicCatalogController::class)->name('public.catalog');
