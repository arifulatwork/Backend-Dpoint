<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Destination;

class DestinationsTableSeeder extends Seeder
{
    public function run()
    {
        $destinations = [
            [
                'country' => 'France',
                'city' => 'Paris',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg/640px-La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg',
                'coordinates' => json_encode(['lat' => 48.8566, 'lng' => 2.3522]),
                'visit_type' => 'individual',
                'highlights' => json_encode(["Eiffel Tower", "Louvre Museum", "Seine River Cruises"]),
                'cuisine' => json_encode(["Croissant", "Baguette", "Coq au Vin"]),
                'description' => 'Paris, France\'s capital, is a major European city and a global center for art, fashion, gastronomy and culture.',
                'max_price' => 2500.00,
            ],
            [
                'country' => 'Italy',
                'city' => 'Rome',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Colosseum_in_Rome%2C_Italy_-_April_2007.jpg/640px-Colosseum_in_Rome%2C_Italy_-_April_2007.jpg',
                'coordinates' => json_encode(['lat' => 41.9028, 'lng' => 12.4964]),
                'visit_type' => 'group',
                'highlights' => json_encode(["Colosseum", "Vatican City", "Trevi Fountain"]),
                'cuisine' => json_encode(["Pasta", "Pizza", "Gelato"]),
                'description' => 'Rome is the capital city of Italy, known for its ancient history, stunning architecture, and delicious cuisine.',
                'max_price' => 1800.00,
            ],
            [
                'country' => 'Japan',
                'city' => 'Tokyo',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Tokyo_Skyline_at_night_with_Mount_Fuji.jpg/640px-Tokyo_Skyline_at_night_with_Mount_Fuji.jpg',
                'coordinates' => json_encode(['lat' => 35.6762, 'lng' => 139.6503]),
                'visit_type' => 'company',
                'highlights' => json_encode(["Shibuya Crossing", "Tokyo Tower", "Senso-ji Temple"]),
                'cuisine' => json_encode(["Sushi", "Ramen", "Tempura"]),
                'description' => 'Tokyo, Japan\'s bustling capital, mixes the ultramodern and the traditional, from neon-lit skyscrapers to historic temples.',
                'max_price' => 3000.00,
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}