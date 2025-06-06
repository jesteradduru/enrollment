<?php

namespace App\Filament\Resources\SchoolYearResource\Pages;

use App\Filament\Resources\SchoolYearResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSchoolYear extends CreateRecord
{
    protected static string $resource = SchoolYearResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['name'] = "SY {$data['name']}";

        return $data;
    }
}
