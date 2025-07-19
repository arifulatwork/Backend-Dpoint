<?php

namespace App\Filament\Admin\Resources\MontenegroTourBookingResource\Pages;

use App\Filament\Admin\Resources\MontenegroTourBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMontenegroTourBookings extends ListRecords
{
    protected static string $resource = MontenegroTourBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
