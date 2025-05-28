<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Faculty\Resources\ClassroomResource\Pages;
use App\Filament\Faculty\Resources\ClassroomResource\RelationManagers\StudentsRelationManager;
use App\Models\Classroom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomResource extends Resource
{
    protected static ?string $label = 'Advisory Class';

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Section')->searchable(),
                Tables\Columns\TextColumn::make('level.level')->label('Grade level'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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
            StudentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassrooms::route('/'),
            // 'create' => Pages\CreateClassroom::route('/create'),
            'view' => Pages\ViewClassroom::route('/{record}'),
            // 'edit' => Pages\EditClassroom::route('/{record}/edit'),
        ];
    }

            public static function getEloquentQuery(): Builder
        {
            // dd(auth()->user()->classrooms()->get());
            if(auth()->user()->role === 'faculty'){
                $classrooms = auth()->user()->classrooms()->get()->pluck('id');

                return Classroom::whereIn('id', $classrooms);
            }
            // $student = auth()->user()->students();
            return parent::getEloquentQuery();
        }
}