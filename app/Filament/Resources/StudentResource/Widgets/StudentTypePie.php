<?php

namespace App\Filament\Resources\StudentResource\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class StudentTypePie extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Student Types';

    protected function getData(): array
    {

        $schoolYearId = $this->filters['school_year'];
        $section = $this->filters['section'];
        $level = $this->filters['level'];
        $new = 0;
        $regular = 0;
        $transferee = 0;
        $returnee = 0;


        $new = Enrollment::whereLike('classroom_id', "%{$section}%")->whereLike('school_year_id', "%{$schoolYearId}%")
        ->whereHas('classroom', function (Builder $query) use ($level) {
            $query->where('level_id', 'like', "%{$level}%");
        })
        ->whereHas('classroom.faculty', function (Builder $query){
            if(auth()->user()->role == 'faculty')
            return $query->where('faculty_id', auth()->user()->id);
        })
        ->whereHas('student', function (Builder $query) {
            $query->where('type', 'like', 'new');
        })->get()->count();

        $regular = Enrollment::whereLike('classroom_id', "%{$section}%")->whereLike('school_year_id', "%{$schoolYearId}%")
        ->whereHas('classroom', function (Builder $query) use ($level) {
            $query->where('level_id', 'like', "%{$level}%");
        })
        ->whereHas('classroom.faculty', function (Builder $query){
            if(auth()->user()->role == 'faculty')
            return $query->where('faculty_id', auth()->user()->id);
        })
        ->whereHas('student', function (Builder $query) {
            $query->where('type', 'like', 'regular');
        })->get()->count();

        $transferee = Enrollment::whereLike('classroom_id', "%{$section}%")->whereLike('school_year_id', "%{$schoolYearId}%")
        ->whereHas('classroom', function (Builder $query) use ($level) {
            $query->where('level_id', 'like', "%{$level}%");
        })
        ->whereHas('classroom.faculty', function (Builder $query){
            if(auth()->user()->role == 'faculty')
            return $query->where('faculty_id', auth()->user()->id);
        })
        ->whereHas('student', function (Builder $query) {
            $query->where('type', 'like', 'transferee');
        })->get()->count();

        $returnee = Enrollment::whereLike('classroom_id', "%{$section}%")->whereLike('school_year_id', "%{$schoolYearId}%")
        ->whereHas('classroom', function (Builder $query) use ($level) {
            $query->where('level_id', 'like', "%{$level}%");
        })
        ->whereHas('classroom.faculty', function (Builder $query){
            if(auth()->user()->role == 'faculty')
            return $query->where('faculty_id', auth()->user()->id);
        })
        ->whereHas('student', function (Builder $query) {
            $query->where('type', 'like', 'returnee');
        })->get()->count();

        return [
            'labels' => [
                'New', 'Regular', 'Transferee', 'Returnee'
            ],
            'datasets' => [
                [
                    'label' => 'Gender Distribution',
                    'data' => [
                        $new,
                        $regular,
                        $transferee,
                        $returnee,
                    ],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(245, 158, 11)',
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
