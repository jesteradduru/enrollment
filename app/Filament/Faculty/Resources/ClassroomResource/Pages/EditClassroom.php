<?php

namespace App\Filament\Faculty\Resources\ClassroomResource\Pages;

use App\Filament\Faculty\Resources\ClassroomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroom extends EditRecord
{
    protected static string $resource = ClassroomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
