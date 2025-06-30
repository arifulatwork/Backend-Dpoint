<?php

namespace App\Filament\Admin\Resources\AttractionBookingResource\Pages;

use App\Filament\Admin\Resources\AttractionBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttractionBookings extends ListRecords
{
    protected static string $resource = AttractionBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
