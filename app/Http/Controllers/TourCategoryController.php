<?php

namespace App\Http\Controllers;

use App\Models\TourCategory;
use App\Models\Tour;

class TourCategoryController extends Controller
{
    // GET /api/tour-categories
    public function index()
    {
        $cats = TourCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($c) {
                // Auto-aggregate from tours if explicit ranges aren’t provided
                $agg = Tour::where('category', $c->key)
                    ->selectRaw('MIN(duration_days) as dmin, MAX(duration_days) as dmax, MIN(base_price) as pmin, MAX(base_price) as pmax')
                    ->first();

                $duration = $c->duration_min && $c->duration_max
                    ? "{$c->duration_min}-{$c->duration_max} days"
                    : (($agg?->dmin && $agg?->dmax) ? "{$agg->dmin}-{$agg->dmax} days" : null);

                $priceRange = $c->price_min && $c->price_max
                    ? "€{$c->price_min} - €{$c->price_max}"
                    : (($agg?->pmin && $agg?->pmax) ? "€{$agg->pmin} - €{$agg->pmax}" : null);

                return [
                    'id' => $c->id,
                    'key' => $c->key,
                    'title' => $c->title,
                    'description' => $c->description,
                    'image' => $c->image, // e.g., images/xxx.jpg
                    'duration' => $duration,
                    'priceRange' => $priceRange,
                    'destinations' => $c->destinations ?: [],
                ];
            });

        return response()->json($cats);
    }

    // GET /api/tour-categories/{key}
    public function show($key)
    {
        $c = TourCategory::where('key', $key)->firstOrFail();
        return response()->json($c);
    }
}
