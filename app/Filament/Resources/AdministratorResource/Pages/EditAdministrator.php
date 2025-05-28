<?php

namespace App\Filament\Resources\AdministratorResource\Pages;

use App\Filament\Resources\AdministratorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdministrator extends EditRecord
{
    protected static string $resource = AdministratorResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['name'] = "{$data['first_name']} {$data['middle_name']} {$data['last_name']} {$data['extension_name']}";

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
