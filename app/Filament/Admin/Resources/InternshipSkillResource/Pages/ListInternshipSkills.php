<?php

namespace App\Filament\Admin\Resources\InternshipSkillResource\Pages;

use App\Filament\Admin\Resources\InternshipSkillResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternshipSkills extends ListRecords
{
    protected static string $resource = InternshipSkillResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
