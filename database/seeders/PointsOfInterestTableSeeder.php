<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PointOfInterest;

class PointsOfInterestTableSeeder extends Seeder
{
    public function run()
    {
        $pointsOfInterest = [
            // Paris
            [
                'destination_id' => 1,
                'name' => 'Eiffel Tower',
                'latitude' => 48.8584,
                'longitude' => 2.2945,
                'type' => 'Monument',
            ],
            [
                'destination_id' => 1,
                'name' => 'Louvre Museum',
                'latitude' => 48.8606,
                'longitude' => 2.3376,
                'type' => 'Museum',
            ],
            [
                'destination_id' => 1,
                'name' => 'Seine River',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'type' => 'Natural',
            ],

            // Rome
            [
                'destination_id' => 2,
                'name' => 'Colosseum',
                'latitude' => 41.8902,
                'longitude' => 12.4922,
                'type' => 'Historic',
            ],
            [
                'destination_id' => 2,
                'name' => 'Vatican City',
                'latitude' => 41.9029,
                'longitude' => 12.4534,
                'type' => 'Religious',
            ],
            [
                'destination_id' => 2,
                'name' => 'Trevi Fountain',
                'latitude' => 41.9009,
                'longitude' => 12.4833,
                'type' => 'Monument',
            ],

            // Tokyo
            [
                'destination_id' => 3,
                'name' => 'Shibuya Crossing',
                'latitude' => 35.6595,
                'longitude' => 139.7006,
                'type' => 'Landmark',
            ],
            [
                'destination_id' => 3,
                'name' => 'Tokyo Tower',
                'latitude' => 35.6586,
                'longitude' => 139.7455,
                'type' => 'Monument',
            ],
            [
                'destination_id' => 3,
                'name' => 'Senso-ji Temple',
                'latitude' => 35.7147,
                'longitude' => 139.7967,
                'type' => 'Religious',
            ],
        ];

        foreach ($pointsOfInterest as $point) {
            PointOfInterest::create($point);
        }
    }
}