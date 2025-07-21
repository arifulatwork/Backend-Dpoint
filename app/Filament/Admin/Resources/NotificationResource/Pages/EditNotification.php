<?php

namespace App\Filament\Admin\Resources\NotificationResource\Pages;

use App\Filament\Admin\Resources\NotificationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotification extends EditRecord
{
    protected static string $resource = NotificationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
