<?php

namespace App\Filament\Admin\Resources\AttractionPaymentResource\Pages;

use App\Filament\Admin\Resources\AttractionPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttractionPayments extends ListRecords
{
    protected static string $resource = AttractionPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
