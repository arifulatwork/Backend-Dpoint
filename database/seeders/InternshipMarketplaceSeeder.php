<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    InternshipLocation,
    InternshipField,
    InternshipCompany,
    InternshipService,
    InternshipCondition,
    InternshipApplication
};

class InternshipMarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        // First clear applications that reference companies
        InternshipApplication::query()->delete();

        // Locations
        InternshipLocation::query()->delete();

        $locations = [
            [
                'slug' => 'spain',
                'country' => 'Spain',
                'cities' => ['Barcelona', 'Madrid', 'Valencia'],
                'flag' => '🇪🇸',
                'popular' => true,
            ],
            [
                'slug' => 'uk',
                'country' => 'England',
                'cities' => ['London', 'Manchester', 'Edinburgh'],
                'flag' => '🇬🇧',
                'popular' => true,
            ],
            [
                'slug' => 'usa',
                'country' => 'United States',
                'cities' => ['New York', 'Miami', 'Los Angeles'],
                'flag' => '🇺🇸',
                'popular' => true,
            ],
            [
                'slug' => 'argentina',
                'country' => 'Argentina',
                'cities' => ['Buenos Aires', 'Córdoba', 'Mendoza'],
                'flag' => '🇦🇷',
                'popular' => false,
            ],
            [
                'slug' => 'france',
                'country' => 'France',
                'cities' => ['Paris', 'Lyon', 'Marseille'],
                'flag' => '🇫🇷',
                'popular' => false,
            ],
            [
                'slug' => 'germany',
                'country' => 'Germany',
                'cities' => ['Berlin', 'Munich', 'Hamburg'],
                'flag' => '🇩🇪',
                'popular' => false,
            ],
        ];

        foreach ($locations as $loc) {
            InternshipLocation::create($loc);
        }

        // Fields
        InternshipField::query()->delete();

        InternshipField::insert([
            [
                'slug' => 'technology',
                'name' => 'Technology & IT',
                'description' => 'Software development, IT support, cybersecurity',
            ],
            [
                'slug' => 'business',
                'name' => 'Business & Marketing',
                'description' => 'Marketing, sales, business development',
            ],
            [
                'slug' => 'hospitality',
                'name' => 'Hospitality & Tourism',
                'description' => 'Hotels, tourism, event management',
            ],
            [
                'slug' => 'education',
                'name' => 'Education',
                'description' => 'Teaching, educational administration',
            ],
            [
                'slug' => 'healthcare',
                'name' => 'Healthcare',
                'description' => 'Medical, nursing, healthcare administration',
            ],
            [
                'slug' => 'engineering',
                'name' => 'Engineering',
                'description' => 'Civil, mechanical, electrical engineering',
            ],
        ]);

        // Companies
        InternshipCompany::query()->delete();

        InternshipCompany::insert([
            [
                'name' => 'DPrealeste - Real Estate Research',
                'logo_url' => '/api/placeholder/80/80',
                'location' => 'Barcelona, Spain',
                'field_slug' => 'business',
                'rating' => 4.8,
                'reviews' => 124,
                'work_mode' => 'hybrid',
                'duration' => '4-6 months',
                'hours' => '20-30h/week',
            ],
            [
                'name' => 'DPrealeste - Digital Marketing',
                'logo_url' => '/api/placeholder/80/80',
                'location' => 'Barcelona, Spain',
                'field_slug' => 'business',
                'rating' => 4.6,
                'reviews' => 89,
                'work_mode' => 'hybrid',
                'duration' => '4-6 months',
                'hours' => '20-30h/week',
            ],
            [
                'name' => 'Meet & Eat - Event Coordination',
                'logo_url' => '/api/placeholder/80/80',
                'location' => 'Barcelona, Spain',
                'field_slug' => 'hospitality',
                'rating' => 4.7,
                'reviews' => 67,
                'work_mode' => 'offline',
                'duration' => '3-6 months',
                'hours' => '15-25h/week',
            ],
            [
                'name' => 'Electronic Software Solutions',
                'logo_url' => '/api/placeholder/80/80',
                'location' => 'Remote',
                'field_slug' => 'technology',
                'rating' => 4.9,
                'reviews' => 156,
                'work_mode' => 'online',
                'duration' => '3-12 months',
                'hours' => 'Flexible',
            ],
            [
                'name' => 'Dpoint Group - Business Development',
                'logo_url' => '/api/placeholder/80/80',
                'location' => 'Multiple Locations',
                'field_slug' => 'business',
                'rating' => 4.5,
                'reviews' => 78,
                'work_mode' => 'hybrid',
                'duration' => '4-8 months',
                'hours' => '20-35h/week',
            ],
        ]);

        // Services
        InternshipService::query()->delete();

        InternshipService::insert([
            [
                'slug' => 'cv-enhancement',
                'name' => 'CV Enhancement',
                'description' => 'Professional CV redesign and content optimization by our experts',
                'price' => 100,
                'original_price' => null,
                'popular' => false,
            ],
            [
                'slug' => 'placement',
                'name' => 'Internship Placement',
                'description' => 'Guaranteed placement in a company that matches your profile',
                'price' => 390,
                'original_price' => 490,
                'popular' => true,
            ],
            [
                'slug' => 'premium-package',
                'name' => 'Premium Package',
                'description' => 'CV Enhancement + Placement (Best Value)',
                'price' => 290,
                'original_price' => 490,
                'popular' => false,
            ],
        ]);

        // Conditions
        InternshipCondition::query()->delete();

        InternshipCondition::insert([
            [
                'slug' => 'age',
                'text' => 'To be 18 years old or over',
                'required' => true,
            ],
            [
                'slug' => 'language',
                'text' => 'To have a high level in the language of the country where you will carry out your internship or to have intermediate level English',
                'required' => true,
            ],
            [
                'slug' => 'visa',
                'text' => 'To have a valid student visa or a valid passport for the corresponding country',
                'required' => true,
            ],
            [
                'slug' => 'university',
                'text' => 'To be able to provide a university form/agreement to be signed with the company',
                'required' => true,
            ],
        ]);
    }
}
