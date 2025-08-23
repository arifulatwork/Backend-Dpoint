<?php

namespace App\Filament\Admin\Resources\GuideResource\Pages;

use App\Filament\Admin\Resources\GuideResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuide extends EditRecord
{
    protected static string $resource = GuideResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
