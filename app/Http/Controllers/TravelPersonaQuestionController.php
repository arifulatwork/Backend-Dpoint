<?php

namespace App\Http\Controllers;

use App\Models\TravelPersonaQuestion;
use Illuminate\Http\JsonResponse;

class TravelPersonaQuestionController extends Controller
{
    // GET /api/travel-persona/questions
    public function index(): JsonResponse
    {
        $questions = TravelPersonaQuestion::with('options')
            ->orderBy('id') // or custom ordering column
            ->get();

        return response()->json($questions);
    }

    // GET /api/travel-persona/questions/{key}  (accepts string key or numeric id)
    public function show(string $key): JsonResponse
    {
        $question = TravelPersonaQuestion::with('options')
            ->where('key', $key)
            ->orWhere('id', $key)
            ->firstOrFail();

        return response()->json($question);
    }
}
