<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\AccommodationOfferController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\AuthController;

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

// Public Routes (No Authentication Required)
Route::controller(DestinationController::class)->group(function () {
    Route::get('/destinations', 'index');
    Route::get('/destinations/{id}', 'show');
});

Route::controller(TripController::class)->group(function () {
    Route::get('/trips', 'index');
    Route::get('/trips/{id}', 'show');
});

Route::get('/accommodation-offers', [AccommodationOfferController::class, 'index']);

// Experiences - Public Read Endpoints
Route::controller(ExperienceController::class)->group(function () {
    Route::get('/experiences', 'index');
    Route::get('/experiences/{id}', 'show');
});

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']); // Changed from GET to POST
    
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        
        // Protected Experience Routes
        Route::controller(ExperienceController::class)->group(function () {
            Route::post('/experiences', 'store');
            Route::put('/experiences/{id}', 'update');
            Route::delete('/experiences/{id}', 'destroy');
        });
        
        // Other protected routes can be added here
        // Route::apiResource('trips', TripController::class)->except(['index', 'show']);
    });
});