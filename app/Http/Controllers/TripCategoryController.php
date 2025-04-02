<?php

namespace App\Http\Controllers;

use App\Models\TripCategory;
use Illuminate\Http\Request;

class TripCategoryController extends Controller
{
    public function index()
    {
        return TripCategory::all();
    }

    public function show($id)
    {
        return TripCategory::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:trip_categories',
            'icon' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        return TripCategory::create($validated);
    }

    public function update(Request $request, $id)
    {
        $category = TripCategory::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:trip_categories,slug,'.$id,
            'icon' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $category->update($validated);
        return $category;
    }

    public function destroy($id)
    {
        $category = TripCategory::findOrFail($id);
        $category->delete();
        return response()->noContent();
    }
}