<?php

namespace Database\Seeders;

use App\Models\AccommodationOffer;
use Illuminate\Database\Seeder;

class AccommodationOfferSeeder extends Seeder
{
    public function run()
    {
        AccommodationOffer::create([
            'name' => 'Hotel Arts Barcelona',
            'type' => 'Luxury Hotel',
            'discount' => 25,
            'price' => 299,
            'original_price' => 399,
            'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
            'valid_until' => '2024-04-30',
            'description' => 'Luxury beachfront hotel with stunning views',
            'perks' => json_encode(['Spa access', 'Breakfast included', 'Late checkout']),
        ]);

        AccommodationOffer::create([
            'name' => 'Casa Camper',
            'type' => 'Boutique Hotel',
            'discount' => 20,
            'price' => 189,
            'original_price' => 239,
            'image' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=800&q=80',
            'valid_until' => '2024-05-15',
            'description' => 'Unique boutique hotel in the heart of the city',
            'perks' => json_encode(['24/7 snack lounge', 'Bike rental', 'Welcome drink']),
        ]);
    }
}