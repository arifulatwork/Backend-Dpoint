<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TripController extends Controller
{
    /**
     * Display a listing of the trips with optional filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $query = Trip::with('category');
            
            // Filter by max price
            if ($request->has('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }
            
            // Filter by category
            if ($request->has('category')) {
                $query->where('category_id', $request->category);
            }
            
            // Search by title or description
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
                });
            }
            
            // Pagination (optional)
            $perPage = $request->has('per_page') ? $request->per_page : 15;
            $trips = $query->paginate($perPage);
            
            return response()->json($trips);
            
        } catch (\Exception $e) {
            Log::error('Error fetching trips: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching trips'], 500);
        }
    }

    /**
     * Store a newly created trip in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:trip_categories,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|unique:trips',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'original_price' => 'required|numeric|min:0',
                'discount_percentage' => 'required|integer|min:0|max:100',
                'image_url' => 'required|url',
                'duration_days' => 'required|integer|min:1',
                'max_participants' => 'nullable|integer|min:1',
                'highlights' => 'nullable|array',
                'highlights.*.day' => 'required_with:highlights|integer',
                'highlights.*.activities' => 'required_with:highlights|array',
                'highlights.*.activities.*.time' => 'required_with:highlights.*.activities|string',
                'highlights.*.activities.*.activity' => 'required_with:highlights.*.activities|string',
                'highlights.*.activities.*.description' => 'required_with:highlights.*.activities|string',
                'learning_outcomes' => 'nullable|array',
                'personal_development' => 'nullable|array',
                'certifications' => 'nullable|array',
                'environmental_impact' => 'nullable|array',
                'community_benefits' => 'nullable|array',
            ]);

            $trip = Trip::create($validated);
            
            return response()->json($trip, 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating trip: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating trip'], 500);
        }
    }

    /**
     * Display the specified trip.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $trip = Trip::with('category')->findOrFail($id);
            return response()->json($trip);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Trip not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching trip: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching trip'], 500);
        }
    }

    /**
     * Update the specified trip in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $trip = Trip::findOrFail($id);
            
            $validated = $request->validate([
                'category_id' => 'sometimes|exists:trip_categories,id',
                'title' => 'sometimes|string|max:255',
                'slug' => ['sometimes', 'string', Rule::unique('trips')->ignore($id)],
                'description' => 'sometimes|string',
                'price' => 'sometimes|numeric|min:0',
                'original_price' => 'sometimes|numeric|min:0',
                'discount_percentage' => 'sometimes|integer|min:0|max:100',
                'image_url' => 'sometimes|url',
                'duration_days' => 'sometimes|integer|min:1',
                'max_participants' => 'nullable|integer|min:1',
                'highlights' => 'nullable|array',
                'highlights.*.day' => 'required_with:highlights|integer',
                'highlights.*.activities' => 'required_with:highlights|array',
                'highlights.*.activities.*.time' => 'required_with:highlights.*.activities|string',
                'highlights.*.activities.*.activity' => 'required_with:highlights.*.activities|string',
                'highlights.*.activities.*.description' => 'required_with:highlights.*.activities|string',
                'learning_outcomes' => 'nullable|array',
                'personal_development' => 'nullable|array',
                'certifications' => 'nullable|array',
                'environmental_impact' => 'nullable|array',
                'community_benefits' => 'nullable|array',
            ]);

            $trip->update($validated);
            
            return response()->json($trip);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Trip not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error updating trip: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating trip'], 500);
        }
    }

    /**
     * Remove the specified trip from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $trip = Trip::findOrFail($id);
            $trip->delete();
            
            return response()->json(null, 204);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Trip not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting trip: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting trip'], 500);
        }
    }

    /**
     * Get featured trips (special filtered list)
     *
     * @return \Illuminate\Http\Response
     */
    public function featured()
    {
        try {
            $featuredTrips = Trip::with('category')
                ->where('discount_percentage', '>', 15)
                ->orderBy('discount_percentage', 'desc')
                ->limit(5)
                ->get();
                
            return response()->json($featuredTrips);
            
        } catch (\Exception $e) {
            Log::error('Error fetching featured trips: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching featured trips'], 500);
        }
    }
}