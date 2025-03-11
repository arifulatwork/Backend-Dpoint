<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attraction;

class AttractionsTableSeeder extends Seeder
{
    public function run()
    {
        $attractions = [
            // Paris
            [
                'destination_id' => 1,
                'name' => 'Eiffel Tower Tour',
                'type' => 'Monument',
                'duration' => '2 hours',
                'price' => 25.00,
                'group_price' => 20.00,
                'min_group_size' => 5,
                'max_group_size' => 15,
                'image' => 'https://example.com/eiffel-tower-tour.jpg',
                'highlights' => json_encode(["Iconic landmark", "Panoramic views", "Historical significance"]),
            ],
            [
                'destination_id' => 1,
                'name' => 'Louvre Museum Guided Tour',
                'type' => 'Museum',
                'duration' => '3 hours',
                'price' => 30.00,
                'group_price' => 25.00,
                'min_group_size' => 5,
                'max_group_size' => 20,
                'image' => 'https://example.com/louvre-tour.jpg',
                'highlights' => json_encode(["Art masterpieces", "Historical artifacts", "Guided explanations"]),
            ],

            // Rome
            [
                'destination_id' => 2,
                'name' => 'Colosseum Guided Tour',
                'type' => 'Historic',
                'duration' => '2.5 hours',
                'price' => 28.00,
                'group_price' => 22.00,
                'min_group_size' => 5,
                'max_group_size' => 20,
                'image' => 'https://example.com/colosseum-tour.jpg',
                'highlights' => json_encode(["Ancient Roman history", "Gladiator stories", "Architectural marvel"]),
            ],
            [
                'destination_id' => 2,
                'name' => 'Vatican City Tour',
                'type' => 'Religious',
                'duration' => '4 hours',
                'price' => 35.00,
                'group_price' => 30.00,
                'min_group_size' => 5,
                'max_group_size' => 20,
                'image' => 'https://example.com/vatican-tour.jpg',
                'highlights' => json_encode(["Sistine Chapel", "St. Peter\'s Basilica", "Religious art"]),
            ],

            // Tokyo
            [
                'destination_id' => 3,
                'name' => 'Shibuya Walking Tour',
                'type' => 'Landmark',
                'duration' => '2 hours',
                'price' => 20.00,
                'group_price' => 15.00,
                'min_group_size' => 5,
                'max_group_size' => 15,
                'image' => 'https://example.com/shibuya-tour.jpg',
                'highlights' => json_encode(["Famous crossing", "Shopping districts", "Local culture"]),
            ],
            [
                'destination_id' => 3,
                'name' => 'Senso-ji Temple Tour',
                'type' => 'Religious',
                'duration' => '1.5 hours',
                'price' => 18.00,
                'group_price' => 14.00,
                'min_group_size' => 5,
                'max_group_size' => 20,
                'image' => 'https://example.com/sensoji-tour.jpg',
                'highlights' => json_encode(["Ancient temple", "Cultural significance", "Traditional architecture"]),
            ],
        ];

        foreach ($attractions as $attraction) {
            Attraction::create($attraction);
        }
    }
}