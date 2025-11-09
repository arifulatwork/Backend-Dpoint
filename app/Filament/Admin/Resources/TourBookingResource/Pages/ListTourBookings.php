<?php

namespace App\Filament\Admin\Resources\TourBookingResource\Pages;

use App\Filament\Admin\Resources\TourBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTourBookings extends ListRecords
{
    protected static string $resource = TourBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
