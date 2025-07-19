<?php

namespace App\Http\Controllers;

use App\Models\MontenegroTour;
use Illuminate\Http\Request;

class MontenegroTourController extends Controller
{
    // 🔹 GET /api/montenegro-tours
    public function index()
    {
        $tours = MontenegroTour::all()->map(function ($tour) {
            return $this->normalizeTour($tour);
        });

        return response()->json($tours);
    }

    // 🔹 GET /api/montenegro-tours/{slug}
    public function show($slug)
    {
        $tour = MontenegroTour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }

        return response()->json($this->normalizeTour($tour));
    }

    // 🔸 POST /api/montenegro-tours
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:montenegro_tours,slug',
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

        $tour = MontenegroTour::create($validated);

        return response()->json($this->normalizeTour($tour), 201);
    }

    protected function normalizeTour($tour)
    {
        return [
            'id' => $tour->id,
            'title' => $tour->title,
            'slug' => $tour->slug,
            'description' => $tour->description,
            'duration' => $tour->duration,
            'price' => $tour->price,
            'image_url' => $tour->image_url,
            'destinations' => is_array($tour->destinations) ? $tour->destinations : json_decode($tour->destinations, true),
            'group_size' => is_array($tour->group_size) ? $tour->group_size : json_decode($tour->group_size, true),
            'itinerary' => is_array($tour->itinerary) ? $tour->itinerary : json_decode($tour->itinerary, true),
            'included' => is_array($tour->included) ? $tour->included : json_decode($tour->included, true),
            'not_included' => is_array($tour->not_included) ? $tour->not_included : json_decode($tour->not_included, true),
        ];
    }
}
