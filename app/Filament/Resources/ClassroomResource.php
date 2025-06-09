<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
use App\Filament\Resources\ClassroomResource\RelationManagers;
use App\Filament\Resources\ClassroomResource\RelationManagers\FacultyRelationManager;
// use App\Filament\Resources\ClassroomResource\RelationManagers\FacultyRelationManager;
use App\Models\Classroom;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomResource extends Resource
{
    protected static ?string $label = 'Section';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Academics';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\Select::make('level_id')
                        ->relationship('level', 'level')
                        ->required(),

                    // Repeater::make('faculty')
                    //     ->label('Assigned Faculty')
                    //     ->relationship('faculty') // your belongsToMany() in Classroom model
                    //     ->schema([
                    //         Select::make('id')
                    //             ->label('Faculty Member')
                    //             ->options(User::query()->where('role', 'faculty')->pluck('name', 'id'))
                    //             ->searchable()
                    //             ->required(),
                    //     ])
                    //     ->defaultItems(1)
                    //     ->addActionLabel('Add Faculty')
                    //     ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_name')->searchable(),
                // Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('level.level')->label('Grade level'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
             FacultyRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassrooms::route('/'),
            'create' => Pages\CreateClassroom::route('/create'),
            'edit' => Pages\EditClassroom::route('/{record}/edit'),
        ];
    }
}
