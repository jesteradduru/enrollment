<?php

namespace App\Filament\Exports;

use App\Models\Student;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class StudentExporter extends Exporter
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            // ExportColumn::make('id')
            //     ->label('ID'),
            // ExportColumn::make('created_at'),
            // ExportColumn::make('updated_at'),
            ExportColumn::make('first_name'),
            ExportColumn::make('middle_name'),
            ExportColumn::make('last_name'),
            ExportColumn::make('extension_name'),
            // ExportColumn::make('gender'),
            // ExportColumn::make('date_of_birth'),
            // ExportColumn::make('address'),
            // ExportColumn::make('last_school_attended'),
            // ExportColumn::make('last_school_address'),
            // ExportColumn::make('type'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your student export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
