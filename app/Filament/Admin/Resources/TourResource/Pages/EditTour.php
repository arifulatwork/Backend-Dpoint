<?php

namespace App\Filament\Admin\Resources\TourResource\Pages;

use App\Filament\Admin\Resources\TourResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTour extends EditRecord
{
    protected static string $resource = TourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
