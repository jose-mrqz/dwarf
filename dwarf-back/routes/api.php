<?php

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::apiResource('urls', UrlController::class)->only(['index', 'store', 'show', 'destroy']);
Route::get('urls/code/{code}', [UrlController::class, 'showByCode'])->name('urls.showByCode');
