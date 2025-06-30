<?php

namespace App\Filament\Admin\Resources\AttractionBookingResource\Pages;

use App\Filament\Admin\Resources\AttractionBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttractionBooking extends EditRecord
{
    protected static string $resource = AttractionBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
