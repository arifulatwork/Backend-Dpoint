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
                'host' => [
                    'name' => 'Chef Maria',
                    'rating' => 4.9,
                    'reviews' => 245,
                    'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&h=120&q=80'
                ],
                'highlights' => [
                    ['value' => 'Authentic family recipe passed down for generations'],
                    ['value' => 'Fresh ingredients from La Boqueria market'],
                    ['value' => 'Enjoy your creation with local wines'],
                ],
                'why_choose' => [
                    [
                        'icon' => 'Award',
                        'title' => "Award-Winning Chef",
                        'description' => "Learn from a chef featured in Spain's top culinary magazines"
                    ],
                    [
                        'icon' => 'Leaf',
                        'title' => "Farm-to-Table",
                        'description' => "Ingredients sourced directly from local producers"
                    ],
                    [
                        'icon' => 'Wine',
                        'title' => "Wine Pairing",
                        'description' => "Includes tasting of 3 regional wines with your meal"
                    ]
                ]
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::create($experience);
        }
    }
}
