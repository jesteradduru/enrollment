<?php

namespace App\Filament\Resources\StudentResource\Widgets;

use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Student;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class TotalStudents extends BaseWidget
{

    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $schoolYearId = $this->filters['school_year'];
        $section = $this->filters['section'];
        $level = $this->filters['level'];
        
        $schoolYear = null;
        // dd($schoolYearId);
        

        $enrollments = Enrollment::whereLike('classroom_id', "%{$section}%")
        ->whereLike('school_year_id', "%{$schoolYearId}%")
        ->whereHas('classroom', function (Builder $query) use ($level) {
            $query->where('level_id', 'like', "%{$level}%");
        })
        ->get();
        
        $studentsEnrolled = $enrollments->count();


        
        $maleStudents = Enrollment::whereLike('classroom_id', "%{$section}%")->whereLike('school_year_id', "%{$schoolYearId}%")
        ->whereHas('classroom', function (Builder $query) use ($level) {
            $query->where('level_id', 'like', "%{$level}%");
        })
        ->whereHas('student', function (Builder $query) {
            $query->where('gender', 'like', 'male');
        })->get()->count();
    


        $femaleStudents = Enrollment::whereLike('classroom_id', "%{$section}%")->whereLike('school_year_id', "%{$schoolYearId}%")
        ->whereHas('classroom', function (Builder $query) use ($level) {
            $query->where('level_id', 'like', "%{$level}%");
        })
        ->whereHas('student', function (Builder $query) {
            $query->where('gender', 'like', 'female');
        })->get()->count();
        
        return [
            Stat::make('No of Students Record', $studentsEnrolled)
            ->icon('heroicon-o-users'),
            Stat::make('Male', $maleStudents)
            ->icon('heroicon-o-users'),
            Stat::make('Female', $femaleStudents)
            ->icon('heroicon-o-users'),
        ];
    }
}
