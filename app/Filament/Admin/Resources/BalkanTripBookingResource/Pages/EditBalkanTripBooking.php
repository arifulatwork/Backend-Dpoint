<?php

namespace App\Filament\Admin\Resources\BalkanTripBookingResource\Pages;

use App\Filament\Admin\Resources\BalkanTripBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBalkanTripBooking extends EditRecord
{
    protected static string $resource = BalkanTripBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
