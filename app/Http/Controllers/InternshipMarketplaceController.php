<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{
    InternshipLocation,
    InternshipField,
    InternshipCompany,
    InternshipService,
    InternshipCondition,
    InternshipApplication
};

class InternshipMarketplaceController extends Controller
{
    /* ============================
     *  LOOKUP ENDPOINTS
     * ============================ */

    public function locations()
    {
        return response()->json(
            InternshipLocation::all()->map(fn($loc) => [
                'id' => $loc->slug,
                'country' => $loc->country,
                'cities' => $loc->cities,
                'flag' => $loc->flag,
                'popular' => $loc->popular,
            ])
        );
    }

    public function fields()
    {
        return response()->json(
            InternshipField::all()->map(fn($f) => [
                'id' => $f->slug,
                'name' => $f->name,
                'description' => $f->description,
            ])
        );
    }

    public function services()
    {
        return response()->json(
            InternshipService::all()->map(fn($s) => [
                'id' => $s->slug,
                'name' => $s->name,
                'description' => $s->description,
                'price' => (float)$s->price,
                'originalPrice' => $s->original_price ? (float)$s->original_price : null,
                'popular' => $s->popular,
            ])
        );
    }

    public function conditions()
    {
        return response()->json(
            InternshipCondition::all()->map(fn($c) => [
                'id' => $c->slug,
                'text' => $c->text,
                'required' => $c->required,
            ])
        );
    }

    public function companies(Request $request)
    {
        $location = $request->query('location');
        $field = $request->query('field');

        $query = InternshipCompany::query();

        if ($location && $location !== 'all') {
            $query->where('location', 'LIKE', '%' . $location . '%');
        }

        if ($field && $field !== 'all') {
            $query->where('field_slug', $field);
        }

        return response()->json(
            $query->get()->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'logo' => $c->logo_url,
                'location' => $c->location,
                'field' => $c->field_slug,
                'rating' => (float)$c->rating,
                'reviews' => $c->reviews,
                'workMode' => $c->work_mode,
                'duration' => $c->duration,
                'hours' => $c->hours,
            ])
        );
    }

    /* ============================
     *  APPLICATION + PAYMENT
     * ============================ */

    public function apply(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'company_id' => 'required|exists:internship_companies,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'selected_services' => 'required|array|min:1',
            'accepted_conditions' => 'required|array|min:1',
            'cv' => 'required|mimes:pdf|max:5120', // 5MB
        ]);

        /* -----------------------
         * Validate Dates
         * ---------------------- */
        $start = new \DateTime($validated['start_date']);
        $end = new \DateTime($validated['end_date']);
        $today = new \DateTime();

        $minStart = (clone $today)->modify('+30 days');

        if ($start <= $today || $start < $minStart) {
            return response()->json(['message' => 'Start date must be at least 30 days from today'], 422);
        }

        $months = ($end->format('Y') - $start->format('Y')) * 12 +
                  ($end->format('m') - $start->format('m'));

        if ($months < 3)
            return response()->json(['message' => 'Internship must be at least 3 months'], 422);

        if ($months > 12)
            return response()->json(['message' => 'Internship cannot exceed 12 months'], 422);


        /* -----------------------
         * Validate Conditions
         * ---------------------- */
        $required = InternshipCondition::where('required', true)->pluck('slug')->toArray();

        foreach ($required as $slug) {
            if (!in_array($slug, $validated['accepted_conditions'])) {
                return response()->json(['message' => 'All required conditions must be accepted'], 422);
            }
        }


        /* -----------------------
         * Calculate Price
         * ---------------------- */
        $services = InternshipService::whereIn('slug', $validated['selected_services'])->get();

        if ($services->isEmpty()) {
            return response()->json(['message' => 'Invalid services selected'], 422);
        }

        $totalPrice = $services->sum('price');


        /* -----------------------
         * Store CV File
         * ---------------------- */
        $cvPath = $request->file('cv')->store('internship_cvs', 'public');


        /* -----------------------
         * Stripe PaymentIntent
         * ---------------------- */
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => intval($totalPrice * 100), // cents
            'currency' => 'eur',
            'metadata' => [
                'user_id' => $user->id,
                'company_id' => $validated['company_id'],
            ],
        ]);


        /* -----------------------
         * Save Application
         * ---------------------- */
        $application = InternshipApplication::create([
            'user_id' => $user->id,
            'company_id' => $validated['company_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration_months' => $months,
            'selected_services' => $validated['selected_services'],
            'accepted_conditions' => $validated['accepted_conditions'],
            'cv_path' => $cvPath,
            'total_price' => $totalPrice,
            'currency' => 'EUR',
            'stripe_payment_intent_id' => $paymentIntent->id,
            'status' => 'pending',
        ]);


        return response()->json([
            'message' => 'Application submitted',
            'application_id' => $application->id,
            'client_secret' => $paymentIntent->client_secret,
        ]);
    }

    /* -----------------------
     * List User Applications
     * ---------------------- */

    public function myApplications(Request $request)
    {
        return InternshipApplication::with('company')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();
    }
}
