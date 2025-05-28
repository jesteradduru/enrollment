<?php

namespace App\Filament\Faculty\Resources\ClassroomResource\Pages;

use App\Filament\Faculty\Resources\ClassroomResource;
use App\Filament\Faculty\Resources\ClassroomResource\Widgets\FemaleStudentWidget;
use App\Filament\Faculty\Resources\ClassroomResource\Widgets\GenderPieWidget;
use App\Filament\Faculty\Resources\ClassroomResource\Widgets\MaleStudentWidget;
use App\Filament\Faculty\Resources\ClassroomResource\Widgets\TotalStudentPerClassWidget;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClassroom extends ViewRecord
{
    protected static string $resource = ClassroomResource::class;


    public function getHeaderWidgetsColumns(): int | array
    {
        return 3;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TotalStudentPerClassWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            GenderPieWidget::class,
        ];
    }
    
}
