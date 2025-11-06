<?php

namespace App\Filament\Admin\Resources\AttractionOpeningHourResource\Pages;

use App\Filament\Admin\Resources\AttractionOpeningHourResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttractionOpeningHour extends EditRecord
{
    protected static string $resource = AttractionOpeningHourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
