<?php
namespace App\Filament\Faculty\Pages;

use App\Models\Classroom;
use App\Models\Level;
use App\Models\SchoolYear;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
                Select::make('school_year')->label('School Year')->options(function(){
                    return SchoolYear::all()->pluck('name', 'id');
                }),
                Select::make('level')->options(function()use($faculty){
                    return Level::whereIn('id', $faculty->classrooms()->get()->pluck('level_id'))->get()->pluck('level', 'id');
                })->label('Grade Level')->hidden(),
                Select::make('section')->options(function(Get $get)use($faculty){
                    return $faculty->classrooms()->whereLike('level_id', $get('level'))->pluck('display_name', 'classroom_id');
                })->hidden(),
            ])->columns(3)
        ]);
    }
}