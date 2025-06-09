<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }

    // protected function afterSave(): void
    // {
    //     $student = $this->record;
    //     $formatted = str_pad($student->id, 4, '0', STR_PAD_LEFT);

    //     $student->update([
    //         'school_id' => 102937 . $formatted
    //     ]);
    // }
}
