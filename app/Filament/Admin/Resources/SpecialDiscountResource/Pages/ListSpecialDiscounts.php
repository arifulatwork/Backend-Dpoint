<?php

namespace App\Filament\Admin\Resources\SpecialDiscountResource\Pages;

use App\Filament\Admin\Resources\SpecialDiscountResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecialDiscounts extends ListRecords
{
    protected static string $resource = SpecialDiscountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
