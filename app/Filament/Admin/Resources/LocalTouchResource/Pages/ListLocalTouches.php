<?php

namespace App\Filament\Admin\Resources\LocalTouchResource\Pages;

use App\Filament\Admin\Resources\LocalTouchResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocalTouches extends ListRecords
{
    protected static string $resource = LocalTouchResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
