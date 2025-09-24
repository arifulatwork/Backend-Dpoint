<?php

namespace App\Filament\Admin\Resources\InternshipResource\Pages;

use App\Filament\Admin\Resources\InternshipResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternship extends EditRecord
{
    protected static string $resource = InternshipResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
