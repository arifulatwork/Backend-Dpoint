<?php

namespace App\Filament\Admin\Resources\AccommodationAppointmentResource\Pages;

use App\Filament\Admin\Resources\AccommodationAppointmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccommodationAppointments extends ListRecords
{
    protected static string $resource = AccommodationAppointmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
