<?php

namespace App\Filament\Admin\Resources\MontenegroTourResource\Pages;

use App\Filament\Admin\Resources\MontenegroTourResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMontenegroTour extends EditRecord
{
    protected static string $resource = MontenegroTourResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
