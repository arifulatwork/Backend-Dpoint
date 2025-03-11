<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guide;

class GuidesTableSeeder extends Seeder
{
    public function run()
    {
        $guides = [
            // Paris
            [
                'attraction_id' => 1,
                'name' => 'Jean Dupont',
                'avatar' => 'https://example.com/jean.jpg',
                'rating' => 4.7,
                'reviews' => 150,
                'experience' => '5 years',
                'languages' => json_encode(["French", "English"]),
            ],
            [
                'attraction_id' => 2,
                'name' => 'Marie Curie',
                'avatar' => 'https://example.com/marie.jpg',
                'rating' => 4.9,
                'reviews' => 200,
                'experience' => '7 years',
                'languages' => json_encode(["French", "English", "Spanish"]),
            ],

            // Rome
            [
                'attraction_id' => 3,
                'name' => 'Marco Rossi',
                'avatar' => 'https://example.com/marco.jpg',
                'rating' => 4.8,
                'reviews' => 180,
                'experience' => '6 years',
                'languages' => json_encode(["Italian", "English"]),
            ],
            [
                'attraction_id' => 4,
                'name' => 'Giulia Bianchi',
                'avatar' => 'https://example.com/giulia.jpg',
                'rating' => 4.6,
                'reviews' => 120,
                'experience' => '4 years',
                'languages' => json_encode(["Italian", "English", "German"]),
            ],

            // Tokyo
            [
                'attraction_id' => 5,
                'name' => 'Yuki Tanaka',
                'avatar' => 'https://example.com/yuki.jpg',
                'rating' => 4.5,
                'reviews' => 100,
                'experience' => '3 years',
                'languages' => json_encode(["Japanese", "English"]),
            ],
            [
                'attraction_id' => 6,
                'name' => 'Hiroshi Nakamura',
                'avatar' => 'https://example.com/hiroshi.jpg',
                'rating' => 4.7,
                'reviews' => 130,
                'experience' => '5 years',
                'languages' => json_encode(["Japanese", "English", "Chinese"]),
            ],
        ];

        foreach ($guides as $guide) {
            Guide::create($guide);
        }
    }
}