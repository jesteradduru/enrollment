<?php

namespace App\Filament\Resources\AdministratorResource\Pages;

use App\Filament\Resources\AdministratorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdministrator extends CreateRecord
{
    protected static string $resource = AdministratorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = "{$data['first_name']} {$data['middle_name']} {$data['last_name']} {$data['extension_name']}";

        return $data;
    }
}
