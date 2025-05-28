<?php

namespace App\Filament\Resources\EnrollmentResource\Widgets;

use App\Models\Enrollment;
use App\Models\SchoolYear;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class EnrolleesPerSchoolYearWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Enrollees Per School Year';

    protected function getData(): array
    {
        $schoolYears = SchoolYear::orderBy('start_year')->get();
        $section = $this->filters['section'];
        $level = $this->filters['level'];


        $labels = [];
        $data = [];

        foreach ($schoolYears as $sy) {
            $label = "SY {$sy->start_year}-{$sy->end_year}";
            $labels[] = $label;

            $data[] = Enrollment::
            whereHas('classroom', function (Builder $query) use ($level) {
                $query->where('level_id', 'like', "%{$level}%");
            })
            ->whereLike('classroom_id', "%{$section}%")
            ->where('school_year_id', $sy->id)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Enrollees',
                    'data' => $data,
                    'fill' => false,
                    'borderColor' => '#3b82f6', // Tailwind blue-500
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';
    

    protected function getType(): string
    {
        return 'line';
    }
}
