<?php

namespace App\Filament\Admin\Resources\LocalTouchPaymentResource\Pages;

use App\Filament\Admin\Resources\LocalTouchPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocalTouchPayment extends EditRecord
{
    protected static string $resource = LocalTouchPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
