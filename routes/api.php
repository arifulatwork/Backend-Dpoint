<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripCategoryController;
use App\Http\Controllers\AccommodationOfferController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PremiumController;

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

// Trips Public Routes
Route::controller(TripController::class)->group(function () {
    Route::get('/trips', 'index');
    Route::get('/trips/{id}', 'show');
});

// Trip Categories Public Routes
Route::controller(TripCategoryController::class)->group(function () {
    Route::get('/trip-categories', 'index');
    Route::get('/trip-categories/{id}', 'show');
});

Route::get('/accommodation-offers', [AccommodationOfferController::class, 'index']);

// Experiences - Public Read Endpoints
Route::controller(ExperienceController::class)->group(function () {
    Route::get('/experiences', 'index');
    Route::get('/experiences/{id}', 'show');
});

// Premium Routes (Public)
Route::controller(PremiumController::class)->prefix('premium')->group(function () {
    Route::get('/benefits', 'getBenefits');
    Route::get('/tiers', 'getPricingTiers');
    Route::get('/discounts', 'getSpecialDiscounts');
    Route::get('/properties', 'getRealEstateProperties');
});

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
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
        
        // Protected Trip Routes
        Route::controller(TripController::class)->group(function () {
            Route::post('/trips', 'store');
            Route::put('/trips/{id}', 'update');
            Route::delete('/trips/{id}', 'destroy');
        });
        
        // Protected Trip Category Routes
        Route::controller(TripCategoryController::class)->group(function () {
            Route::post('/trip-categories', 'store');
            Route::put('/trip-categories/{id}', 'update');
            Route::delete('/trip-categories/{id}', 'destroy');
        });
    });
});