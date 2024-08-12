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
use App\Models\CostCenter;
use Filament\Facades\Filament;
use Filament\Forms\Get;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'ADMIN';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute = 'name';

    public static function canAccess(): bool
    {       
        $user = Auth::user();
        return $user->hasRole(['super-admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // Super Admin Create Users
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('roles')
                    ->relationship('roles', 'name', function ($query) {
                        return $query->where('name', '!=', 'super-admin');
                    })
                    ->native(false),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
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
                TextInput::make('avatar_url')
                    ->readOnly(fn () => Auth::user()->hasRole('super-admin'))
                    ->maxLength(255)
                    ->dehydrated(true)
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        if (empty($state)) {
                            $component->state('/storage/default_avatar/default.png');
                        }
                    })
                    ->dehydrateStateUsing(function ($state) {
                        return $state ?: '/storage/default_avatar/default.png';
                    }),
                Select::make('branch_id')
                    ->label('Branch')
                    ->helperText('Select the branch this user belongs to')
                    ->nullable()
                    ->options(CostCenter::all()->pluck('name','cost_center_id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('avatar_url')
                        ->grow(false)
                        ->circular()
                        ->getStateUsing(function ($record) {
                            if ($record->avatar_url) {
                                return asset(url($record->avatar_url));
                            }
                        
                            return Filament::getUserAvatarUrl($record);
                        }),
                    TextColumn::make('name')
                        ->searchable()
                        ->weight(FontWeight::Bold),
                ]),
                Panel::make([
                    Split::make([
                        TextColumn::make('username')
                            ->searchable()
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-s-user')
                            ->description('Username', position: 'below'),
                        TextColumn::make('costCenter.name')
                            ->description('Assigned Branch', position: 'below')
                            ->searchable()
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-s-map-pin'),
                        TextColumn::make('roles.name')
                            ->searchable()
                            ->weight(FontWeight::Bold)
                            ->grow()
                            ->icon('heroicon-s-flag')
                            ->description('Role', position: 'below'),
                        Tables\Columns\TextColumn::make('email')
                            ->searchable()
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-s-envelope')
                            ->description('Email', position: 'below'),
                        Stack::make([
                            Tables\Columns\TextColumn::make('created_at')
                                ->date('m-d-Y')
                                ->weight(FontWeight::SemiBold)
                                ->icon('heroicon-s-calendar-days')
                                ->description('Date Created', position: 'below'),
                            Tables\Columns\TextColumn::make('updated_at')
                                ->date('m-d-Y')
                                ->weight(FontWeight::SemiBold)
                                ->icon('heroicon-s-calendar-days')
                                ->description('Date Updated', position: 'below'),
                        ]),
                    ])->from('md'),
                ])->collapsed(false)
            ])
            ->filters([
                Filter::make('cost_center')
                    ->form([
                        Select::make('branch_id')
                            ->label('Branches')
                            ->placeholder('Select Branch')
                            ->options(CostCenter::all()->pluck('name','cost_center_id'))
                            ->native(false)
                            ->reactive()
                            ->searchable()
                            ->multiple(),
                        Select::make('roles')
                            ->label('Roles')
                            ->placeholder('Select Role')
                            ->options(Role::all()->pluck('name', 'name'))
                            ->native(false)
                            ->multiple(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['roles'],
                                fn (Builder $query, array $roles) => $query->whereHas('roles', function ($q) use ($roles) {
                                    $q->whereIn('name', $roles);
                                })
                            )
                            ->when(
                                $data['branch_id'],
                                fn (Builder $query, array $branchIds) => $query->whereIn('branch_id', $branchIds)
                            );
                    }),
                TrashedFilter::make()
                    ->placeholder('All Users')
                    ->label('Archived')
                    ->trueLabel('All Users w/ Archived')
                    ->falseLabel('Archived Users'),
            ])
            ->actions([
                ActionGroup::make([
                    RestoreAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color('info'),
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
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
