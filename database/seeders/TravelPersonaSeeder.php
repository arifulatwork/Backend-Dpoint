<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TravelPersonaQuestion;
use App\Models\TravelPersonaOption;

class TravelPersonaSeeder extends Seeder
{
    public function run(): void
    {
        // Question 1
        $q1 = TravelPersonaQuestion::create([
            'key' => 'travelReason',
            'text' => 'Why do you travel?',
            'multiple' => false,
            'has_budget_slider' => false,
        ]);

        $q1->options()->createMany([
            ['value' => 'peace', 'label' => 'Peace', 'description' => 'I want to disconnect and recharge', 'emoji' => '🧘‍♀️'],
            ['value' => 'exploration', 'label' => 'Exploration', 'description' => 'I\'m curious and want to see new things', 'emoji' => '🔍'],
            ['value' => 'connection', 'label' => 'Connection', 'description' => 'I want to meet people and bond', 'emoji' => '🧑‍🤝‍🧑', 'icon' => 'Users'],
            ['value' => 'escape', 'label' => 'Escape', 'description' => 'I need a break from my routine', 'emoji' => '✈️', 'icon' => 'Compass'],
            ['value' => 'adventure', 'label' => 'Adventure', 'description' => 'I live for thrills and challenges', 'emoji' => '🗺️', 'icon' => 'Activity'],
        ]);

        // Question 2
        $q2 = TravelPersonaQuestion::create([
            'key' => 'environment',
            'text' => 'What kind of environment do you vibe with?',
            'multiple' => false,
            'has_budget_slider' => false,
        ]);

        $q2->options()->createMany([
            ['value' => 'quiet', 'label' => 'Quiet & Secluded', 'description' => 'Far from the crowds', 'emoji' => '🌿'],
            ['value' => 'mixed', 'label' => 'Half & Half', 'description' => 'I like peaceful moments and energy', 'emoji' => '🏙️'],
            ['value' => 'bustling', 'label' => 'Bustling & Lively', 'description' => 'I love busy streets and action', 'emoji' => '🎡'],
        ]);

        // Question 3
        $q3 = TravelPersonaQuestion::create([
            'key' => 'budgetPreference',
            'text' => 'Do you want us to filter trips by your budget?',
            'multiple' => false,
            'has_budget_slider' => true,
        ]);

        $q3->options()->createMany([
            ['value' => 'yes', 'label' => 'Yes, show me trips within my budget', 'emoji' => '✅', 'icon' => 'Check'],
            ['value' => 'no', 'label' => 'No, show me everything', 'emoji' => '⛔', 'icon' => 'DollarSign'],
        ]);

        // Question 4
        $q4 = TravelPersonaQuestion::create([
            'key' => 'planningStyle',
            'text' => 'How do you like to plan your trips?',
            'multiple' => false,
            'has_budget_slider' => false,
        ]);

        $q4->options()->createMany([
            ['value' => 'planned', 'label' => 'Fully Planned', 'description' => 'Detailed itineraries', 'emoji' => '📋', 'icon' => 'Calendar'],
            ['value' => 'flexible', 'label' => 'Flexible', 'description' => 'Rough plan with room for changes', 'emoji' => '🧭', 'icon' => 'Compass'],
            ['value' => 'spontaneous', 'label' => 'Spontaneous', 'description' => 'Go with the flow', 'emoji' => '🌊', 'icon' => 'Activity'],
        ]);
    }
}
