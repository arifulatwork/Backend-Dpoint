<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PointOfInterest;

class PointsOfInterestTableSeeder extends Seeder
{
    public function run()
    {
        $pointsOfInterest = [
            // Accommodation examples
            [
                'destination_id' => 1,
                'name' => 'Student Hostel Paris',
                'type' => 'accommodation',
                'position' => [48.8566, 2.3522],
                'description' => 'Affordable student-friendly hostel in central Paris with dorms and private rooms.',
                'image' => 'https://example.com/images/paris-hostel.jpg',
                'rating' => 4.2,
                'price' => '€',
                'booking_url' => 'https://www.parishostel.com',
                'amenities' => ['Free WiFi', 'Shared kitchen', '24h Reception'],
            ],
            [
                'destination_id' => 2,
                'name' => 'Rome Guesthouse',
                'type' => 'accommodation',
                'position' => [41.9028, 12.4964],
                'description' => 'Charming guesthouse in the heart of Rome with Italian breakfast included.',
                'image' => 'https://example.com/images/rome-guesthouse.jpg',
                'rating' => 4.5,
                'price' => '€€',
                'booking_url' => 'https://www.romeguesthouse.com',
                'amenities' => ['Breakfast included', 'Air conditioning', 'Pet friendly'],
            ],
            [
                'destination_id' => 3,
                'name' => 'Tokyo Capsule Hotel',
                'type' => 'accommodation',
                'position' => [35.6895, 139.6917],
                'description' => 'Modern capsule hotel experience in Shinjuku with communal facilities.',
                'image' => 'https://example.com/images/tokyo-capsule.jpg',
                'rating' => 4.0,
                'price' => '€',
                'booking_url' => 'https://www.tokyocapsule.com',
                'amenities' => ['Capsule pods', 'Shared bath', 'Luggage lockers'],
            ],

            // Legal Advice examples
            [
                'destination_id' => 1,
                'name' => 'Paris Legal Aid Center',
                'type' => 'legal advice',
                'position' => [48.8600, 2.3400],
                'description' => 'Provides free and affordable legal consultation services in Paris.',
                'image' => 'https://example.com/images/paris-legal.jpg',
                'rating' => 4.6,
                'price' => 'Free / €',
                'booking_url' => 'https://www.parislegalaid.fr',
                'amenities' => ['Immigration support', 'Civil law advice', 'Translation services'],
            ],
            [
                'destination_id' => 2,
                'name' => 'Rome Immigration Lawyer',
                'type' => 'legal advice',
                'position' => [41.9030, 12.4950],
                'description' => 'Law firm specializing in immigration, visas, and NIE/TIE documentation.',
                'image' => 'https://example.com/images/rome-lawyer.jpg',
                'rating' => 4.7,
                'price' => '€€€',
                'booking_url' => 'https://www.romeimmigrationlaw.com',
                'amenities' => ['Visa assistance', 'Residency permits', 'NIE/TIE guidance'],
            ],
            [
                'destination_id' => 3,
                'name' => 'Tokyo International Legal Office',
                'type' => 'legal advice',
                'position' => [35.6762, 139.6503],
                'description' => 'Legal office offering bilingual support for expats living in Tokyo.',
                'image' => 'https://example.com/images/tokyo-legal.jpg',
                'rating' => 4.8,
                'price' => '€€',
                'booking_url' => 'https://www.tokyolegal.jp',
                'amenities' => ['Bilingual lawyers', 'Corporate law', 'Visa services'],
            ],
        ];

        foreach ($pointsOfInterest as $point) {
            PointOfInterest::create($point);
        }
    }
}
