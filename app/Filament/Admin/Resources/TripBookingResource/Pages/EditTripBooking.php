<?php

namespace App\Filament\Admin\Resources\TripBookingResource\Pages;

use App\Filament\Admin\Resources\TripBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTripBooking extends EditRecord
{
    protected static string $resource = TripBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
