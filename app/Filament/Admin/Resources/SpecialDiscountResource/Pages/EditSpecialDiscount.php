<?php

namespace App\Filament\Admin\Resources\SpecialDiscountResource\Pages;

use App\Filament\Admin\Resources\SpecialDiscountResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecialDiscount extends EditRecord
{
    protected static string $resource = SpecialDiscountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
