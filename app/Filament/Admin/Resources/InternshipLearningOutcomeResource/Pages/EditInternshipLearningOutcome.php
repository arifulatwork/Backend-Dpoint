<?php

namespace App\Filament\Admin\Resources\InternshipLearningOutcomeResource\Pages;

use App\Filament\Admin\Resources\InternshipLearningOutcomeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternshipLearningOutcome extends EditRecord
{
    protected static string $resource = InternshipLearningOutcomeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
