<?php

namespace App\Filament\Admin\Resources\PremiumTierResource\Pages;

use App\Filament\Admin\Resources\PremiumTierResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPremiumTier extends EditRecord
{
    protected static string $resource = PremiumTierResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
