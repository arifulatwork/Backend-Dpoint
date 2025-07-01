<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BalkanTrip;

class BalkanTripSeeder extends Seeder
{
    public function run(): void
    {
        BalkanTrip::create([
            'title' => 'Albania, Macedonia & Greece Adventure',
            'slug' => 'albania-macedonia-greece',
            'description' => 'Embark on an unforgettable journey through the heart of the Balkans. Discover ancient ruins, vibrant cultures, and breathtaking landscapes across three fascinating countries.',
            'duration' => '15',
            'price' => 2499.00,
            'image_url' => 'https://images.unsplash.com/photo-1592486058517-36236ba247c8?auto=format&fit=crop&w=800&q=80',
            'destinations' => json_encode(['Albania', 'North Macedonia', 'Greece']),
            'group_size' => json_encode(['min' => 4, 'max' => 12]),
            'itinerary' => json_encode([
                [
                    'day' => 1,
                    'title' => 'Arrival in Tirana',
                    'description' => 'Welcome to Albania! Upon arrival at Tirana International Airport, transfer to your hotel. Evening welcome meeting and traditional dinner.',
                    'meals' => ['Dinner'],
                    'accommodation' => 'Hotel in Tirana'
                ],
                [
                    'day' => 2,
                    'title' => 'Tirana City Tour & Kruja',
                    'description' => 'Explore Tirana\'s highlights including Skanderbeg Square and the National Museum. Afternoon visit to historic Kruja.',
                    'meals' => ['Breakfast', 'Lunch'],
                    'accommodation' => 'Hotel in Tirana'
                ],
                [
                    'day' => 3,
                    'title' => 'Ohrid, North Macedonia',
                    'description' => 'Cross into Macedonia and discover UNESCO-listed Ohrid, known for its beautiful lake and historic churches.',
                    'meals' => ['Breakfast', 'Dinner'],
                    'accommodation' => 'Hotel in Ohrid'
                ],
                [
                    'day' => 4,
                    'title' => 'Meteora, Greece',
                    'description' => 'Travel to Greece to visit the magnificent monasteries of Meteora perched atop dramatic rock formations.',
                    'meals' => ['Breakfast', 'Lunch'],
                    'accommodation' => 'Hotel in Kalambaka'
                ]
            ]),
            'included' => json_encode([
                'All accommodations in 4-star hotels',
                'Professional English-speaking guide',
                'Private transportation',
                'Daily breakfast and selected meals',
                'All entrance fees',
                'Airport transfers',
                'Local experiences and cultural activities'
            ]),
            'not_included' => json_encode([
                'International flights',
                'Travel insurance',
                'Personal expenses',
                'Optional activities',
                'Gratuities',
                'Visa fees (if applicable)'
            ])
        ]);
    }
}
