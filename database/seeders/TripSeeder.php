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
                'slug' => 'mediterranean-yoga-retreat',
                'category_id' => 1,
                'title' => 'Mediterranean Yoga Retreat',
                'description' => 'A transformative yoga and meditation retreat along the Mediterranean coast. Rejuvenate your body and mind.',
                'price' => 599,
                'original_price' => 749,
                'discount_percentage' => 20,
                'image_url' => 'trip-images/mediterranean-yoga.jpg',
                'duration_days' => 3,
                'max_participants' => 16,

                'highlights' => [
                    [
                        'day' => 1,
                        'activities' => [
                            [
                                'time' => '07:00',
                                'activity' => 'Sunrise Yoga',
                                'description' => 'Start the day with a gentle yoga session on the beach.'
                            ],
                            [
                                'time' => '09:00',
                                'activity' => 'Seaside Meditation',
                                'description' => 'Guided mindfulness meditation by the sea.'
                            ]
                        ]
                    ],
                    [
                        'day' => 2,
                        'activities' => [
                            [
                                'time' => '08:00',
                                'activity' => 'Breathwork Workshop',
                                'description' => 'Learn focused breathing techniques for stress relief.'
                            ],
                            [
                                'time' => '11:00',
                                'activity' => 'Organic Lunch',
                                'description' => 'Enjoy a healthy plant-based meal sourced locally.'
                            ]
                        ]
                    ],
                    [
                        'day' => 3,
                        'activities' => [
                            [
                                'time' => '06:30',
                                'activity' => 'Morning Walk',
                                'description' => 'Light walk along the scenic coastline.'
                            ],
                            [
                                'time' => '10:00',
                                'activity' => 'Closing Circle',
                                'description' => 'Reflection session and certificate distribution.'
                            ]
                        ]
                    ]
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
            Trip::updateOrCreate(
                ['slug' => $trip['slug']],
                $trip
            );
        }
    }
}
