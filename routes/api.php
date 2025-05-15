<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Hello World from Laravel on Vercel!',
        'status' => 'success',
        'php_version' => phpversion(), // Let's also see the PHP version Vercel is using
        'laravel_version' => app()->version()
    ]);
});

// You can comment out or remove any other routes you had for now
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('items', App\Http\Controllers\ItemController::class);
*/
