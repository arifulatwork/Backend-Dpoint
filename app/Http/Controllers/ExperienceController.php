<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Display a listing of all experiences.
     */
    public function index()
    {
        $experiences = Experience::all();
        return response()->json($experiences);
    }

    /**
     * Display a specific experience.
     */
    public function show($id)
    {
        $experience = Experience::findOrFail($id);
        return response()->json($experience);
    }

    /**
     * Store a newly created experience.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:food,music,craft',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews' => 'nullable|integer|min:0',
            'location' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'image' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'host' => 'required|array',
            'highlights' => 'nullable|array',
            'why_choose' => 'nullable|array',
        ]);

        $experience = Experience::create($validated);
        return response()->json($experience, 201);
    }

    /**
     * Update an existing experience.
     */
    public function update(Request $request, $id)
    {
        $experience = Experience::findOrFail($id);

        $validated = $request->validate([
            'type' => 'sometimes|required|in:food,music,craft',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'rating' => 'sometimes|nullable|numeric|min:0|max:5',
            'reviews' => 'sometimes|nullable|integer|min:0',
            'location' => 'sometimes|required|string|max:255',
            'duration' => 'sometimes|required|string|max:255',
            'max_participants' => 'sometimes|required|integer|min:1',
            'image' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'host' => 'sometimes|required|array',
            'highlights' => 'sometimes|nullable|array',
            'why_choose' => 'sometimes|nullable|array',
        ]);

        $experience->update($validated);
        return response()->json($experience, 200);
    }

    /**
     * Remove an experience.
     */
    public function destroy($id)
    {
        Experience::destroy($id);
        return response()->json(null, 204);
    }


    public function bookings()
    {
    return $this->hasMany(LocalTouchBooking::class);
    }

}
