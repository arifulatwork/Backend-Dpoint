<?php

namespace App\Filament\Admin\Resources\GuideResource\Pages;

use App\Filament\Admin\Resources\GuideResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGuide extends CreateRecord
{
    protected static string $resource = GuideResource::class;
}
