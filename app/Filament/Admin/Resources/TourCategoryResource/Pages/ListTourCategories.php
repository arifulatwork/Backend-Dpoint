<?php

namespace App\Filament\Admin\Resources\TourCategoryResource\Pages;

use App\Filament\Admin\Resources\TourCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTourCategories extends ListRecords
{
    protected static string $resource = TourCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
