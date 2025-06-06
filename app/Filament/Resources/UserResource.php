<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\ClassesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\ClassroomsRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Pest\Laravel\options;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Users';

    protected static ?string $label = 'Teacher';

    // protected static ?string $pluralLabel = 'Faculty Staff';

    protected static ?string $pluralModelLabel = null;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->label('First Name')
                    ->required(),

                TextInput::make('middle_name')
                    ->label('Middle Name'),

                TextInput::make('last_name')
                    ->label('Last Name'),

                TextInput::make('extension_name')
                    ->label('Extension'),
                Forms\Components\Select::make('gender')->options(['male' => 'Male', 'female'=>'Female']),
                Forms\Components\DatePicker::make('date_of_birth'),
                Forms\Components\TextInput::make('position')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')->password()->revealable()->dehydrated(fn ($state) => filled($state)),
                Forms\Components\Select::make('role')
                    ->options([
                        'faculty' => 'Teacher',
                    ])
                    ->default('faculty')
                    ->readOnly(),


                Forms\Components\Select::make('classroom_id')
                            ->label('Advisory Class')
                             ->relationship('classrooms', 'display_name')
                            ->searchable()
                            ->multiple()
                            ->preload()
                            ->required(),



                    // Repeater::make('classrooms')
                    // ->relationship()
                    // ->label('Advisory Classes')
                    // ->schema([
                    //     Forms\Components\Select::make('classroom_id')
                    //         ->label('Section')
                    //          ->relationship('classrooms', 'display_name')
                    //         ->searchable()
                    //         ->preload()
                    //         ->required(),
                    // ])
                    // ->collapsible()
                    // ->columns(2)
                    // ->columnSpanFull()
                    // ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('first_name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('middle_name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('last_name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('extension_name')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            // ClassroomsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'faculty');
    }
}
