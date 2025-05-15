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
                'type' => 'attraction',
                'position' => json_encode([48.8584, 2.2945]),
                'description' => 'Iconic iron tower offering panoramic views of Paris from its observation decks.',
                'image' => 'https://example.com/images/eiffel-tower.jpg',
                'rating' => 4.7,
                'price' => '€€€',
                'booking_url' => 'https://www.toureiffel.paris/en',
                'amenities' => json_encode(['Guided tours', 'Souvenir shop', 'Restaurant', 'Elevator access']),
            ],
            [
                'destination_id' => 1,
                'name' => 'Louvre Museum',
                'type' => 'museum',
                'position' => json_encode([48.8606, 2.3376]),
                'description' => 'World\'s largest art museum housing iconic works like the Mona Lisa.',
                'image' => 'https://example.com/images/louvre.jpg',
                'rating' => 4.8,
                'price' => '€€',
                'booking_url' => 'https://www.louvre.fr/en',
                'amenities' => json_encode(['Audio guides', 'Cafeteria', 'Wheelchair accessible', 'Free WiFi']),
            ],
            [
                'destination_id' => 1,
                'name' => 'Hôtel Ritz Paris',
                'type' => 'hotel',
                'position' => json_encode([48.8686, 2.3289]),
                'description' => 'Legendary luxury hotel featuring elegant rooms, a spa & gourmet dining.',
                'image' => 'https://example.com/images/ritz-paris.jpg',
                'rating' => 4.9,
                'price' => '€€€€',
                'booking_url' => 'https://www.ritzparis.com',
                'amenities' => json_encode(['Spa', 'Pool', 'Fine dining', 'Concierge', 'Room service']),
            ],

            // Rome
            [
                'destination_id' => 2,
                'name' => 'Colosseum',
                'type' => 'attraction',
                'position' => json_encode([41.8902, 12.4922]),
                'description' => 'Ancient amphitheater that hosted gladiator contests and public spectacles.',
                'image' => 'https://example.com/images/colosseum.jpg',
                'rating' => 4.8,
                'price' => '€€',
                'booking_url' => 'https://parcocolosseo.it',
                'amenities' => json_encode(['Guided tours', 'Audio guide', 'Night visits']),
            ],
            [
                'destination_id' => 2,
                'name' => 'Trattoria da Enzo',
                'type' => 'restaurant',
                'position' => json_encode([41.8897, 12.4776]),
                'description' => 'Authentic Roman cuisine in a cozy trattoria near the Tiber river.',
                'image' => 'https://example.com/images/da-enzo.jpg',
                'rating' => 4.6,
                'price' => '€€',
                'amenities' => json_encode(['Outdoor seating', 'Traditional cuisine', 'Wine selection']),
            ],
            [
                'destination_id' => 2,
                'name' => 'Vatican Museums',
                'type' => 'museum',
                'position' => json_encode([41.9062, 12.4543]),
                'description' => 'Extensive collections of art and historical artifacts in the Vatican City.',
                'image' => 'https://example.com/images/vatican-museums.jpg',
                'rating' => 4.7,
                'price' => '€€',
                'booking_url' => 'https://www.museivaticani.va',
                'amenities' => json_encode(['Sistine Chapel', 'Guided tours', 'Audio guide']),
            ],

            // Tokyo
            [
                'destination_id' => 3,
                'name' => 'Shibuya Crossing',
                'type' => 'attraction',
                'position' => json_encode([35.6595, 139.7006]),
                'description' => 'World\'s busiest pedestrian crossing with neon lights and giant video screens.',
                'image' => 'https://example.com/images/shibuya-crossing.jpg',
                'rating' => 4.5,
                'price' => 'Free',
            ],
            [
                'destination_id' => 3,
                'name' => 'Park Hotel Tokyo',
                'type' => 'hotel',
                'position' => json_encode([35.6632, 139.7638]),
                'description' => 'Stylish hotel with art-themed rooms and panoramic city views.',
                'image' => 'https://example.com/images/park-hotel-tokyo.jpg',
                'rating' => 4.4,
                'price' => '€€€',
                'booking_url' => 'https://www.parkhoteltokyo.com',
                'amenities' => json_encode(['City views', 'Art gallery', 'Restaurant', 'Concierge']),
            ],
            [
                'destination_id' => 3,
                'name' => 'Sukiyabashi Jiro',
                'type' => 'restaurant',
                'position' => json_encode([35.6694, 139.7595]),
                'description' => 'Michelin-starred sushi restaurant featured in the documentary "Jiro Dreams of Sushi".',
                'image' => 'https://example.com/images/jiro-sushi.jpg',
                'rating' => 4.9,
                'price' => '€€€€',
                'amenities' => json_encode(['Omakase menu', 'Counter seating', 'Premium ingredients']),
            ],

            // Flight example
            [
                'destination_id' => 1, // Paris
                'name' => 'Air France Flight 123 (CDG-JFK)',
                'type' => 'flight',
                'position' => json_encode([49.0097, 2.5479]), // CDG Airport coordinates
                'description' => 'Daily flight from Paris to New York',
                'flight_details' => json_encode([
                    'departure' => 'Charles de Gaulle Airport (CDG)',
                    'arrival' => 'John F. Kennedy Airport (JFK)',
                    'airline' => 'Air France',
                    'flight_number' => 'AF123',
                    'duration' => '7h 30m',
                    'cabin_class' => ['Economy', 'Premium Economy', 'Business']
                ]),
                'booking_url' => 'https://www.airfrance.com'
            ],

            // Shuttle example
            [
                'destination_id' => 2, // Rome
                'name' => 'Rome Airport Shuttle',
                'type' => 'shuttle',
                'position' => json_encode([41.8004, 12.2388]), // Between FCO and Rome
                'description' => 'Shared shuttle service between Fiumicino Airport and Rome city center',
                'shuttle_details' => json_encode([
                    'departure_point' => 'Fiumicino Airport (FCO)',
                    'arrival_point' => 'Rome Termini Station',
                    'operator' => 'Rome Airport Shuttle',
                    'duration' => '45m',
                    'schedule' => 'Every 30 minutes',
                    'capacity' => 8
                ]),
                'price' => '€25',
                'booking_url' => 'https://www.romeairportshuttle.com'
            ]
        ];

        foreach ($pointsOfInterest as $point) {
            PointOfInterest::create($point);
        }
    }
}