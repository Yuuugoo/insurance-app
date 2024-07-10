<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportsResource\Pages;
use App\Filament\Resources\ReportsResource\RelationManagers;
use App\Models\Report;
use App\Models\Reports;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportsResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('sale_person')
                    ->label('Sales Person'),
                TextInput::make('cost_center')
                    ->label('Cost Center'),
                TextInput::make('arpr_num')
                    ->label('AR/PR No.'),
                DatePicker::make('arpr_date')
                    ->label('AR/PR Date'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale_person')
                    ->label('Sales Person'),
                TextColumn::make('cost_center')
                    ->label('Cost Center'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.'),
                TextColumn::make('arpr_date')
                    ->label('AR/PR Date'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReports::route('/create'),
            'edit' => Pages\EditReports::route('/{record}/edit'),
        ];
    }
}
