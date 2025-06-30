<?php

namespace App\Filament\Admin\Resources\PremiumPaymentDetailResource\Pages;

use App\Filament\Admin\Resources\PremiumPaymentDetailResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPremiumPaymentDetail extends ViewRecord
{
    protected static string $resource = PremiumPaymentDetailResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
