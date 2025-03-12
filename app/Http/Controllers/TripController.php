<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $maxPrice = $request->query('maxPrice', PHP_INT_MAX);
        $selectedType = $request->query('type');
        $searchQuery = $request->query('search');

        $trips = Trip::query()
            ->when($selectedType, fn($query) => $query->where('type', $selectedType))
            ->where('price', '<=', $maxPrice)
            ->where(function ($query) use ($searchQuery) {
                $query->where('title', 'like', "%$searchQuery%")
                      ->orWhere('description', 'like', "%$searchQuery%");
            })
            ->get();

        return response()->json($trips);
    }

    public function show($id)
    {
        $trip = Trip::findOrFail($id);
        return response()->json($trip);
    }
}