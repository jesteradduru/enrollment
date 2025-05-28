<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $label = 'Student Record';
    
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')->options([
                    'new' => 'New',
                    'regular' => 'Regular',
                    'transferee' => 'Transferee',
                    'returnee' => 'Returnee'
                ])->required()
                ->default('new')
                ->live(),
                 Forms\Components\TextInput::make('first_name')->required() ->autocapitalize('words'),
                Forms\Components\TextInput::make('middle_name') ->autocapitalize('words'),
                Forms\Components\TextInput::make('last_name')->required() ->autocapitalize('words'),
                Forms\Components\TextInput::make('extension_name') ->autocapitalize('words'),
                Forms\Components\Select::make('gender')->options([
                    'male' => 'Male',
                    'female' => 'Female'
                ])->required(),
                Forms\Components\DatePicker::make('date_of_birth')->required(),
                Forms\Components\Textarea::make('address')->required(),
                Forms\Components\TextInput::make('last_school_attended')
                    ->label('Last School Attended')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'transferee')
                    ->required(fn (Forms\Get $get) => $get('type') === 'transferee'),
                Forms\Components\Textarea::make('last_school_address')
                    ->label('Last School Attended Address')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'transferee')
                    ->required(fn (Forms\Get $get) => $get('type') === 'transferee'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('last_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('first_name')->searchable(),
                Tables\Columns\TextColumn::make('middle_name')->searchable(),
                Tables\Columns\TextColumn::make('extenstion_name')->searchable(),
                Tables\Columns\TextColumn::make('enrollments.classroom.level.level')->searchable()->label('Grade Level'),
                Tables\Columns\TextColumn::make('enrollments.classroom.name')->searchable()->label('Section'),
                Tables\Columns\TextColumn::make('enrollments.created_at')->searchable()->label('Enrolled at')->date()->sortable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')->label('Grade Level')
                    ->relationship('enrollments.classroom.level', 'level'),
                Tables\Filters\SelectFilter::make('classroom')->label('Section')
                    ->relationship('enrollments.classroom', 'name'),
                Tables\Filters\SelectFilter::make('type')->label('Student Type')
                    ->options([
                        'new' => 'New',
                        'regular' => 'Regular',
                        'transferee' => 'Transferee',
                        'returnee' => 'Returnee',
                    ]),
                Tables\Filters\SelectFilter::make('gender')->label('Sex')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     dd(auth()->user()->classes());
    //     // $student = auth()->user()->students();
    //     return parent::getEloquentQuery()->where('role', 'faculty');
    // }
}
