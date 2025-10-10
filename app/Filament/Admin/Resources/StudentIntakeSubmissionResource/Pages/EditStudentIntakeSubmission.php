<?php

namespace App\Filament\Admin\Resources\StudentIntakeSubmissionResource\Pages;

use App\Filament\Admin\Resources\StudentIntakeSubmissionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentIntakeSubmission extends EditRecord
{
    protected static string $resource = StudentIntakeSubmissionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
