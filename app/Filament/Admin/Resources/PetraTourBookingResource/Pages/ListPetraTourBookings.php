<?php

namespace App\Filament\Admin\Resources\PetraTourBookingResource\Pages;

use App\Filament\Admin\Resources\PetraTourBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPetraTourBookings extends ListRecords
{
    protected static string $resource = PetraTourBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
