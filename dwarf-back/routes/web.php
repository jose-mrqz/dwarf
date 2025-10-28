<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

Route::get('/urls/{code}/redirect', [UrlController::class, 'redirect'])->name('urls.redirect');
