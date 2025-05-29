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

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $label = 'Enrollee';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Enrollment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship(name:'student', modifyQueryUsing: function(Builder $query){
                        return $query->where('created_by', auth()->user()->id);
                    })
                    ->searchable(['first_name', 'last_name', 'middle_name', 'extension_name'])
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->middle_name} {$record->last_name}")
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('classroom_id')
                    ->label('Section')
                    ->options(function(){
                        $faculty = auth()->user();

                        return $faculty->classrooms()->get()->pluck('display_name', 'id');
                    })
                    ->required(),
                Forms\Components\Select::make('school_year_id')
                    ->relationship(name: 'schoolYear')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "SY {$record->start_year}-{$record->end_year}")
                    ->searchable(['start_year', 'end_year'])
                    ->preload()
                    // ->getSearchResultsUsing(fn (string $search): array =>
                    //     \App\Models\SchoolYear::where('start_year', 'like', "%{$search}%")
                    //         ->orWhere('end_year', 'like', "%{$search}%")
                    //         ->get()
                    //         ->mapWithKeys(fn ($sy) => [
                    //             $sy->id => $sy->display_name
                    //         ])->toArray()
                    // )
                    // ->getOptionLabelUsing(fn ($value): ?string => \App\Models\SchoolYear::find($value)?->display_name)
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

    public static function getEloquentQuery(): Builder
    {
        $faculty = auth()->user();
        return parent::getEloquentQuery()->whereHas('classroom.faculty', function (Builder $query) use ($faculty) {
                $query->where('faculty_id', 'like', "{$faculty->id}");
            });
    }
}
