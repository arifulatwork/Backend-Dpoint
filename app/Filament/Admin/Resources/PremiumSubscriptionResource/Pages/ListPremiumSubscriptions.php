<?php

namespace App\Filament\Admin\Resources\PremiumSubscriptionResource\Pages;

use App\Filament\Admin\Resources\PremiumSubscriptionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPremiumSubscriptions extends ListRecords
{
    protected static string $resource = PremiumSubscriptionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
