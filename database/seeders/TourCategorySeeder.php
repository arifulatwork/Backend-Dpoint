<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourCategory;

class TourCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'key' => 'montenegro',
                'title' => 'Montenegro Adventures',
                'description' => 'Discover the stunning beauty of Montenegro with our curated multi-country tours. Experience breathtaking coastlines, medieval towns, and majestic mountains.',
                'image' => 'images/montenegro-trips.jpg',
                'duration_min' => 5,
                'duration_max' => 10,
                'price_min' => 800,
                'price_max' => 2000,
                'destinations' => ['Montenegro', 'Croatia', 'Bosnia', 'Albania'],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'balkan',
                'title' => 'Balkan Adventures',
                'description' => 'Explore the diverse cultures and landscapes of the Balkan region. From historic cities to natural wonders, experience the best of Southeastern Europe.',
                'image' => 'images/balkan-trips.jpg',
                'duration_min' => 7,
                'duration_max' => 14,
                'price_min' => 1000,
                'price_max' => 2500,
                'destinations' => ['Serbia', 'Croatia', 'Bosnia', 'Montenegro', 'Albania'],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'spain',
                'title' => 'Spain & Mediterranean Tours',
                'description' => 'Immerse yourself in the vibrant culture, history, and cuisine of Spain. Explore iconic cities, beautiful coastlines, and rich architectural heritage.',
                'image' => 'images/spain-trips.jpg',
                'duration_min' => 4,
                'duration_max' => 12,
                'price_min' => 600,
                'price_max' => 1800,
                'destinations' => ['Barcelona', 'Madrid', 'Seville', 'Valencia', 'Costa del Sol'],
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $data) {
            TourCategory::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
