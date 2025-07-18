<?php

namespace App\Filament\Admin\Resources\ConnectionResource\Pages;

use App\Filament\Admin\Resources\ConnectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConnections extends ListRecords
{
    protected static string $resource = ConnectionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
