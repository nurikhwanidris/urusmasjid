<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrayerTimesController;
use Illuminate\Support\Facades\Http;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Prayer Times Routes
|--------------------------------------------------------------------------
|
| Prayer times API routes for the application.
|
*/

// Simple test route to verify that prayer routes are working
Route::get('/prayer-test', function () {
    return response()->json([
        'message' => 'Prayer routes are working!',
        'timestamp' => now()->toDateTimeString()
    ]);
})->name('prayer.test');

// API route for prayer times (using controller)
Route::get('/prayer-times/{zone}', [PrayerTimesController::class, 'getByZone'])
    ->name('api.prayer-times');
