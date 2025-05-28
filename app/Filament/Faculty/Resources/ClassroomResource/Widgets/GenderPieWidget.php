<?php

namespace App\Filament\Faculty\Resources\ClassroomResource\Widgets;

use App\Models\Classroom;
use Filament\Widgets\ChartWidget;

class GenderPieWidget extends ChartWidget
{
    protected static ?string $heading = 'Male vs Female';

    public ?Classroom $record;

    protected function getData(): array
    {
        $maleStudentsCount = $this->record->students()->where('gender', 'male')->get()->count();
        $femaleStudentsCount = $this->record->students()->where('gender', 'female')->get()->count();
        return [
            'labels' => [
                'Male', 'Female'
            ],
            'datasets' => [
                [
                    'label' => 'Gender Distribution',
                    'data' => [
                        $maleStudentsCount,
                        $femaleStudentsCount
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
