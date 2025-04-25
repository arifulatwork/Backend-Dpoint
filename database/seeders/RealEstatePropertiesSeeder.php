<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RealEstateProperty;

class RealEstatePropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/RealEstatePropertiesSeeder.php
public function run()
{
    $properties = [
        [
            'title' => 'Modern Downtown Apartment',
            'description' => 'Spacious 3-bedroom apartment with panoramic city views',
            'location' => 'Barcelona, Eixample',
            'price' => '€1,200,000',
            'type' => 'apartment',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area' => '120 m²',
            'image' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=800&q=80',
            'premium_discount' => '5% discount for premium members'
        ],
        // Add all other properties from your React code
    ];

    RealEstateProperty::insert($properties);
}
}
