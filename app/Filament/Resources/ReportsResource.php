<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Report;
use App\Models\Reports;
use Filament\Forms\Form;
use App\Enums\CostCenter;
use Filament\Tables\Table;
use App\Enums\InsuranceProd;
use App\Enums\InsuranceType;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\ReportsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReportsResource\RelationManagers;

class ReportsResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $recordTitleAttribute = 'arpr_num';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Wizard\Step::make('Report Details')
                        ->schema([
                            TextInput::make('sale_person')
                                ->label('Sales Person'),
                            Select::make('cost_center')
                                ->label('Cost center')
                                ->options(CostCenter::class),
                            TextInput::make('arpr_num')
                                ->label('AR/PR No.'),
                            DatePicker::make('arpr_date')
                                ->label('AR/PR Date'),
                            Select::make('insurance_prod')
                                ->label('Insurance Provider')
                                ->options(InsuranceProd::class),
                            Select::make('insurance_type')
                                ->label('Insurance Provider')
                                ->options(InsuranceType::class),
                        ])
                            ->description('View Report Details')
                            ->columns(['md' => 2, 'xl' => 3]),
                    Wizard\Step::make('Delivery')
                        ->schema([
                            Fieldset::make('vehicles')
                                ->relationship('vehicles')
                                ->schema([
                                    TextInput::make('plate_num'),
                                    TextInput::make('policy_status'),
                                ])

                        ]),
                    Wizard\Step::make('Billing')
                        ->schema([
                            // ...
                        ]),
                ])->columnSpanFull()
                    

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime('d-M-Y')
                    ->label('Date Created'),
                TextColumn::make('sale_person')
                    ->label('Sales Person'),
                TextColumn::make('cost_center')
                    ->label('Cost Center'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.'),
                TextColumn::make('arpr_date')
                    ->label('AR/PR Date'),
                TextColumn::make('vehicles.plate_num')
                    ->label('Vehicle Plate No.'),
                TextColumn::make('vehicles.policy_status')
                    ->label('Policy Status'),


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
