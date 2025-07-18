<?php

namespace App\Filament\Admin\Resources\MessageResource\Pages;

use App\Filament\Admin\Resources\MessageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMessages extends ListRecords
{
    protected static string $resource = MessageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
