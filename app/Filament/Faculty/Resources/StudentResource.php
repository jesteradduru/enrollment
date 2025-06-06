<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Faculty\Resources\StudentResource\Pages;
use App\Filament\Faculty\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $label = 'Student Record';

    protected static ?string $navigationGroup = 'Enrollment';
    
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

                // enrollment form
                //  Forms\Components\Select::make('enrollments.classroom')
                //     ->label('Section')
                //     ->relationship('enrollments.classroom', 'display_name')
                //     ->preload()
                //     ->searchable()
                //     ->required(),
                // Forms\Components\Select::make('enrollments.schoolYear')
                //     ->relationship('enrollments.schoolYear', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->required(),
                // Forms\Components\FileUpload::make('enrollments.documents')->multiple()->directory('enrollments')->openable(),

                Repeater::make('enrollments')
                    ->relationship()
                    ->label('Enrollment Details')
                    ->schema([
                        Forms\Components\Select::make('school_year_id')
                            ->label('School Year')
                            ->relationship('schoolYear', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('classroom_id')
                            ->label('Section')
                            ->relationship('classroom', 'display_name')
                            ->options(function (){
                                return auth()->user()->classrooms()->pluck('display_name', 'classroom_id');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\FileUpload::make('documents')
                            ->multiple()
                            ->directory('enrollments')
                            ->openable()
                            ->required()
                            ->columnSpanFull()
                            ->label('Documents'),
                    ])
                    ->collapsible()
                    ->columns(2)
                    ->itemLabel(fn ($state) => optional(\App\Models\SchoolYear::find(data_get($state, 'school_year_id')))?->name ?? 'Enrollment')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('school_id')->label('School ID')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('first_name')->searchable(),
                Tables\Columns\TextColumn::make('middle_name')->searchable(),
                Tables\Columns\TextColumn::make('extension_name')->searchable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('latestEnrollment.classroom.level.level')->searchable()->label('Grade Level'),
                Tables\Columns\TextColumn::make('latestEnrollment.classroom.name')->searchable()->label('Section'),
                Tables\Columns\TextColumn::make('latestEnrollment.classroom.faculty.name')->label('Adviser')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('latestEnrollment.created_at')->searchable()->label('Enrolled at')->date()->sortable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')->label('Grade Level')
                    ->relationship('latestEnrollment.classroom.level', 'level'),
                Tables\Filters\SelectFilter::make('classroom')->label('Section')
                    ->relationship('latestEnrollment.classroom', 'name'),
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
            ->defaultSort('created_at', 'desc')
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
            // 'view' => Pages\ViewStudent::route('/{record}/view'),

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('created_by', auth()->user()->id);
        
        // ->whereHas('classes.faculty', function (Builder $query) use ($faculty) {
        //         $query->where('faculty_id', 'like', "{$faculty->id}");
        //     });
    }
}
