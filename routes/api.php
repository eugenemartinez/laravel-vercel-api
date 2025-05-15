<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; // Import DB Facade
use Illuminate\Support\Facades\Log; // Import Log Facade
use App\Http\Controllers\ItemController;

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
        'php_version' => phpversion(),
        'laravel_version' => app()->version()
    ]);
});

Route::get('/dbtest', function () { // Note: your file shows /dbtest, not /db-test
    $dbStatus = ['status' => 'unknown', 'message' => ''];
    try {
        DB::connection()->getPdo();
        $dbStatus['status'] = 'success';
        $dbStatus['message'] = 'Successfully connected to the database.';
    } catch (\Exception $e) {
        Log::error('DB Connection Error from /api/dbtest route: ' . $e->getMessage(), ['exception' => $e]);
        $dbStatus['status'] = 'error';
        $dbStatus['message'] = 'Could not connect to the database. Check logs for details. Error: ' . $e->getMessage();
    }
    return response()->json($dbStatus);
});

Route::get('/ping', function () {
    return response()->json([
        'message' => 'pong',
        'timestamp' => now()
    ]);
});

// API Resource routes for Items
Route::apiResource('items', ItemController::class);

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
