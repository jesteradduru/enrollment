<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassModelResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use App\Models\Enrollment;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
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
                Section::make('Enrollment Details')->schema([
                    Forms\Components\Select::make('student_id')
                        ->relationship('student', 'full_name')
                        ->searchable()
                        ->preload()
                        // ->getSearchResultsUsing(fn (string $search): array =>
                        //     Student::where('first_name', 'like', "%{$search}%")
                        //         ->orWhere('middle_name', 'like', "%{$search}%")
                        //         ->orWhere('last_name', 'like', "%{$search}%")
                        //         ->orWhere('extension_name', 'like', "%{$search}%")
                        //         ->limit(10)
                        //         ->get()
                        //         ->mapWithKeys(fn ($student) => [
                        //             $student->id => $student->full_name
                        //         ])->toArray()
                        // )
                        ->required(),
                    Forms\Components\Select::make('classroom_id')
                        ->label('Section')
                        ->relationship('classroom', 'display_name')
                        ->preload()
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('school_year_id')
                        ->relationship('schoolYear', 'name')
                        ->preload()
                        ->searchable()
                        ->required(),
                ])->columns(2)->columnSpanFull(),
                Section::make('Documents')->schema([
                    Forms\Components\FileUpload::make('psa')->multiple()->label('PSA Birth Certificate')->directory('enrollments')->openable(),
                    Forms\Components\FileUpload::make('form137')->multiple()->label('Form 137')->directory('enrollments')->openable(),
                    Forms\Components\FileUpload::make('report_card')->multiple()->directory('enrollments')->openable(),
                    // Forms\Components\FileUpload::make('documents')->label('Other Requirements')->multiple()->directory('enrollments')->openable(),
                ])->columns(2)->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('student.school_id')->label('Learner\'s ID'),
                  Tables\Columns\TextColumn::make('classroom.level.level')->label('Code'),
                Tables\Columns\TextColumn::make('student.full_name')->label('Student Name')
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->whereHas('student', function (Builder $query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('extension_name', 'like', "%{$search}%");
                    });
                }),
                Tables\Columns\TextColumn::make('classroom.level.type')->label('Level'),
                Tables\Columns\TextColumn::make('classroom.level.grade')->label('Grade'),
                Tables\Columns\TextColumn::make('classroom.name')->label('Section'),
               
                Tables\Columns\TextColumn::make('student.date_of_birth')->label('Birthdate (mm-dd-yyyy)')->date('m-d-Y')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('student.age')->label('Age')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('student.address')->label('Address')->toggleable()->toggledHiddenByDefault(),
                
                Tables\Columns\TextColumn::make('classroom.faculty.name')->label('Adviser')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('created_at')->label('Date Enrolled')->date(),
                Tables\Columns\TextColumn::make('student.gender')->label('Gender'),
                Tables\Columns\TextColumn::make('schoolYear.name')->label('School Year'),
                Tables\Columns\TextColumn::make('documents_status')->view('tables.columns.enrollment-docs')->label('Documents'),
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
            'view' => Pages\ViewEnrollment::route('/{record}/view'),
        ];
    }

}
