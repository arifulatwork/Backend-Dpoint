<?php

namespace App\Filament\Admin\Resources\LocalTouchBookingResource\Pages;

use App\Filament\Admin\Resources\LocalTouchBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocalTouchBookings extends ListRecords
{
    protected static string $resource = LocalTouchBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
