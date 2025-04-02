<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TripCategory;

class TripCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Yoga & Spirituality', 'slug' => 'yoga', 'icon' => 'lotus', 'description' => 'Find inner peace through meditation and yoga'],
            ['name' => 'Party Seekers', 'slug' => 'party', 'icon' => 'music', 'description' => 'Experience the vibrant nightlife and party scene'],
            // Add all other categories from your frontend
        ];

        foreach ($categories as $category) {
            TripCategory::create($category);
        }
    }
}
