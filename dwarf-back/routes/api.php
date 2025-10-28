<?php

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::apiResource('v1/urls', UrlController::class)->only(['index', 'store', 'show', 'destroy']);
Route::get('v1/urls/code/{code}', [UrlController::class, 'showByCode'])->name('urls.showByCode');
