<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PremiumTier;

class PremiumTiersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $tiers = [
        [
            'name' => 'Basic',
            'price' => 4.99,
            'period' => 'month',
            'type' => 'individual',
            'is_popular' => false,
            'features' => [
                'Priority booking access',
                'Basic travel insurance',
                'Email support',
                'Basic cancellation coverage',
                'Access to premium discounts',
            ],
        ],
        [
            'name' => 'Standard',
            'price' => 9.99,
            'period' => 'month',
            'type' => 'individual',
            'is_popular' => true,
            'features' => [
                'All Basic features',
                'Extended travel insurance',
                '24/7 customer support',
                'Flexible cancellation options',
                'Free travel consultation',
            ],
        ],
        [
            'name' => 'Family',
            'price' => 14.99,
            'period' => 'month',
            'type' => 'individual',
            'is_popular' => false,
            'features' => [
                'All Standard features',
                'Family group booking discounts',
                'Kids travel insurance',
                'Priority family support line',
                'Family-friendly trip suggestions',
            ],
        ],
        [
            'name' => 'Business',
            'price' => 19.99,
            'period' => 'month',
            'type' => 'business',
            'is_popular' => false,
            'features' => [
                'All Standard features',
                'Corporate travel management',
                'Business trip insurance',
                'Dedicated account manager',
                'Invoicing and reporting tools',
            ],
        ],
    ];

    foreach ($tiers as $tierData) {
        $tier = PremiumTier::create([
            'name' => $tierData['name'],
            'price' => $tierData['price'],
            'period' => $tierData['period'],
            'type' => $tierData['type'],
            'is_popular' => $tierData['is_popular'],
        ]);

        $featureData = collect($tierData['features'])->map(fn($feature) => ['feature' => $feature]);
        $tier->features()->createMany($featureData->toArray());
    }
}
}
