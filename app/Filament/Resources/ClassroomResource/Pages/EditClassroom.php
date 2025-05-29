<?php

namespace App\Filament\Resources\ClassroomResource\Pages;

use App\Filament\Resources\ClassroomResource;
use App\Models\Level;
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $level = Level::find($data['level_id']);

        $data['display_name'] = "{$level->level} - {$data['name']}";

        return $data;
    }
}
