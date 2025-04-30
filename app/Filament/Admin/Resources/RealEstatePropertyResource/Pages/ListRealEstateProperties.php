<?php

namespace App\Filament\Admin\Resources\RealEstatePropertyResource\Pages;

use App\Filament\Admin\Resources\RealEstatePropertyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRealEstateProperties extends ListRecords
{
    protected static string $resource = RealEstatePropertyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
