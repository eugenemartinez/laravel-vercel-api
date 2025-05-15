<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController; // Ensure this line is present or use App\Http\Controllers\Api\ItemController if you placed it in an Api subfolder

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

// Uncomment this line to enable your Item CRUD routes
Route::apiResource('items', ItemController::class); // Or App\Http\Controllers\Api\ItemController::class if it's in a subfolder

// Optional: A simple database connection test route
Route::get('/db-test', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return response()->json(['message' => 'Successfully connected to the database.']);
    } catch (\Exception $e) {
        // Log the detailed error for server-side inspection
        \Illuminate\Support\Facades\Log::error('DB Connection Error: ' . $e->getMessage(), ['exception' => $e]);
        return response()->json(['message' => 'Could not connect to the database. Please check your configuration.'], 500);
    }
});

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
