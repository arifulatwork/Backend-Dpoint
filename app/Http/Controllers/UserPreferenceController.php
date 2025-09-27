<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserPreferenceController extends Controller
{
    // assumes you store persona as JSON column on users table or a separate table
    // Example below uses users.travel_persona (json)

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'travel_persona' => $user->travel_persona ?? (object)[],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'travel_persona' => ['array'], // { key: value }
        ]);

        $user = $request->user();
        $user->travel_persona = $data['travel_persona'] ?? [];
        $user->save();

        return response()->json([
            'message' => 'Saved',
            'travel_persona' => $user->travel_persona,
        ], 201);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'travel_persona' => ['array'],
        ]);

        $user = $request->user();
        // merge or replace — choose one. Here we replace for simplicity:
        $user->travel_persona = $data['travel_persona'] ?? [];
        $user->save();

        return response()->json([
            'message' => 'Updated',
            'travel_persona' => $user->travel_persona,
        ]);
    }
}
