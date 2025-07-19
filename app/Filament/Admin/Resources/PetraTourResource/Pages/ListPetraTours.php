<?php

namespace App\Filament\Admin\Resources\PetraTourResource\Pages;

use App\Filament\Admin\Resources\PetraTourResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPetraTours extends ListRecords
{
    protected static string $resource = PetraTourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
