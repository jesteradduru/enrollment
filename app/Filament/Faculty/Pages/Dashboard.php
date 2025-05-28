<?php
namespace App\Filament\Faculty\Pages;

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
        $faculty = auth()->user();
        // dd($faculty->classrooms());

        return $form->schema([
            Section::make('Dashboard Filters')->schema([
                Select::make('school_year')->label('Start of School Year')->options(function(){
                    return SchoolYear::all()->pluck('start_year', 'id');
                }),
                Select::make('level')->options(function()use($faculty){
                    return Level::whereIn('id', $faculty->classrooms()->get()->pluck('level_id'))->get()->pluck('level', 'id');
                })->label('Grade Level'),
                Select::make('section')->options(function()use($faculty){
                    return $faculty->classrooms()->get()->pluck('name','id');
                }),
            ])->columns(3)
        ]);
    }
}