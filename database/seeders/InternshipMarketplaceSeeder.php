<?php

namespace Database\Seeders;

use App\Models\Internship;
use App\Models\InternshipCategory;
use App\Models\InternshipLearningOutcome;
use App\Models\InternshipSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InternshipMarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Categories (use slug as stable key)
        $categories = [
            ['slug' => 'it',         'name' => 'Information Technology', 'icon' => 'briefcase'],
            ['slug' => 'management', 'name' => 'Business Management',    'icon' => 'users'],
            ['slug' => 'marketing',  'name' => 'Digital Marketing',       'icon' => 'building'],
            ['slug' => 'design',     'name' => 'UI/UX Design',            'icon' => 'award'],
            ['slug' => 'data',       'name' => 'Data Science',            'icon' => 'book-open'],
        ];

        foreach ($categories as $c) {
            InternshipCategory::updateOrCreate(
                ['slug' => $c['slug']],
                ['name' => $c['name'], 'icon' => $c['icon']]
            );
        }

        // Helper by NAME (so you can keep your internship array as-is)
        $catId = fn (string $categoryName) =>
            InternshipCategory::where('name', $categoryName)->value('id');

        // 2) Skills
        $skills = [
            'React','TypeScript','CSS','JavaScript',
            'Project Management','Agile','Scrum','JIRA',
            'Python','Machine Learning','SQL','Data Visualization',
            'SEO','Social Media','Content Marketing','Analytics',
            'Figma','User Research','Wireframing','Prototyping',
            'Excel','Tableau','Statistical Analysis',
        ];

        $skillIdByName = collect($skills)->mapWithKeys(function ($name) {
            $model = InternshipSkill::firstOrCreate(['name' => $name]);
            return [$name => $model->id];
        });

        // 3) Internships (matches your React mock data)
        $internships = [
            [
                'title' => 'Frontend Development Intern',
                'category' => 'Information Technology',
                'description' => 'Join our dynamic team to build cutting-edge web applications using React and TypeScript.',
                'duration' => '3 months',
                'price' => 299, 'original_price' => 399,
                'rating' => 4.8, 'review_count' => 142,
                'company' => 'TechInnovate Inc.',
                'location' => 'North America',
                'mode' => 'remote',
                'image' => 'https://picsum.photos/seed/frontend/600/400',
                'featured' => true,
                'deadline' => now()->addMonths(2)->toDateString(),
                'spots_left' => 5,
                'skills' => ['React', 'TypeScript', 'CSS', 'JavaScript'],
                'learning_outcomes' => [
                    'Master React and modern frontend frameworks',
                    'Learn to work in agile development teams',
                    'Build portfolio-worthy projects',
                ],
            ],
            [
                'title' => 'IT Project Management Intern',
                'category' => 'Business Management',
                'description' => 'Gain hands-on experience managing IT projects from conception to delivery.',
                'duration' => '4 months',
                'price' => 349,
                'rating' => 4.6, 'review_count' => 89,
                'company' => 'GlobalTech Solutions',
                'location' => 'Europe',
                'mode' => 'hybrid',
                'image' => 'https://picsum.photos/seed/pm/600/400',
                'featured' => true,
                'deadline' => null,
                'spots_left' => 8,
                'skills' => ['Project Management', 'Agile', 'Scrum', 'JIRA'],
                'learning_outcomes' => [
                    'Learn project management methodologies',
                    'Develop leadership and team coordination skills',
                    'Understand budgeting and resource allocation',
                ],
            ],
            [
                'title' => 'Data Science Internship',
                'category' => 'Data Science',
                'description' => 'Work with large datasets and build machine learning models in a real-world environment.',
                'duration' => '6 months',
                'price' => 449, 'original_price' => 549,
                'rating' => 4.9, 'review_count' => 217,
                'company' => 'DataInsights Corp',
                'location' => 'Asia',
                'mode' => 'on-site',
                'image' => 'https://picsum.photos/seed/datasci/600/400',
                'featured' => false,
                'deadline' => now()->addMonths(3)->toDateString(),
                'spots_left' => null,
                'skills' => ['Python', 'Machine Learning', 'SQL', 'Data Visualization'],
                'learning_outcomes' => [
                    'Master data cleaning and preprocessing techniques',
                    'Build and evaluate machine learning models',
                    'Create compelling data visualizations',
                ],
            ],
            [
                'title' => 'Digital Marketing Intern',
                'category' => 'Digital Marketing',
                'description' => 'Develop and execute digital marketing campaigns across various platforms.',
                'duration' => '3 months',
                'price' => 249,
                'rating' => 4.5, 'review_count' => 93,
                'company' => 'NextGen Media',
                'location' => 'Remote',
                'mode' => 'remote',
                'image' => 'https://picsum.photos/seed/marketing/600/400',
                'featured' => false,
                'deadline' => null,
                'spots_left' => null,
                'skills' => ['SEO', 'Social Media', 'Content Marketing', 'Analytics'],
                'learning_outcomes' => [
                    'Plan and execute multi-channel marketing campaigns',
                    'Analyze campaign performance with analytics tools',
                    'Optimize content for search engines',
                ],
            ],
            [
                'title' => 'UI/UX Design Intern',
                'category' => 'UI/UX Design',
                'description' => 'Create intuitive and beautiful user interfaces for our product suite.',
                'duration' => '4 months',
                'price' => 329, 'original_price' => 399,
                'rating' => 4.7, 'review_count' => 124,
                'company' => 'DesignCraft Studios',
                'location' => 'North America',
                'mode' => 'hybrid',
                'image' => 'https://picsum.photos/seed/uiux/600/400',
                'featured' => true,
                'deadline' => null,
                'spots_left' => 3,
                'skills' => ['Figma', 'User Research', 'Wireframing', 'Prototyping'],
                'learning_outcomes' => [
                    'Conduct user research and usability testing',
                    'Create wireframes and interactive prototypes',
                    'Design responsive interfaces for multiple devices',
                ],
            ],
            [
                'title' => 'Business Analytics Intern',
                'category' => 'Data Science',
                'description' => 'Help businesses make data-driven decisions through analytical insights.',
                'duration' => '5 months',
                'price' => 399,
                'rating' => 4.6, 'review_count' => 78,
                'company' => 'StrategyPlus Consultants',
                'location' => 'Europe',
                'mode' => 'remote',
                'image' => 'https://picsum.photos/seed/ba/600/400',
                'featured' => false,
                'deadline' => null,
                'spots_left' => null,
                'skills' => ['Excel', 'SQL', 'Tableau', 'Statistical Analysis'],
                'learning_outcomes' => [
                    'Transform raw data into actionable insights',
                    'Create dashboards and reports for stakeholders',
                    'Develop predictive models for business forecasting',
                ],
            ],
        ];

        foreach ($internships as $i) {
            // Create / update internship (use title+company as a natural key)
            $internship = Internship::updateOrCreate(
                ['title' => $i['title'], 'company' => $i['company']],
                [
                    'category_id'    => $catId($i['category']),
                    'description'    => $i['description'],
                    'duration'       => $i['duration'],
                    'price'          => $i['price'],
                    'original_price' => $i['original_price'] ?? null,
                    'rating'         => $i['rating'],
                    'review_count'   => $i['review_count'],
                    'location'       => $i['location'],
                    'mode'           => $i['mode'], // 'remote' | 'on-site' | 'hybrid'
                    'image'          => $i['image'], // store URL into image_path column
                    'featured'       => $i['featured'],
                    'deadline'       => $i['deadline'],
                    'spots_left'     => $i['spots_left'],
                ]
            );

            // Attach skills
            $skillIds = collect($i['skills'])
                ->map(fn ($name) => $skillIdByName[$name] ?? null)
                ->filter()
                ->values()
                ->all();

            if (method_exists($internship, 'skills')) {
                $internship->skills()->sync($skillIds);
            }

            // Learning outcomes
            if (!empty($i['learning_outcomes'])) {
                // Clear old outcomes on reseed
                if (class_exists(InternshipLearningOutcome::class)) {
                    InternshipLearningOutcome::where('internship_id', $internship->id)->delete();
                }

                foreach ($i['learning_outcomes'] as $outcome) {
                    InternshipLearningOutcome::create([
                        'internship_id' => $internship->id,
                        'outcome'       => $outcome,
                    ]);
                }
            }
        }
    }
}
