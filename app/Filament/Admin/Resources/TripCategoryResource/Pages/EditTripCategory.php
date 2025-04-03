<?php

namespace App\Filament\Admin\Resources\TripCategoryResource\Pages;

use App\Filament\Admin\Resources\TripCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTripCategory extends EditRecord
{
    protected static string $resource = TripCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
