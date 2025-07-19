<?php

namespace App\Filament\Admin\Resources\PetraTourBookingResource\Pages;

use App\Filament\Admin\Resources\PetraTourBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPetraTourBooking extends EditRecord
{
    protected static string $resource = PetraTourBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
