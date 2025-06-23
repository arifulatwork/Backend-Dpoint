<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\PremiumTier;
use App\Models\SpecialDiscount;
use App\Models\RealEstateProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PremiumController extends Controller
{
    // Cache duration in minutes
    const CACHE_DURATION = 60;

    public function getBenefits()
    {
        return Cache::remember('premium_benefits', self::CACHE_DURATION, function () {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'benefits' => [
                        [
                            'icon' => 'Star',
                            'title' => 'Priority Access',
                            'description' => 'Book popular experiences before they sell out'
                        ],
                        [
                            'icon' => 'Globe',
                            'title' => 'Worldwide Coverage',
                            'description' => 'Travel insurance coverage in all destinations'
                        ],
                        // Add all other benefits from your React code
                    ]
                ]
            ]);
        });
    }

    public function getPricingTiers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['sometimes', Rule::in(['individual', 'business'])]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid input',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $type = $request->query('type', 'individual');
            
            $tiers = PremiumTier::with('features')
                ->where('type', $type)
                ->get()
                ->map(function ($tier) {
                    return [
                         'id' => $tier->id,
                        'name' => $tier->name,
                        'price' => $tier->price,
                        'period' => $tier->period,
                        'type' => $tier->type,
                        'isPopular' => $tier->is_popular,
                        'features' => $tier->features->pluck('feature')->toArray()
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => ['tiers' => $tiers]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve pricing tiers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSpecialDiscounts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => ['sometimes', Rule::in([
                'restaurant', 'museum', 'shop', 'attraction', 'realestate'
            ])]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid category',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = $request->query('category');
            $cacheKey = 'special_discounts' . ($category ? "_$category" : '');
            
            $discounts = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category) {
                $query = SpecialDiscount::query();
                
                if ($category) {
                    $query->where('category', $category);
                }
                
                return $query->get()->map(function ($discount) {
                    return [
                        'id' => $discount->id,
                        'title' => $discount->title,
                        'description' => $discount->description,
                        'location' => $discount->location,
                        'discount' => $discount->discount,
                        'category' => $discount->category,
                        'validUntil' => Carbon::parse($discount->valid_until)->format('Y-m-d'),
                        'image' => $discount->image
                    ];
                });
            });

            return response()->json([
                'status' => 'success',
                'data' => ['discounts' => $discounts]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve discounts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRealEstateProperties(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['sometimes', Rule::in([
                'apartment', 'villa', 'commercial', 'penthouse', 'townhouse', 'all'
            ])]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid property type',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $type = $request->query('type');
            $cacheKey = 'real_estate_properties' . ($type && $type !== 'all' ? "_$type" : '');
            
            $properties = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($type) {
                $query = RealEstateProperty::query();
                
                if ($type && $type !== 'all') {
                    $query->where('type', $type);
                }
                
                return $query->get()->map(function ($property) {
                    return [
                        'id' => $property->id,
                        'title' => $property->title,
                        'description' => $property->description,
                        'location' => $property->location,
                        'price' => $property->price,
                        'type' => $property->type,
                        'bedrooms' => $property->bedrooms,
                        'bathrooms' => $property->bathrooms,
                        'area' => $property->area,
                        'image' => $property->image,
                        'premiumDiscount' => $property->premium_discount
                    ];
                });
            });

            return response()->json([
                'status' => 'success',
                'data' => ['properties' => $properties]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve properties',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}