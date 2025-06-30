<?php

namespace App\Filament\Admin\Resources\PointOfInterestResource\Pages;

use App\Filament\Admin\Resources\PointOfInterestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPointOfInterests extends ListRecords
{
    protected static string $resource = PointOfInterestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
