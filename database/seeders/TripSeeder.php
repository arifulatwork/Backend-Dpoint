<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

namespace Database\Seeders;

use App\Models\Trip;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    public function run()
    {
        Trip::create([
            'title' => 'Montserrat Day Trip',
            'description' => 'Visit the stunning Montserrat monastery and mountains',
            'type' => 'one-day',
            'duration' => '10 hours',
            'price' => 79,
            'original_price' => 99,
            'discount_percentage' => 20,
            'image' => 'https://images.unsplash.com/photo-1586957469525-7850e7bef283?auto=format&fit=crop&w=800&q=80',
            'start_time' => '08:30',
            'end_time' => '18:30',
            'highlights' => json_encode([
                ['time' => '08:30', 'activity' => 'Departure from Barcelona', 'description' => 'Meet your guide at Plaça Catalunya and board our comfortable coach'],
                ['time' => '09:45', 'activity' => 'Montserrat Monastery Arrival', 'description' => 'Reach the monastery and enjoy breathtaking mountain views'],
                // Add more highlights as needed
            ]),
            'included' => json_encode(['Transportation', 'Guide', 'Cable car tickets', 'Wine tasting']),
            'meeting_point' => 'Plaça Catalunya',
            'max_participants' => 20,
            'special_offer' => json_encode([
                'type' => 'Early Bird',
                'validUntil' => '2024-03-31',
                'description' => 'Book now and save 20%'
            ]),
        ]);
    }
}