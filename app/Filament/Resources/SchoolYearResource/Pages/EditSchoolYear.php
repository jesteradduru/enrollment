<?php

namespace App\Filament\Resources\SchoolYearResource\Pages;

use App\Filament\Resources\SchoolYearResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchoolYear extends EditRecord
{
    protected static string $resource = SchoolYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {

        $data['name'] = "SY {$data['name']}";

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['name'] = str_replace('SY ', '', $data['name']);

        return $data;
    }
}
