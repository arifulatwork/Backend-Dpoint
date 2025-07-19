<?php

namespace App\Filament\Admin\Resources\MontenegroTourResource\Pages;

use App\Filament\Admin\Resources\MontenegroTourResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMontenegroTours extends ListRecords
{
    protected static string $resource = MontenegroTourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
