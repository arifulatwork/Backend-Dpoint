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
use App\Http\Controllers\AccommodationAppointmentController;
use App\Http\Controllers\AttractionOpeningHourController;

// NEW (generic Tours system)
use App\Http\Controllers\TourController;
use App\Http\Controllers\TourBookingController;
use App\Http\Controllers\TourStripeWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and are assigned
| to the "api" middleware group.
|
*/

/* ------------------------- PUBLIC ROUTES ------------------------- */

// Public: destinations
Route::controller(DestinationController::class)->group(function () {
    Route::get('/destinations', 'index');
    Route::get('/destinations/{id}', 'show');
});

// Public: create accommodation appointment
Route::post('/appointments', [AccommodationAppointmentController::class, 'store']);

// Public: read opening hours for an attraction
Route::get('/attractions/{attraction}/opening-hours', [AttractionOpeningHourController::class, 'index']);

// Public: internships (listing)
Route::prefix('internships')->group(function () {
    Route::get('/',        [InternshipController::class, 'index']);   // list with filters
    Route::get('/options', [InternshipController::class, 'options']); // filter options
    Route::get('/{id}',    [InternshipController::class, 'show']);    // single
});

// Public: travel persona questions
Route::prefix('travel-persona')->group(function () {
    Route::get('/questions', [TravelPersonaQuestionController::class, 'index']);
    Route::get('/questions/{key}', [TravelPersonaQuestionController::class, 'show']);
});

// Public: Balkan trips (existing)
Route::get('/balkan-trips', [BalkanTripController::class, 'index']);
Route::get('/balkan-trips/{slug}', [BalkanTripController::class, 'show']);

// Public: generic Trips (existing)
Route::controller(TripController::class)->group(function () {
    Route::get('/trips', 'index');
    Route::get('/trips/{id}', 'show');
});

// Public: Trip Categories (existing)
Route::controller(TripCategoryController::class)->group(function () {
    Route::get('/trip-categories', 'index');
    Route::get('/trip-categories/{id}', 'show');
});

// Public: Accommodation offers (existing)
Route::get('/accommodation-offers', [AccommodationOfferController::class, 'index']);

// Public: Experiences (existing)
Route::controller(ExperienceController::class)->group(function () {
    Route::get('/experiences', 'index');
    Route::get('/experiences/{id}', 'show');
});

// Public: Premium (existing)
Route::controller(PremiumController::class)->prefix('premium')->group(function () {
    Route::get('/benefits', 'getBenefits');
    Route::get('/tiers', 'getPricingTiers');
    Route::get('/discounts', 'getSpecialDiscounts');
    Route::get('/properties', 'getRealEstateProperties');
});

// ---------------- NEW: PUBLIC TOURS (generic) ----------------
Route::get('/tours',        [TourController::class, 'index']);          // optional ?category=montenegro|balkan|spain
Route::get('/tours/{slug}', [TourController::class, 'show']);

// (Optional) backward-compatible public endpoints for Montenegro list/show
Route::get('/montenegro-tours',        [TourController::class, 'indexMontenegro']);
Route::get('/montenegro-tours/{slug}', [TourController::class, 'showMontenegro']);

// ---------------- NEW: STRIPE WEBHOOK (PUBLIC, NO AUTH) ----------------
Route::post('/stripe/tour/webhook', [TourStripeWebhookController::class, 'handle']);

/* ---------------------- AUTHENTICATION ROUTES ---------------------- */

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    // Password reset (public)
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
    Route::post('/auth/reset-password',  [AuthController::class, 'resetPassword']);

    /* -------------------- PROTECTED ROUTES (SANCTUM) -------------------- */
    Route::middleware('auth:sanctum')->group(function () {

        // session/user
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Student Intake (existing)
        Route::controller(App\Http\Controllers\StudentIntakeController::class)
            ->prefix('student-intake')
            ->group(function () {
                Route::post('/initiate', 'initiate');       // create PaymentIntent + save form (pending)
                Route::get('/status/{submission}', 'status'); // check payment + submission status
            });

        // Internships: enrollment + payment (existing)
        Route::prefix('internships')->group(function () {
            Route::post('/enroll/create-payment-intent', [InternshipEnrollmentController::class, 'createPaymentIntent']);
            Route::post('/enroll/confirm',               [InternshipEnrollmentController::class, 'confirm']); // if using PI flow
            Route::get('/enrolled-ids',                  [InternshipEnrollmentController::class, 'enrolledIds']);
            Route::get('/{id}/enrollment',               [InternshipEnrollmentController::class, 'enrollmentDetails']);
        });

        // LocalTouch booking (existing)
        Route::controller(LocalTouchBookingController::class)->prefix('localtouch')->group(function () {
            Route::post('/book', 'bookAndPay'); // booking + create Stripe payment intent
            Route::get('/experiences', 'experiencesWithBookingStatus');
        });

        // Trip Booking (existing)
        Route::controller(TripBookingController::class)->prefix('trip')->group(function () {
            Route::post('/{slug}/book',        'book');               // Book the trip
            Route::post('/payment/create',     'createPaymentIntent'); // Create Stripe Payment Intent
            Route::post('/payment/confirm',    'confirmPayment');      // Confirm Payment
            Route::get('/booked',              'bookedTrips');
            Route::get('/{slug}/booking-details', 'bookingDetails');   // fetch booking details
        });

        // Destination/Attraction booking (existing)
        Route::controller(App\Http\Controllers\AttractionBookingController::class)
            ->prefix('attraction')->group(function () {
                Route::post('/book/{id}', 'book');
                Route::post('/payment/create', 'createPaymentIntent');
                Route::post('/payment/confirm', 'confirmPayment');
                Route::get('/my-bookings', 'myBookings');
            });

        // Balkan trip booking (legacy)
        Route::controller(BalkanTripBookingController::class)
            ->prefix('balkan-trip')
            ->group(function () {
                Route::post('/book', 'createPaymentIntent');
                Route::post('/payment/confirm', 'confirmPayment');
                Route::get('/my-bookings', 'myBookings');
                Route::get('/auth/balkan-trip/booking/{id}','show');
            });

        // Montenegro tour booking (legacy)
        Route::controller(MontenegroTourBookingController::class)
            ->prefix('montenegro-tour')
            ->group(function () {
                Route::post('/book', 'createPaymentIntent');
                Route::post('/payment/confirm', 'confirmPayment');
                Route::get('/my-bookings', 'myBookings');
                Route::get('/auth/montenegro-tour/booking/{id}', 'show');
            });

        // Petra tour booking (legacy)
        Route::controller(PetraTourBookingController::class)
            ->prefix('petra-tour')
            ->group(function () {
                Route::post('/book', 'createPaymentIntent');
                Route::post('/payment/confirm', 'confirmPayment');
                Route::get('/my-bookings', 'myBookings');
                Route::get('/auth/petra-tour/booking/{id}', 'show');
            });

        // ---------------- NEW: GENERIC TOUR BOOKING ----------------
        Route::controller(TourBookingController::class)->prefix('tour')->group(function () {
            Route::post('/book',       'createPaymentIntent'); // body: { tour_id, travelers?, start_date?, notes? }
            Route::post('/confirm',    'confirmPayment');      // optional if relying on webhooks
            Route::get('/my-bookings', 'myBookings');
            Route::get('/bookings/{id}', 'show');
        });

        // ---------------- NEW: LEGACY COMPAT FOR FRONTEND ----------------
        // Your React currently uses /api/auth/montenegro-trip/...; keep it working.
        Route::controller(TourBookingController::class)->prefix('montenegro-trip')->group(function () {
            Route::post('/book',         'createPaymentIntentMontenegro'); // maps montenegro_tour_id -> tour_id
            Route::get('/my-bookings',   'myBookingsMontenegro');
            Route::get('/booking/{id}',  'showMontenegro');
        });

        // Notifications (existing)
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications', [NotificationController::class, 'store']);

        // Subscriptions (existing)
        Route::post('/subscriptions', [SubscriptionController::class, 'store']);
        Route::get('/subscriptions/status', [SubscriptionController::class, 'status']);

        // Protected Experiences CRUD (existing)
        Route::controller(ExperienceController::class)->group(function () {
            Route::post('/experiences', 'store');
            Route::put('/experiences/{id}', 'update');
            Route::delete('/experiences/{id}', 'destroy');
        });

        // Protected Trips CRUD (existing)
        Route::controller(TripController::class)->group(function () {
            Route::post('/trips', 'store');
            Route::put('/trips/{id}', 'update');
            Route::delete('/trips/{id}', 'destroy');
        });

        // Protected Trip Categories CRUD (existing)
        Route::controller(TripCategoryController::class)->group(function () {
            Route::post('/trip-categories', 'store');
            Route::put('/trip-categories/{id}', 'update');
            Route::delete('/trip-categories/{id}', 'destroy');
        });

        // Credit Cards (existing)
        Route::controller(CreditCardController::class)->group(function () {
            Route::get('/credit-cards', 'index');
            Route::post('/credit-cards', 'store');
            Route::put('/credit-cards/{id}/default', 'setDefault');
            Route::delete('/credit-cards/{id}', 'destroy');
        });

        // Payments (existing)
        Route::post('/payments/charge', [PaymentController::class, 'charge']);

        // User Preferences (existing)
        Route::controller(UserPreferenceController::class)->group(function () {
            Route::get('/user-preferences', 'show');
            Route::post('/user-preferences', 'store');
            Route::put('/user-preferences', 'update');
        });

        // Network & Messaging (existing)
        Route::controller(NetworkController::class)->group(function () {
            Route::get('/network/users', 'index');                         // find users
            Route::post('/network/request', 'sendConnectionRequest');      // send request
            Route::post('/network/request/respond/{id}', 'respondToRequest'); // accept/reject
            Route::get('/network/connections', 'myConnections');           // accepted connections
            Route::get('/messages/{userId}', 'getMessages');               // messages with user
            Route::post('/messages/send', 'sendMessage');                  // send message
        });
    });
});
