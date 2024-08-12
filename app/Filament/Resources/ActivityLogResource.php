<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ActivityLogExporter;
use App\Filament\Resources\ActivityLogResource\Pages;
use App\Filament\Resources\ActivityLogResource\RelationManagers;
use App\Models\ActivityLog;
use App\Models\Report;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $breadcrumb = 'Audits';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationLabel = 'Audit Trail';
    protected static ?string $title = 'Audit Trail';
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
                // Panel::make([
                    Split::make([
                        // Image Column Bug. User needs to have an avatar for activity logs to be viewed
                        ImageColumn::make('causer.avatar_url')
                            ->circular()
                            ->grow(false)
                            ->getStateUsing(function ($record) {
                                if ($record->causer) {
                                    return asset(url($record->causer->avatar_url));
                                }
                            }),
                        TextColumn::make('description')
                            ->formatStateUsing(function ($record) {
                                $username = $record->causer->name ?? 'Unknown User';
                                $action = strtolower($record->description);
                                $subjectType = class_basename($record->subject_type);
                                $arprNum = $record->subject->arpr_num ?? 'N/A';
                                $updatedAt = $record->created_at->format('m/d/Y h:i A');
                                return "<strong>{$username} {$action}</strong> {$subjectType} '<strong>{$arprNum}</strong>' at {$updatedAt}";
                            })
                            ->html(),
                    ])->from('md'),
                // ])->collapsed(false)
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')
                            ->label('From')
                            ->placeholder('Select start date')
                            ->native(false)
                            ->displayFormat('m.d.Y')
                            ->format('Y-m-d'),
                        DatePicker::make('until')
                            ->label('To')
                            ->placeholder('Select end date')
                            ->native(false)
                            ->displayFormat('m.d.Y')
                            ->format('Y-m-d'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                function (Builder $query, $date) {
                                    return $query->whereDate('created_at', '>=', $date);
                                }
                            )
                            ->when(
                                $data['until'],
                                function (Builder $query, $date) {
                                    return $query->whereDate('created_at', '<=', $date);
                                }
                            );
                    }),
            ],layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\Action::make('activities')
                        ->label('View Recent Changes')
                        ->color('aap-blue')
                        ->button()
                        ->hidden(fn (Activity $record) => Auth::user()->hasAnyRole(['acct-staff', 'cashier']))
                        ->url(fn ($record) => route('filament.admin.resources.reports.activities', ['record' => $record->subject_id])),
                Tables\Actions\Action::make('view')
                        ->label('View Report')
                        ->color('aap-blue')
                        ->hidden(fn (Activity $record) => Auth::user()->hasAnyRole(['acct-staff', 'cashier']))
                        ->url(fn ($record) => route('filament.admin.resources.reports.view', ['record' => $record->subject_id]))
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(ActivityLogExporter::class)
                    ->hidden(fn () => Auth::user()->hasRole(['agent', 'cashier']))
                    ->label('Export All Records')
                    ->color('aap-blue')
                    ->columnMapping(false)
                    ->chunkSize(250)
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(ActivityLogExporter::class)
                        ->label('Export Selected Records')
                        ->color('success')
                        ->hidden(fn () => Auth::user()->hasRole(['agent', 'cashier']))
                        ->columnMapping(false)
                        ->chunkSize(250)
                        ->formats([
                            ExportFormat::Xlsx,
                        ])
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
            'index' => Pages\ListActivityLogs::route('/'),
            'create' => Pages\CreateActivityLog::route('/create'),
            'edit' => Pages\EditActivityLog::route('/{record}/edit'),
        ];
    }
}
