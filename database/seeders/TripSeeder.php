<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;

class TripSeeder extends Seeder
{
    public function run()
    {
        $trips = [
            [
                'category_id' => 1,
                'title' => 'Mediterranean Yoga Retreat',
                'slug' => 'mediterranean-yoga-retreat',
                'description' => 'A transformative yoga and meditation retreat along the Mediterranean coast. Rejuvenate your body and mind.',
                'price' => 599,
                'original_price' => 749,
                'discount_percentage' => 20,
                'image_url' => 'trip-images/mediterranean-yoga.jpg',
                'duration_days' => 3,
                'max_participants' => 16,

                // JSON-compatible arrays
                'highlights' => [
                    ['item' => 'Sunrise yoga sessions'],
                    ['item' => 'Seaside meditation'],
                    ['item' => 'Organic vegetarian meals'],
                ],
                'learning_outcomes' => [
                    ['item' => 'Improved flexibility and breathing'],
                    ['item' => 'Mindfulness and focus techniques'],
                ],
                'personal_development' => [
                    ['item' => 'Stress reduction'],
                    ['item' => 'Self-awareness boost'],
                ],
                'certifications' => [
                    ['item' => 'Certificate of Participation'],
                ],
                'environmental_impact' => [
                    ['item' => 'Eco-lodging used throughout the trip'],
                    ['item' => 'Locally sourced food to reduce footprint'],
                ],
                'community_benefits' => [
                    ['item' => 'Supports local yoga instructors'],
                    ['item' => 'Promotes wellness tourism in rural areas'],
                ],

                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($trips as $trip) {
            Trip::create($trip);
        }
    }
}
