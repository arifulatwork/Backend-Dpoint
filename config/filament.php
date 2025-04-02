<?php

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Http\Middleware\MirrorConfigToSubpackages;
use Filament\Pages;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return [

    'path' => env('FILAMENT_PATH', 'admin'),
    'core_path' => env('FILAMENT_CORE_PATH', 'filament'),
    'domain' => env('FILAMENT_DOMAIN'),

    'home_url' => '/admin/dashboard', // Changed to admin dashboard

    'brand' => env('APP_NAME', 'Admin Panel'), // Updated brand name

    'auth' => [
        'guard' => 'admin', // Explicitly set to admin guard
        'pages' => [
            'login' => \Filament\Http\Livewire\Auth\Login::class,
        ],
        'user' => [
            'model' => \App\Models\Admin::class, // Point to Admin model
        ],
    ],

    'pages' => [
        'namespace' => 'App\\Filament\\Admin\\Pages',
        'path' => app_path('Filament/Admin/Pages'),
        'register' => [
            Pages\Dashboard::class,
        ],
    ],

    'resources' => [
        'namespace' => 'App\\Filament\\Admin\\Resources',
        'path' => app_path('Filament/Admin/Resources'),
        'register' => [],
    ],

    'widgets' => [
        'namespace' => 'App\\Filament\\Admin\\Widgets',
        'path' => app_path('Filament/Admin/Widgets'),
        'register' => [
            Widgets\AccountWidget::class,
            Widgets\FilamentInfoWidget::class,
        ],
    ],

    'livewire' => [
        'namespace' => 'App\\Filament\\Admin',
        'path' => app_path('Filament/Admin'),
    ],

    'dark_mode' => true, // Enabled dark mode

    'database_notifications' => [
        'enabled' => false, // Enabled notifications
        'polling_interval' => '30s',
    ],

    'layout' => [
        'actions' => [
            'modal' => [
                'actions' => [
                    'alignment' => 'left',
                ],
            ],
        ],
        'forms' => [
            'actions' => [
                'alignment' => 'left',
                'are_sticky' => true, // Sticky form actions
            ],
            'have_inline_labels' => true, // Better form layout
        ],
        'footer' => [
            'should_show_logo' => false, // Hide footer logo
        ],
        'max_content_width' => '7xl',
        'notifications' => [
            'vertical_alignment' => 'top',
            'alignment' => 'right',
        ],
        'sidebar' => [
            'is_collapsible_on_desktop' => true, // Collapsible sidebar
            'groups' => [
                'are_collapsible' => true,
            ],
            'width' => '18rem', // Wider sidebar
            'collapsed_width' => '5.5rem',
        ],
    ],

    'favicon' => null,

    'default_avatar_provider' => \Filament\AvatarProviders\UiAvatarsProvider::class,

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DRIVER', 'public'),

    'google_fonts' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', // Modern font

    'middleware' => [
        'auth' => [
            Authenticate::class,
        ],
        'base' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DispatchServingFilamentEvent::class,
            MirrorConfigToSubpackages::class,
        ],
    ],

];