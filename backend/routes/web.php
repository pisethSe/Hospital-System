<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| The SPA frontend is served separately (Vite dev server or built files),
| so the Laravel app only exposes the API. This route simply points
| visitors to the API health check.
|
*/

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'status' => 'ok',
        'docs' => url('/api/documentation'),
    ]);
});
