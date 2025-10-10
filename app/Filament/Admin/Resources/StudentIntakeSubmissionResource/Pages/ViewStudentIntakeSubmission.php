<?php

namespace App\Filament\Admin\Resources\StudentIntakeSubmissionResource\Pages;

use App\Filament\Admin\Resources\StudentIntakeSubmissionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions;

class ViewStudentIntakeSubmission extends ViewRecord
{
    protected static string $resource = StudentIntakeSubmissionResource::class;

    /**
     * In Filament v2, provide the schema array directly.
     * ViewRecord will render it in read-only mode.
     */
    protected function getFormSchema(): array
    {
        return StudentIntakeSubmissionResource::formSchema();
    }

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getHeading(): string
    {
        /** @var \App\Models\StudentIntakeSubmission $record */
        $record = $this->record;

        return 'View: ' . ($record->full_name ?? 'Student Intake Submission');
    }
}
