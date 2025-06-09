<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Exports\StudentExporter;
use App\Filament\Resources\StudentResource;
use Filament\Actions;
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
        //    ExportAction::make()->exports([
        //         ExcelExport::make('table')->fromTable()->withFilename(date('Y-m-d') . ' - students'),
        //     ]),
        ];
    }
}
