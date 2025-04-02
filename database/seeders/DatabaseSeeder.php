<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            DestinationsTableSeeder::class,
            PointsOfInterestTableSeeder::class,
            AttractionsTableSeeder::class,
            GuidesTableSeeder::class,
            TripCategorySeeder::class,
            TripSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
        ]);
    }
}