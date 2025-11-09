<?php

namespace App\Filament\Admin\Resources\TourPaymentResource\Pages;

use App\Filament\Admin\Resources\TourPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTourPayments extends ListRecords
{
    protected static string $resource = TourPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
