<?php

namespace App\Filament\Admin\Resources\AttractionResource\Pages;

use App\Filament\Admin\Resources\AttractionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttractions extends ListRecords
{
    protected static string $resource = AttractionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
