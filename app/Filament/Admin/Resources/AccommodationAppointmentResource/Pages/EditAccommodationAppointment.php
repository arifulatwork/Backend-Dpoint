<?php

namespace App\Filament\Admin\Resources\AccommodationAppointmentResource\Pages;

use App\Filament\Admin\Resources\AccommodationAppointmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccommodationAppointment extends EditRecord
{
    protected static string $resource = AccommodationAppointmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
