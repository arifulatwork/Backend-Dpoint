<?php

namespace App\Filament\Admin\Resources\ShortTripsPaymentResource\Pages;

use App\Filament\Admin\Resources\ShortTripsPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShortTripsPayment extends EditRecord
{
    protected static string $resource = ShortTripsPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
