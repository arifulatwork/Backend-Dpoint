<?php

namespace App\Filament\Admin\Resources\AttractionResource\Pages;

use App\Filament\Admin\Resources\AttractionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttraction extends EditRecord
{
    protected static string $resource = AttractionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
