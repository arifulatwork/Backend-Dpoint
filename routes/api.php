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
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\UserPreferenceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and are assigned
| to the "api" middleware group.
|
*/

// Public Routes
Route::controller(DestinationController::class)->group(function () {
    Route::get('/destinations', 'index');
    Route::get('/destinations/{id}', 'show');
});

Route::controller(TripController::class)->group(function () {
    Route::get('/trips', 'index');
    Route::get('/trips/{id}', 'show');
});

Route::controller(TripCategoryController::class)->group(function () {
    Route::get('/trip-categories', 'index');
    Route::get('/trip-categories/{id}', 'show');
});

Route::get('/accommodation-offers', [AccommodationOfferController::class, 'index']);

Route::controller(ExperienceController::class)->group(function () {
    Route::get('/experiences', 'index');
    Route::get('/experiences/{id}', 'show');
});

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

        // ✅ Credit Card Routes
        Route::controller(CreditCardController::class)->group(function () {
            Route::get('/credit-cards', 'index');
            Route::post('/credit-cards', 'store');
            Route::put('/credit-cards/{id}/default', 'setDefault');
            Route::delete('/credit-cards/{id}', 'destroy');
        });

        // ✅ User Preferences Routes (Travel Persona)
        Route::controller(UserPreferenceController::class)->group(function () {
            Route::get('/user-preferences', 'show');
            Route::post('/user-preferences', 'store');
            Route::put('/user-preferences', 'update');
        });
    });
});
