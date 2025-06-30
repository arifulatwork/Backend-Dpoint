<?php

namespace App\Filament\Admin\Resources\ShortTripsPaymentResource\Pages;

use App\Filament\Admin\Resources\ShortTripsPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShortTripsPayments extends ListRecords
{
    protected static string $resource = ShortTripsPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
