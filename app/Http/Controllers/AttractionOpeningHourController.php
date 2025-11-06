<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\AttractionOpeningHour;
use Illuminate\Http\Request;

class AttractionOpeningHourController extends Controller
{
    /**
     * Display all opening hours for a specific attraction.
     */
    public function index($attractionId)
    {
        $attraction = Attraction::findOrFail($attractionId);
        return response()->json($attraction->openingHours);
    }

    /**
     * Store or update opening hours for a specific day.
     */
    public function store(Request $request, $attractionId)
    {
        $data = $request->validate([
            'day_of_week' => 'required|integer|min:0|max:6',
            'open_time'   => 'nullable|date_format:H:i',
            'close_time'  => 'nullable|date_format:H:i',
            'is_closed'   => 'boolean',
            'timezone'    => 'nullable|string|max:64',
        ]);

        $data['attraction_id'] = $attractionId;

        $hour = AttractionOpeningHour::updateOrCreate(
            ['attraction_id' => $attractionId, 'day_of_week' => $data['day_of_week']],
            $data
        );

        return response()->json(['message' => 'Opening hour saved', 'data' => $hour]);
    }

    /**
     * Delete an opening hour entry.
     */
    public function destroy($id)
    {
        $hour = AttractionOpeningHour::findOrFail($id);
        $hour->delete();

        return response()->json(['message' => 'Opening hour deleted']);
    }
}
