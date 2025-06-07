<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Faculty\Resources\EnrollmentResource\Pages;
use App\Filament\Faculty\Resources\EnrollmentResource\RelationManagers;
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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $label = 'Enrollee';

    protected static ?string $navigationGroup = 'Enrollment';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('enrollment.student')
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
                    ->required(),
                Forms\Components\Select::make('classroom_id')
                    ->label('Section')
                    ->relationship('classroom', 'display_name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('school_year_id')
                    ->relationship('schoolYear', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\FileUpload::make('documents')->multiple()->directory('enrollments')->openable()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.school_id')->label('Schoold ID'),
                Tables\Columns\TextColumn::make('student.full_name')->label('Student')
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->whereHas('student', function (Builder $query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('extension_name', 'like', "%{$search}%");
                    });
                }),
                Tables\Columns\TextColumn::make('student.gender')->label('Gender'),
                Tables\Columns\TextColumn::make('student.date_of_birth')->label('Birthdate (mm-dd-yyyy)')->date('m-d-Y')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('student.age')->label('Age')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('student.address')->label('Address')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('classroom.level.level')->label('Grade Level'),
                Tables\Columns\TextColumn::make('classroom.name')->label('Section'),
                Tables\Columns\TextColumn::make('classroom.faculty.name')->label('Adviser')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('schoolYear.name')->label('School Year'),
                Tables\Columns\TextColumn::make('created_at')->label('Enrolled at')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('classroom_id')
                    ->relationship('classroom', 'display_name')
                    ->label('Section'),
                Tables\Filters\SelectFilter::make('level')
                    ->relationship('classroom.level', 'level')
                    ->label('Grade Level'),
                Tables\Filters\SelectFilter::make('school_year_id')
                    ->relationship('schoolYear', 'name')
                    ->label('School Year'),
               Tables\Filters\SelectFilter::make('gender')
                    ->label('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ])
                    ->query(function ($query, array $data): Builder {
                          if (!isset($data['value']) || $data['value'] === '') {
                                return $query;
                            }
                        return $query->whereHas('student', function ($q) use ($data) {
                            $q->where('gender', $data['value']);
                        });
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make()
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
                // ExportBulkAction::make()
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
            // 'view' => Pages\ViewEnrollment::route('/{record}/view'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $faculty = auth()->user();
        return parent::getEloquentQuery()->whereHas('classroom.faculty', function (Builder $query) use ($faculty) {
                $query->where('faculty_id', 'like', "{$faculty->id}");
            });
    }
}
