<?php

namespace App\Filament\Faculty\Resources\StudentResource\Pages;

use App\Filament\Faculty\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
}
