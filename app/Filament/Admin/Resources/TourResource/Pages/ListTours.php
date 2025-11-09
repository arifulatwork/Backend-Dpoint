<?php

namespace App\Filament\Admin\Resources\TourResource\Pages;

use App\Filament\Admin\Resources\TourResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTours extends ListRecords
{
    protected static string $resource = TourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
