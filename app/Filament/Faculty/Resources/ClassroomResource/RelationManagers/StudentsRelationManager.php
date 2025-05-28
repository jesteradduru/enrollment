<?php

namespace App\Filament\Faculty\Resources\ClassroomResource\RelationManagers;

use App\Filament\Exports\StudentExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('last_name')->searchable()
                    ->label('Surname'),
                Tables\Columns\TextColumn::make('first_name')->searchable()
                    ->label('First Name'),
                Tables\Columns\TextColumn::make('middle_name')->searchable()
                    ->label('Middle Name'),
                Tables\Columns\TextColumn::make('extension_name')->searchable()
                    ->label('Ext'),
                Tables\Columns\TextColumn::make('gender')->label('Sex'),
                Tables\Columns\TextColumn::make('age'),
                Tables\Columns\TextColumn::make('date_of_birth')->date(),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('enrollments.created_at')
                    ->label('Enrollment Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                ->badge()
                ->color(function ($state){
                    return match($state){
                        "new" => 'success',
                        "regular" => 'info',
                        "transferee" => 'warning',
                    };
                }),
            ])
            ->filters([
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                // Tables\Actions\ExportAction::make()->exporter(StudentExporter::class)
                // ->fileDisk('public')
                // ->formats([
                //     ExportFormat::Xlsx,
                //     ExportFormat::Csv,
                // ]),
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
}
