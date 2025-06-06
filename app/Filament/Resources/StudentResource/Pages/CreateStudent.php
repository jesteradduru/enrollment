<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->user()->id;

        return $data;
    }

     protected function afterCreate(): void
    {
        $student = $this->record;
        $formatted = str_pad($student->id, 4, '0', STR_PAD_LEFT);

        $student->update([
            'school_id' => 102937 . $formatted
        ]);
    }
}
