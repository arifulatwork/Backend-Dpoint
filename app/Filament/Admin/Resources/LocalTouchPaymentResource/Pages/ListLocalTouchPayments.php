<?php

namespace App\Filament\Admin\Resources\LocalTouchPaymentResource\Pages;

use App\Filament\Admin\Resources\LocalTouchPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocalTouchPayments extends ListRecords
{
    protected static string $resource = LocalTouchPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
