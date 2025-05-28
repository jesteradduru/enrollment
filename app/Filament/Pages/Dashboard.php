<?php
namespace App\Filament\Pages;

use App\Models\Classroom;
use App\Models\Level;
use App\Models\SchoolYear;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Dashboard Filters')->schema([
                Select::make('school_year')->label('Start of School Year')->options(function(){
                return SchoolYear::all()->pluck('start_year', 'id');
                }),
                Select::make('level')->options(function(){
                    return Level::all()->pluck('level', 'id');
                })->label('Grade Level'),
                Select::make('section')->options(function(){
                    return Classroom::all()->pluck('name', 'id');
                }),
            ])->columns(3)
        ]);
    }
}