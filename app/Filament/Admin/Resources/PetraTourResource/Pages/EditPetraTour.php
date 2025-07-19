<?php

namespace App\Filament\Admin\Resources\PetraTourResource\Pages;

use App\Filament\Admin\Resources\PetraTourResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPetraTour extends EditRecord
{
    protected static string $resource = PetraTourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
