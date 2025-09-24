<?php

namespace App\Filament\Admin\Resources\InternshipLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\InternshipLearningOutcomeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternshipLearningOutcomes extends ListRecords
{
    protected static string $resource = InternshipLearningOutcomeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
