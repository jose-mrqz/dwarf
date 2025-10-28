<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

// set public cache on client for 24 hours
Route::middleware('cache.headers:public;max_age=86400;etag')->group(function () {
    Route::get('/urls/{code}/redirect', [UrlController::class, 'redirect'])->name('urls.redirect');
});