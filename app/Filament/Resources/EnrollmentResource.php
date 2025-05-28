<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassModelResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use App\Models\Enrollment;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $label = 'Enrollee';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'full_name')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array =>
                        Student::where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('extension_name', 'like', "%{$search}%")
                            ->limit(10)
                            ->get()
                            ->mapWithKeys(fn ($student) => [
                                $student->id => $student->full_name
                            ])->toArray()
                    )
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('classroom_id')
                    ->label('Section')
                    ->relationship('classroom', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('school_year_id')
                    ->relationship('schoolYear', 'end_year')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array =>
                        \App\Models\SchoolYear::where('start_year', 'like', "%{$search}%")
                            ->orWhere('end_year', 'like', "%{$search}%")
                            ->get()
                            ->mapWithKeys(fn ($sy) => [
                                $sy->id => $sy->display_name
                            ])->toArray()
                    )
                    ->getOptionLabelUsing(fn ($value): ?string => \App\Models\SchoolYear::find($value)?->display_name)
                    ->required(),
                Forms\Components\FileUpload::make('documents')->multiple()->directory('enrollments')->openable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.full_name')->label('Student')
                ->searchable(),
                Tables\Columns\TextColumn::make('student.gender')->label('Gender'),
                Tables\Columns\TextColumn::make('classroom.level.level')->label('Grade Level'),
                Tables\Columns\TextColumn::make('classroom.name'),
                Tables\Columns\TextColumn::make('schoolYear.display_name')->label('School Year'),
                Tables\Columns\TextColumn::make('created_at')->label('Enrolled at')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('classroom_id')
                    ->relationship('classroom', 'name')
                    ->label('Section'),
                Tables\Filters\SelectFilter::make('level')
                    ->relationship('classroom.level', 'level')
                    ->label('Grade Level'),
                Tables\Filters\SelectFilter::make('school_year_id')
                    ->relationship('schoolYear', 'start_year')
                    ->label('Start of School Year'),
                Tables\Filters\SelectFilter::make('student')
                    ->label('Gender')
                    ->relationship('student', 'gender')
                    ->preload()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
           
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }
}
