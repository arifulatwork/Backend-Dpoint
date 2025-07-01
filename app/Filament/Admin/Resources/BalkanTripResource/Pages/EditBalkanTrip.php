<?php

namespace App\Filament\Admin\Resources\BalkanTripResource\Pages;

use App\Filament\Admin\Resources\BalkanTripResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBalkanTrip extends EditRecord
{
    protected static string $resource = BalkanTripResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
