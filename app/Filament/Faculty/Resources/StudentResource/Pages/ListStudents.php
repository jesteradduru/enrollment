<?php

namespace App\Filament\Faculty\Resources\StudentResource\Pages;

use App\Filament\Exports\StudentExporter;
use App\Filament\Faculty\Resources\StudentResource;
use App\Models\Student;
use Filament\Actions;
// use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()->exports([
                ExcelExport::make('table')->fromTable()->withFilename(date('Y-m-d') . ' - students'),
            ]),
        ];
    }
}
