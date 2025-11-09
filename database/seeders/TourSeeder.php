<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $tours = [
            [
                'title' => 'Montenegro Coastal Adventure',
                'slug' => 'montenegro-coastal-adventure',
                'description' => 'Explore Montenegro’s breathtaking Adriatic coast — from the old town of Kotor to the lively beaches of Budva and the serene Bay of Boka.',
                'duration_days' => 7,
                'base_price' => 1200.00,
                'currency' => 'EUR',
                'image_url' => 'tours/montenegro.jpg',
                'destinations' => ['Kotor', 'Budva', 'Podgorica', 'Sveti Stefan'],
                'group_size' => ['min' => 2, 'max' => 10],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Arrival & Welcome Dinner', 'description' => 'Meet your guide and group in Podgorica, enjoy a traditional Montenegrin dinner.', 'meals' => ['Dinner'], 'accommodation' => 'Hotel Podgorica'],
                    ['day' => 2, 'title' => 'Kotor Bay Tour', 'description' => 'Guided walking tour of UNESCO-listed Kotor and Perast.', 'meals' => ['Breakfast'], 'accommodation' => 'Hotel Marija Kotor'],
                ],
                'included' => ['Accommodation', 'Breakfast', 'Transportation', 'Local guide'],
                'not_included' => ['Flights', 'Travel insurance', 'Personal expenses'],
                'category' => 'montenegro',
                'is_active' => true,
            ],
            [
                'title' => 'Balkan Discovery Journey',
                'slug' => 'balkan-discovery-journey',
                'description' => 'Experience the rich culture and natural beauty of the Balkans — from Belgrade to Dubrovnik, across lakes, mountains, and historic towns.',
                'duration_days' => 10,
                'base_price' => 1850.00,
                'currency' => 'EUR',
                'image_url' => 'tours/balkan.jpg',
                'destinations' => ['Serbia', 'Bosnia', 'Croatia', 'Montenegro'],
                'group_size' => ['min' => 4, 'max' => 12],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Arrival in Belgrade', 'description' => 'Welcome meeting and dinner at Skadarlija.', 'meals' => ['Dinner'], 'accommodation' => 'Belgrade City Hotel'],
                    ['day' => 2, 'title' => 'Sarajevo Highlights', 'description' => 'Visit the Latin Bridge, Baščaršija, and Tunnel of Hope.', 'meals' => ['Breakfast'], 'accommodation' => 'Hotel Bosnia'],
                ],
                'included' => ['Breakfast', 'Dinner', 'Guided Tours', 'Private transport'],
                'not_included' => ['Visa fees', 'Flights', 'Tips'],
                'category' => 'balkan',
                'is_active' => true,
            ],
            [
                'title' => 'Spain & Mediterranean Explorer',
                'slug' => 'spain-mediterranean-explorer',
                'description' => 'Immerse yourself in the vibrant spirit of Spain — from Barcelona’s Gaudí masterpieces to Seville’s flamenco nights and Valencia’s beaches.',
                'duration_days' => 8,
                'base_price' => 1500.00,
                'currency' => 'EUR',
                'image_url' => 'tours/spain.jpg',
                'destinations' => ['Barcelona', 'Seville', 'Valencia', 'Granada'],
                'group_size' => ['min' => 2, 'max' => 15],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Welcome to Barcelona', 'description' => 'Arrival and tapas dinner near Las Ramblas.', 'meals' => ['Dinner'], 'accommodation' => 'Hotel Jazz Barcelona'],
                    ['day' => 2, 'title' => 'Sagrada Familia & Gothic Quarter', 'description' => 'Explore Gaudí’s masterpiece and Barcelona’s historic heart.', 'meals' => ['Breakfast'], 'accommodation' => 'Hotel Jazz Barcelona'],
                ],
                'included' => ['Accommodation', 'Breakfast', 'Airport pickup', 'Local guide'],
                'not_included' => ['Lunch', 'Dinner (except welcome)', 'Entrance tickets'],
                'category' => 'spain',
                'is_active' => true,
            ],
        ];

        foreach ($tours as $tour) {
            Tour::updateOrCreate(['slug' => $tour['slug']], $tour);
        }
    }
}
