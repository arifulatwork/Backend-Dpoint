<?php

namespace App\Filament\Admin\Resources\ConnectionResource\Pages;

use App\Filament\Admin\Resources\ConnectionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateConnection extends CreateRecord
{
    protected static string $resource = ConnectionResource::class;
}
