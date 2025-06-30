<?php

namespace App\Filament\Admin\Resources\PointOfInterestResource\Pages;

use App\Filament\Admin\Resources\PointOfInterestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPointOfInterest extends EditRecord
{
    protected static string $resource = PointOfInterestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
