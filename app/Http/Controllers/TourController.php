<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TourController extends Controller
{
    // GET /api/tours?category=montenegro
    public function index(Request $request)
    {
        $q = Tour::query()->active();

        if ($request->filled('category')) {
            $q->category($request->string('category'));
        }

        $tours = $q->get()->map(fn ($tour) => $this->normalizeTour($tour));

        return response()->json($tours);
    }

    // GET /api/tours/{slug}
    public function show(string $slug)
    {
        $tour = Tour::where('slug', $slug)->first();

        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }
        return response()->json($this->normalizeTour($tour));
    }

    // POST /api/tours (admin only normally)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => ['required','string','max:255', Rule::unique('tours','slug')],
            'category'       => 'required|string|max:100', // e.g., montenegro|balkan|spain
            'description'    => 'required|string',
            'duration_days'  => 'required|integer|min:1|max:365',
            'base_price'     => 'required|numeric|min:0',
            'currency'       => 'nullable|string|size:3',
            'image_url'      => 'required|string',
            'destinations'   => 'required|array',
            'group_size'     => 'required|array',     // {min,max}
            'itinerary'      => 'required|array',
            'included'       => 'required|array',
            'not_included'   => 'required|array',
            'is_active'      => 'nullable|boolean',
        ]);

        $tour = Tour::create($validated);

        return response()->json($this->normalizeTour($tour), 201);
    }

    /** Map DB → frontend shape expected by your components */
    protected function normalizeTour(Tour $tour): array
    {
        return [
            'id'           => $tour->id,
            'title'        => $tour->title,
            'slug'         => $tour->slug,
            'description'  => $tour->description,
            // your React expects string duration & price key names:
            'duration'     => (string) $tour->duration_days,
            'price'        => (float) $tour->base_price,
            'image_url'    => $tour->image_url,
            'destinations' => $tour->destinations,
            'group_size'   => $tour->group_size,
            'itinerary'    => $tour->itinerary,
            'included'     => $tour->included,
            'not_included' => $tour->not_included,
            'category'     => $tour->category,
        ];
    }

    // --- Optional backward-compatible endpoints for your current frontend ---
    // GET /api/montenegro-tours
    public function indexMontenegro()
    {
        request()->merge(['category' => 'montenegro']);
        return $this->index(request());
    }

    // GET /api/montenegro-tours/{slug}
    public function showMontenegro(string $slug)
    {
        return $this->show($slug);
    }
}
