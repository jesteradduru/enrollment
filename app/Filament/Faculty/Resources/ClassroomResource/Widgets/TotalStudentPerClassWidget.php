<?php

namespace App\Filament\Faculty\Resources\ClassroomResource\Widgets;

use App\Models\Classroom;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalStudentPerClassWidget extends BaseWidget
{
    public ?Classroom $record;
    
    protected function getStats(): array
    {
        $record = $this->record->loadCount('students');
        $maleStudentsCount = $this->record->students()->where('gender', 'male')->get()->count();
        $femaleStudentsCount = $this->record->students()->where('gender', 'female')->get()->count();
        return [
            Stat::make('Total Students', $record->students_count)
                ->description('Students enrolled on this class')
                ->icon('heroicon-o-users')
                ->color('success'),
            Stat::make('Male', $maleStudentsCount)
                ->color('info')
                ->icon('heroicon-o-users'),
            Stat::make('Female', $femaleStudentsCount)
                ->color('primary')
                ->icon('heroicon-o-users'),
        ];
    }
}
