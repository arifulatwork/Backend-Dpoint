<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\InternshipCategory;
use App\Models\InternshipSkill;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InternshipController extends Controller
{
    /**
     * GET /api/internships
     * Query params:
     *  - search: string
     *  - categories[]: slug or id
     *  - locations[]: string[]
     *  - modes[]: ['remote','on-site','hybrid']
     *  - skills[]: string[] (skill names)
     *  - price_min, price_max: numbers
     *  - featured: 0|1
     *  - sort: popularity|price_asc|price_desc|rating|newest
     *  - page, per_page
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'search'        => ['nullable', 'string'],
            'categories'    => ['array'],
            'categories.*'  => ['string'],
            'locations'     => ['array'],
            'locations.*'   => ['string'],
            'modes'         => ['array'],
            'modes.*'       => [Rule::in(['remote','on-site','hybrid'])],
            'skills'        => ['array'],
            'skills.*'      => ['string'],
            'price_min'     => ['nullable','numeric','min:0'],
            'price_max'     => ['nullable','numeric','min:0'],
            'featured'      => ['nullable','boolean'],
            'sort'          => ['nullable', Rule::in(['popularity','price_asc','price_desc','rating','newest'])],
            'per_page'      => ['nullable','integer','min:1','max:100'],
        ]);

        $q = Internship::query()
            ->with(['category'])
            ->select('*');

        // ---- Search
        if (!empty($data['search'])) {
            $term = '%' . str()->lower($data['search']) . '%';
            $q->where(function ($qq) use ($term) {
                $qq->whereRaw('LOWER(title) LIKE ?', [$term])
                   ->orWhereRaw('LOWER(description) LIKE ?', [$term])
                   ->orWhereRaw('LOWER(company) LIKE ?', [$term]);
            });
        }

        // ---- Categories (accept slug or id)
        if (!empty($data['categories'])) {
            $catIds = InternshipCategory::query()
                ->whereIn('slug', $data['categories'])
                ->orWhereIn('id', $data['categories'])
                ->pluck('id')
                ->all();

            if (!empty($catIds)) {
                $q->whereIn('category_id', $catIds);
            }
        }

        // ---- Locations
        if (!empty($data['locations'])) {
            $q->whereIn('location', $data['locations']);
        }

        // ---- Modes
        if (!empty($data['modes'])) {
            $q->whereIn('mode', $data['modes']);
        }

        // ---- Skills (by name) — requires belongsToMany relation `skills()`
        if (!empty($data['skills'])) {
            $skillNames = $data['skills'];
            $q->whereHas('skills', function ($qq) use ($skillNames) {
                $qq->whereIn('name', $skillNames);
            });
        }

        // ---- Price range
        if (isset($data['price_min'])) {
            $q->where('price', '>=', (float) $data['price_min']);
        }
        if (isset($data['price_max'])) {
            $q->where('price', '<=', (float) $data['price_max']);
        }

        // ---- Featured
        if (array_key_exists('featured', $data)) {
            $q->where('featured', (bool) $data['featured']);
        }

        // ---- Sorting
        switch ($data['sort'] ?? null) {
            case 'price_asc':
                $q->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $q->orderBy('price', 'desc');
                break;
            case 'rating':
                $q->orderBy('rating', 'desc')->orderBy('review_count', 'desc');
                break;
            case 'newest':
                $q->orderBy('created_at', 'desc');
                break;
            case 'popularity':
            default:
                // proxy for popularity
                $q->orderBy('review_count', 'desc')->orderBy('rating', 'desc');
                break;
        }

        $perPage   = (int) ($data['per_page'] ?? 12);
        $paginator = $q->paginate($perPage)->appends($request->query());

        // ---- Shape payload for frontend
        $items = $paginator->getCollection()->map(function (Internship $i) {
            $image = $i->image ?? ($i->image_path ?? null);

            return [
                'id'               => $i->id,
                'title'            => $i->title,
                'category'         => $i->category?->slug ?? null,
                'categoryName'     => $i->category?->name ?? null,
                'description'      => $i->description,
                'duration'         => $i->duration,
                'price'            => (float) $i->price,
                'originalPrice'    => $i->original_price ? (float) $i->original_price : null,
                'rating'           => (float) $i->rating,
                'reviewCount'      => (int) $i->review_count,
                'company'          => $i->company,
                'location'         => $i->location,
                'mode'             => $i->mode, // 'remote' | 'on-site' | 'hybrid'
                'skills'           => method_exists($i, 'skills') ? $i->skills()->pluck('name')->all() : [],
                'learningOutcomes' => method_exists($i, 'learningOutcomes') ? $i->learningOutcomes()->pluck('outcome')->all() : [],
                'image'            => $image,
                'featured'         => (bool) $i->featured,
                'deadline'         => optional($i->deadline)->toDateString(),
                'spotsLeft'        => $i->spots_left ? (int) $i->spots_left : null,
            ];
        });

        return response()->json([
            'data' => $items,
            'meta' => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/internships/options
     * Returns filter options (categories, locations, modes, price range, skills).
     */
    public function options()
    {
        $categories = InternshipCategory::query()
            ->select('id', 'slug', 'name', 'icon')
            ->withCount('internships')
            ->orderBy('name')
            ->get()
            ->map(fn ($c) => [
                'id'    => (string) $c->slug,   // send slug to FE for filtering
                'name'  => $c->name,
                'icon'  => $c->icon,
                'count' => (int) $c->internships_count,
            ]);

        $locations = Internship::query()
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location')
            ->values();

        $modes = ['Remote', 'On-site', 'Hybrid']; // UI-friendly casing

        $minPrice = (float) (Internship::min('price') ?? 0);
        $maxPrice = (float) (Internship::max('price') ?? 0);

        $skills = InternshipSkill::query()
            ->orderBy('name')
            ->pluck('name')
            ->values();

        return response()->json([
            'categories' => $categories,
            'locations'  => $locations,
            'modes'      => $modes,
            'skills'     => $skills,
            'priceRange' => [$minPrice, $maxPrice],
        ]);
    }

    /**
     * GET /api/internships/{id}
     */
    public function show($id)
    {
        $i = Internship::with(['category', 'skills', 'learningOutcomes'])->findOrFail($id);
        $image = $i->image ?? ($i->image_path ?? null);

        return response()->json([
            'id'               => $i->id,
            'title'            => $i->title,
            'category'         => $i->category?->slug,
            'categoryName'     => $i->category?->name,
            'description'      => $i->description,
            'duration'         => $i->duration,
            'price'            => (float) $i->price,
            'originalPrice'    => $i->original_price ? (float) $i->original_price : null,
            'rating'           => (float) $i->rating,
            'reviewCount'      => (int) $i->review_count,
            'company'          => $i->company,
            'location'         => $i->location,
            'mode'             => $i->mode,
            'skills'           => $i->skills?->pluck('name')->all() ?? [],
            'learningOutcomes' => $i->learningOutcomes?->pluck('outcome')->all() ?? [],
            'image'            => $image,
            'featured'         => (bool) $i->featured,
            'deadline'         => optional($i->deadline)->toDateString(),
            'spotsLeft'        => $i->spots_left ? (int) $i->spots_left : null,
            'createdAt'        => $i->created_at?->toAtomString(),
        ]);
    }
}
