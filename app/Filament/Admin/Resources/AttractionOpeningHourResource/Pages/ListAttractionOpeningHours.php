<?php

namespace App\Filament\Admin\Resources\AttractionOpeningHourResource\Pages;

use App\Filament\Admin\Resources\AttractionOpeningHourResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttractionOpeningHours extends ListRecords
{
    protected static string $resource = AttractionOpeningHourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
