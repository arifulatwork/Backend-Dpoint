<?php

namespace App\Filament\Admin\Resources\ConnectionResource\Pages;

use App\Filament\Admin\Resources\ConnectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConnection extends EditRecord
{
    protected static string $resource = ConnectionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
