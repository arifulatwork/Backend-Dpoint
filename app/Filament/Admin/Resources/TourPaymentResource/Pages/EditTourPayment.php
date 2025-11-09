<?php

namespace App\Filament\Admin\Resources\TourPaymentResource\Pages;

use App\Filament\Admin\Resources\TourPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTourPayment extends EditRecord
{
    protected static string $resource = TourPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
