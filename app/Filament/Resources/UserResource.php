<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'ADMIN';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute = 'roles.name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // Super Admin Create Users
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('roles')
                    ->relationship('roles', 'name', function ($query) {
                        return $query->where('name', '!=', 'super-admin');
                    })
                    ->native(false),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(static fn(null|string $state):
                        null|string =>
                        filled($state) ? hash('sha512', $state) : null,
                    )
                    ->required()
                    ->maxLength(255),
                TextInput::make('username')
                    ->required()
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->description('Name', position: 'above')
                    ->searchable()
                    ->icon('heroicon-o-user'),
                
                Panel::make([
                    Split::make([
                        TextColumn::make('username')
                            ->searchable()
                            ->icon('heroicon-o-user')
                            ->description('username', position: 'below'),
                        TextColumn::make('roles.name')
                            ->icon('heroicon-o-flag')
                            ->description('role', position: 'below'),
                        Tables\Columns\TextColumn::make('email')
                            ->searchable()
                            ->icon('heroicon-o-envelope')
                            ->description('email', position: 'below'),
                        Tables\Columns\TextColumn::make('created_at')
                            ->date('m-d-Y')
                            ->sortable()
                            ->description('date created', position: 'below'),
                        Tables\Columns\TextColumn::make('updated_at')
                            ->date('m-d-Y')
                            ->sortable()
                            ->description('date updated', position: 'below'),
                    ])->from('md'),
                ])->collapsed(false)
            ])
            ->filters([
                TrashedFilter::make()
                    ->placeholder('All Users')
                    ->label('Archived')
                    ->trueLabel('All Users w/ Deleted')
                    ->falseLabel('Deleted Users'),
            ])
            ->actions([
                ActionGroup::make([
                    RestoreAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->label('Archive')
                        ->icon('heroicon-m-archive-box-arrow-down')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Archive this User?')
                        ->modalIcon('heroicon-m-archive-box-arrow-down')
                        ->successNotificationTitle('User Archived Successfully')
                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
