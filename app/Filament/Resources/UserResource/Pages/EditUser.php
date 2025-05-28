<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;


    
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
