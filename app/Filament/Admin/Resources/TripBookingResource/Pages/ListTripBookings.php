<?php

namespace App\Filament\Admin\Resources\TripBookingResource\Pages;

use App\Filament\Admin\Resources\TripBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTripBookings extends ListRecords
{
    protected static string $resource = TripBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
