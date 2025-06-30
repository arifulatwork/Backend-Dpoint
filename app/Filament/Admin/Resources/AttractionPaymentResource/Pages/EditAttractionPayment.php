<?php

namespace App\Filament\Admin\Resources\AttractionPaymentResource\Pages;

use App\Filament\Admin\Resources\AttractionPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttractionPayment extends EditRecord
{
    protected static string $resource = AttractionPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
