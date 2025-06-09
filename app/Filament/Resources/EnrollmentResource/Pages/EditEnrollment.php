<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Student;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }

     protected function afterSave(): void
    {
        $student = Student::find($this->record->student_id);
        $formatted = str_pad($student->id, 4, '0', STR_PAD_LEFT);

        $student->update([
            'school_id' => 10293723 . $formatted
        ]);
    }
}
