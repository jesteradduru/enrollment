<?php



namespace App\Filament\Faculty\Resources\EnrollmentResource\Pages;

use App\Filament\Exports\EnrollmentExporter;
use App\Filament\Faculty\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Register Enrollee'),
            // ExportAction::make()->exports([
            //     ExcelExport::make('table')->fromTable()->withFilename(date('Y-m-d') . ' - enrollees'),
            // ]),
        ];
    }
}
