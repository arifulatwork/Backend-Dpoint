<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trip;

class TripSeeder extends Seeder
{
    public function run()
    {
        $trips = [
            [
                'category_id' => 1, // Yoga
                'title' => 'Mediterranean Yoga Retreat',
                'slug' => 'mediterranean-yoga-retreat',
                'description' => 'A transformative yoga and meditation retreat...',
                'price' => 599,
                'original_price' => 749,
                'discount_percentage' => 20,
                'image_url' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773',
                'duration_days' => 3,
                'max_participants' => 16,
                'highlights' => json_encode([/* your highlights data */]),
                // Add other fields as needed
            ],
            // Add all other trips from your frontend
        ];

        foreach ($trips as $trip) {
            Trip::create($trip);
        }
    }
}