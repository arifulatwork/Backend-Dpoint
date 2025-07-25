<?php

namespace App\Filament\Admin\Resources\MessageResource\Pages;

use App\Filament\Admin\Resources\MessageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMessage extends EditRecord
{
    protected static string $resource = MessageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
