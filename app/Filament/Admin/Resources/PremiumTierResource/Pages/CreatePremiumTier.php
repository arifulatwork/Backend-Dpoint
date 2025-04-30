<?php

namespace App\Filament\Admin\Resources\PremiumTierResource\Pages;

use App\Filament\Admin\Resources\PremiumTierResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePremiumTier extends CreateRecord
{
    protected static string $resource = PremiumTierResource::class;
}
