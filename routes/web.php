<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Tuki School PWA
|--------------------------------------------------------------------------
| All web routes serve the PWA shell. Client-side routing
| handles page navigation via Alpine.js.
*/

// Serve the PWA shell for all routes
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
