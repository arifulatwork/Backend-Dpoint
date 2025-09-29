<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalkanTripController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripCategoryController;
use App\Http\Controllers\AccommodationOfferController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LocalTouchBookingController;
use App\Http\Controllers\TripBookingController;
use App\Http\Controllers\BalkanTripBookingController;
use App\Http\Controllers\PetraTourBookingController;
use App\Http\Controllers\MontenegroTourBookingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\TravelPersonaQuestionController;
use App\Http\Controllers\InternshipEnrollmentController;



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

Route::prefix('internships')->group(function () {
    Route::get('/',        [InternshipController::class, 'index']);   // list with filters
    Route::get('/options', [InternshipController::class, 'options']); // filter options
    Route::get('/{id}',    [InternshipController::class, 'show']);    // single
});

// Public: Questions + Options
Route::prefix('travel-persona')->group(function () {
    Route::get('/questions', [TravelPersonaQuestionController::class, 'index']);
    Route::get('/questions/{key}', [TravelPersonaQuestionController::class, 'show']);
});

// Authenticated: Persist user’s persona answers
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user-preferences',  [UserPreferenceController::class, 'show']);
    Route::post('/user-preferences', [UserPreferenceController::class, 'store']);
    Route::put('/user-preferences',  [UserPreferenceController::class, 'update']);
});




Route::get('/balkan-trips', [BalkanTripController::class, 'index']);
Route::get('/balkan-trips/{slug}', [BalkanTripController::class, 'show']);

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
    // 🔐 Add this below login/register, still outside Sanctum
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

         // ---- Internships: enrollment + payment ----
        Route::prefix('internships')->group(function () {
            Route::post('/enroll/create-payment-intent', [InternshipEnrollmentController::class, 'createPaymentIntent']);
            Route::post('/enroll/confirm', [InternshipEnrollmentController::class, 'confirm']); // only if using PI flow
            // ✅ NEW: Get all internship IDs the user has successfully enrolled in
            Route::get('/enrolled-ids', [InternshipEnrollmentController::class, 'enrolledIds']);

            // (optional) details for one internship’s enrollment
            Route::get('/{id}/enrollment', [InternshipEnrollmentController::class, 'enrollmentDetails']);

        });

        Route::controller(LocalTouchBookingController::class)->prefix('localtouch')->group(function () {
        Route::post('/book', 'bookAndPay'); // booking + create Stripe payment intent
        Route::get('/experiences', 'experiencesWithBookingStatus');
        });

        // ✅ Trip Booking and Payment Routes
        Route::controller(TripBookingController::class)->prefix('trip')->group(function () {
        Route::post('/{slug}/book', 'book'); // Book the trip
        Route::post('/payment/create', 'createPaymentIntent'); // Create Stripe Payment Intent
        Route::post('/payment/confirm', 'confirmPayment');     // Confirm Payment
        Route::get('/booked', 'bookedTrips');
        Route::get('/{slug}/booking-details', 'bookingDetails'); // ✅ NEW: fetch booking details
        });

    //Destination PaymentBook
    Route::controller(App\Http\Controllers\AttractionBookingController::class)
    ->prefix('attraction')->group(function () {
        Route::post('/book/{id}', 'book');
        Route::post('/payment/create', 'createPaymentIntent');
        Route::post('/payment/confirm', 'confirmPayment');
        Route::get('/my-bookings', 'myBookings');
    });


   Route::controller(BalkanTripBookingController::class)
    ->prefix('balkan-trip')
    ->group(function () {
        Route::post('/book', 'createPaymentIntent');
        Route::post('/payment/confirm', 'confirmPayment');
        Route::get('/my-bookings', 'myBookings');
        Route::get('/auth/balkan-trip/booking/{id}','show');
    });

    Route::controller(MontenegroTourBookingController::class)
    ->prefix('montenegro-tour')
    ->group(function () {
        Route::post('/book', 'createPaymentIntent');
        Route::post('/payment/confirm', 'confirmPayment');
        Route::get('/my-bookings', 'myBookings');
        Route::get('/auth/montenegro-tour/booking/{id}', 'show');
    });

    Route::controller(PetraTourBookingController::class)
    ->prefix('petra-tour')
    ->group(function () {
        Route::post('/book', 'createPaymentIntent');
        Route::post('/payment/confirm', 'confirmPayment');
        Route::get('/my-bookings', 'myBookings');
        Route::get('/auth/petra-tour/booking/{id}', 'show');
    });


    // Notification
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
});


        // Subscription Routes
        Route::post('/subscriptions', [SubscriptionController::class, 'store']);
        Route::get('/subscriptions/status', [SubscriptionController::class, 'status']);


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

        // Payment Routes
        Route::post('/payments/charge', [PaymentController::class, 'charge']);
        

        // ✅ User Preferences Routes (Travel Persona)
        Route::controller(UserPreferenceController::class)->group(function () {
            Route::get('/user-preferences', 'show');
            Route::post('/user-preferences', 'store');
            Route::put('/user-preferences', 'update');
        });

        // ✅ Network and Messaging Routes
        Route::controller(NetworkController::class)->group(function () {
            // 🔍 Find users to connect with
            Route::get('/network/users', 'index');

            // 🤝 Send a connection request
            Route::post('/network/request', 'sendConnectionRequest');

            // ✅ Respond to a connection request (accept/reject)
            Route::post('/network/request/respond/{id}', 'respondToRequest');

            // 👥 Get all your accepted connections
            Route::get('/network/connections', 'myConnections');

            // 💬 Get all messages with a specific user
            Route::get('/messages/{userId}', 'getMessages');

            // ✉️ Send a message
            Route::post('/messages/send', 'sendMessage');
        });
    });
});