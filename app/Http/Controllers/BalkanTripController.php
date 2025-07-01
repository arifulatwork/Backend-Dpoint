<?php

namespace App\Http\Controllers;

use App\Models\BalkanTrip;
use Illuminate\Http\Request;

class BalkanTripController extends Controller
{
    // 🔹 GET /api/balkan-trips
    public function index()
    {
        $trips = BalkanTrip::all()->map(function ($trip) {
            return $this->normalizeTrip($trip);
        });

        return response()->json($trips);
    }

    // 🔹 GET /api/balkan-trips/{slug}
    public function show($slug)
    {
        $trip = BalkanTrip::where('slug', $slug)->first();

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        return response()->json($this->normalizeTrip($trip));
    }

    // 🔸 POST /api/balkan-trips
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:balkan_trips,slug',
            'description' => 'required|string',
            'duration' => 'required|string',
            'price' => 'required|numeric',
            'image_url' => 'required|string',
            'destinations' => 'required|array',
            'group_size' => 'required|array',
            'itinerary' => 'required|array',
            'included' => 'required|array',
            'not_included' => 'required|array',
        ]);

        $trip = BalkanTrip::create($validated);

        return response()->json($this->normalizeTrip($trip), 201);
    }

    // ✅ Helper to ensure JSON fields are parsed properly
    protected function normalizeTrip($trip)
    {
        return [
            'id' => $trip->id,
            'title' => $trip->title,
            'slug' => $trip->slug,
            'description' => $trip->description,
            'duration' => $trip->duration,
            'price' => $trip->price,
            'image_url' => $trip->image_url,
            'destinations' => is_array($trip->destinations) ? $trip->destinations : json_decode($trip->destinations, true),
            'group_size' => is_array($trip->group_size) ? $trip->group_size : json_decode($trip->group_size, true),
            'itinerary' => is_array($trip->itinerary) ? $trip->itinerary : json_decode($trip->itinerary, true),
            'included' => is_array($trip->included) ? $trip->included : json_decode($trip->included, true),
            'not_included' => is_array($trip->not_included) ? $trip->not_included : json_decode($trip->not_included, true),
        ];
    }
}
