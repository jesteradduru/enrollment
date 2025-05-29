<?php

namespace App\Filament\Faculty\Resources\EnrollmentResource\Pages;

use App\Filament\Faculty\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Register Enrollee'),
        ];
    }
}
