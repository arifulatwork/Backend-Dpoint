<?php

namespace App\Filament\Admin\Resources\InternshipCategoryResource\Pages;

use App\Filament\Admin\Resources\InternshipCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternshipCategory extends EditRecord
{
    protected static string $resource = InternshipCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
