<?php

namespace App\Filament\Admin\Resources\LocalTouchBookingResource\Pages;

use App\Filament\Admin\Resources\LocalTouchBookingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocalTouchBooking extends EditRecord
{
    protected static string $resource = LocalTouchBookingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
