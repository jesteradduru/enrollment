<?php

namespace App\Filament\Faculty\Resources\EnrollmentResource\Pages;

use App\Filament\Faculty\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected static ?string $title = 'Register Enrollee';

    protected static ?string $navigationLabel = 'Register';
}
