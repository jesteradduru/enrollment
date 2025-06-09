<?php

namespace App\Filament\Faculty\Resources\EnrollmentResource\Pages;

use App\Filament\Faculty\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Student;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected static ?string $title = 'Register Enrollee';

    protected static ?string $navigationLabel = 'Register';

    protected function handleRecordCreation(array $data): Model
    {
        // dd($data);
        $studentId = $data['student_id'];
    $schoolYearId = $data['school_year_id'];

    // Check for duplicate enrollment
    $alreadyEnrolled = Enrollment::where('student_id', $studentId)
        ->where('school_year_id', $schoolYearId)
        ->exists();

    if ($alreadyEnrolled) {
        Notification::make()
            ->title('Student already enrolled in this school year.')
            ->danger()
            ->send();

        $this->halt(); // Stops creation
        return static::getModel()::create($data);
    }

    // $currentSY = SchoolYear::find($schoolYearId);
    // if (!$currentSY) return static::getModel()::create($data);

    // $lastSY = SchoolYear::where('start_year', $currentSY->start_year - 1)->first();
    // $lastSYId = $lastSY?->id;

    // $enrolledLastYear = Enrollment::where('student_id', $studentId)
    //     ->where('school_year_id', $lastSYId)
    //     ->exists();

    // $previousEnrollments = Enrollment::where('student_id', $studentId)
    //     ->where('school_year_id', '<', $schoolYearId)
    //     ->exists();

    // if ($enrolledLastYear) {
    //     Student::where('id', $studentId)->update(['type' => 'regular']);
    // } elseif (!$enrolledLastYear && $previousEnrollments) {
    //     Student::where('id', $studentId)->update(['type' => 'returnee']);
    // }

        return static::getModel()::create($data);
    }

    protected function afterCreate(): void
    {
        $student = Student::find($this->record->student_id);
        $formatted = str_pad($student->id, 4, '0', STR_PAD_LEFT);

        $student->update([
            'school_id' => 10293723 . $formatted
        ]);
    }
}
