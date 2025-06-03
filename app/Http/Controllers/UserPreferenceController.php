<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    // Show the current preferences
    public function show()
    {
        $preference = Auth::user()->preferences;

        return response()->json($preference ?? ['message' => 'No preferences set yet.']);
    }

    // Store new preferences
    public function store(Request $request)
    {
        $request->validate([
            'travel_persona' => 'required|array',
        ]);

        $existing = Auth::user()->preferences;

        if ($existing) {
            return response()->json(['message' => 'Preferences already exist. Use update instead.'], 400);
        }

        $preference = new UserPreference([
            'travel_persona' => $request->travel_persona,
        ]);

        Auth::user()->preferences()->save($preference);

        return response()->json(['message' => 'Preferences saved successfully', 'preferences' => $preference]);
    }

    // Update existing preferences
    public function update(Request $request)
    {
        $request->validate([
            'travel_persona' => 'required|array',
        ]);

        $preference = Auth::user()->preferences;

        if (!$preference) {
            return response()->json(['message' => 'Preferences not found.'], 404);
        }

        $preference->update([
            'travel_persona' => $request->travel_persona,
        ]);

        return response()->json(['message' => 'Preferences updated successfully', 'preferences' => $preference]);
    }
}
