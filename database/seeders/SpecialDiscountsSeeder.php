<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SpecialDiscount;

class SpecialDiscountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/SpecialDiscountsSeeder.php
public function run()
{
    $discounts = [
        [
            'title' => 'Sagrada Familia VIP Tour',
            'description' => '25% off on VIP guided tours with skip-the-line access',
            'location' => 'Barcelona',
            'discount' => '25% OFF',
            'category' => 'attraction',
            'valid_until' => '2024-12-31',
            'image' => 'https://images.unsplash.com/photo-1583779457094-ab6f77f7bf57?auto=format&fit=crop&w=800&q=80'
        ],
        // Add all other discounts from your React code
    ];

    SpecialDiscount::insert($discounts);
}
}
