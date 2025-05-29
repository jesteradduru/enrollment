<?php

namespace App\Filament\Resources\StudentResource\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class GenderPieWidget extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Male vs Female';

    protected function getData(): array
    {

        $schoolYearId = $this->filters['school_year'];
        $section = $this->filters['section'];
        $level = $this->filters['level'];
        $isFaculty = auth()->user()->role== 'faculty';

        $maleStudents = Enrollment::whereLike('classroom_id', "%{$section}%")->whereLike('school_year_id', "%{$schoolYearId}%")
        ->whereHas('classroom', function (Builder $query) use ($level) {
            $query->where('level_id', 'like', "%{$level}%");
        })
        ->whereHas('classroom.faculty', function (Builder $query) use($isFaculty){
                if($isFaculty)
                return $query->where('faculty_id', auth()->user()->id);
            })
        ->whereHas('student', function (Builder $query) {
            $query->where('gender', 'like', 'male');
        })->get()->count();

        $femaleStudents = Enrollment::whereLike('classroom_id', "%{$section}%")->whereLike('school_year_id', "%{$schoolYearId}%")
        ->whereHas('classroom', function (Builder $query) use ($level) {
            $query->where('level_id', 'like', "%{$level}%");
        })
        ->whereHas('classroom.faculty', function (Builder $query) use($isFaculty){
                if($isFaculty)
                return $query->where('faculty_id', auth()->user()->id);
            })
        ->whereHas('student', function (Builder $query) {
            $query->where('gender', 'like', 'female');
        })->get()->count();

        return [
            'labels' => [
                'Male', 'Female'
            ],
            'datasets' => [
                [
                    'label' => 'Gender Distribution',
                    'data' => [
                        $maleStudents,
                        $femaleStudents
                    ],
                    'backgroundColor' => [
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                    ],
                    'hoverOffset' => '4'
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected static ?string $maxHeight = '300px';
    
}
