<?php

namespace App\Filament\Admin\Resources\TripResource\Pages;

use App\Filament\Admin\Resources\TripResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrip extends EditRecord
{
    protected static string $resource = TripResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
