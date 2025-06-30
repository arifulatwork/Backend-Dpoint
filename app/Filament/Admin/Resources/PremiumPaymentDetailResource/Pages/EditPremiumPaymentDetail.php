<?php

namespace App\Filament\Admin\Resources\PremiumPaymentDetailResource\Pages;

use App\Filament\Admin\Resources\PremiumPaymentDetailResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPremiumPaymentDetail extends EditRecord
{
    protected static string $resource = PremiumPaymentDetailResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
