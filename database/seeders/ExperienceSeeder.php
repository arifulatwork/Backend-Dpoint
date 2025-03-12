<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Experience;

class ExperienceSeeder extends Seeder
{
    public function run()
    {
        $experiences = [
            [
                'type' => 'food',
                'name' => 'Traditional Paella Cooking Class',
                'description' => 'Learn to cook authentic Valencian paella with a local chef',
                'price' => 65,
                'rating' => 4.9,
                'reviews' => 128,
                'location' => 'Gothic Quarter',
                'duration' => '3 hours',
                'max_participants' => 8,
                'image' => 'https://images.unsplash.com/photo-1534080564583-6be75777b70a?auto=format&fit=crop&w=800&q=80',
                'city' => 'Barcelona',
                'host' => json_encode([
                    'name' => 'Chef Maria',
                    'rating' => 4.9,
                    'reviews' => 245,
                    'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&h=120&q=80'
                ])
            ],
            // Add more experiences as needed...
        ];

        foreach ($experiences as $experience) {
            Experience::create($experience);
        }
    }
}
