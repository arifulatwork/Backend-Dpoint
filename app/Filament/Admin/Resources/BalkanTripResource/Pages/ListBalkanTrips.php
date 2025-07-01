<?php

namespace App\Filament\Admin\Resources\BalkanTripResource\Pages;

use App\Filament\Admin\Resources\BalkanTripResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBalkanTrips extends ListRecords
{
    protected static string $resource = BalkanTripResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
