<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Filament\Resources\ActivityLogResource\RelationManagers;
use App\Models\ActivityLog;
use App\Models\Report;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?int $navigationSort = 6;
    protected static ?string $title = 'Activity Log';
    protected static ?string $navigationGroup = 'SETTINGS';
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $recordTitleAttribute = 'description';

    public static function canAccess(): bool
    {       
        $user = Auth::user();
        return $user->hasRole('acct-manager');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Panel::make([
                    Split::make([
                        ImageColumn::make('causer.avatar_url')
                            ->circular()
                            ->grow(false)
                            ->getStateUsing(function ($record) {
                                if ($record->causer) {
                                    return asset(url($record->causer->avatar_url));
                                }
                                return null;
                            }),
                        TextColumn::make('description')
                            ->formatStateUsing(function ($record) {
                                $username = $record->causer->name ?? 'Unknown User';
                                $action = strtolower($record->description);
                                $subjectType = class_basename($record->subject_type);
                                $arprNum = $record->subject->arpr_num ?? 'N/A';
                                $updatedAt = $record->created_at->format('m/d/Y h:i A');
                                return "{$username} {$action} {$subjectType} '{$arprNum}' at {$updatedAt}";
                            }),
                        
                            
                    ])->from('md'),
                ])->collapsed(false)
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('activities')
                        ->label('View Recent Changes')
                        ->button()
                        ->hidden(fn (Activity $record) => Auth::user()->hasAnyRole(['acct-staff', 'cashier']))
                        ->url(fn ($record) => route('filament.admin.resources.reports.activities', ['record' => $record->subject_id])),
                Tables\Actions\Action::make('view')
                        ->label('View Report')
                        ->color('info')
                        ->button()
                        ->hidden(fn (Activity $record) => Auth::user()->hasAnyRole(['acct-staff', 'cashier']))
                        ->url(fn ($record) => route('filament.admin.resources.reports.view', ['record' => $record->subject_id]))
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListActivityLogs::route('/'),
            'create' => Pages\CreateActivityLog::route('/create'),
            'edit' => Pages\EditActivityLog::route('/{record}/edit'),
        ];
    }
}
