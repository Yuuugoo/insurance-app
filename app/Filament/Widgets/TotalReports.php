<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Faker\Provider\ar_EG\Text;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TotalReports extends BaseWidget
{

    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Report::query()
            )
            ->columns([
                TextColumn::make('created_at')
                    ->searchable()
                    ->dateTime()
                    ->label('Date Created')
                    ->icon('heroicon-o-calendar-days'),
                TextColumn::make('cost_center')
                    ->label('Cost Center')
                    ->icon('heroicon-o-map-pin'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.')
                    ->searchable(),
                TextColumn::make('arpr_date')
                    ->label('AR/PR Date')
                    ->icon('heroicon-o-calendar-days'),
                TextColumn::make('plate_num')
                    ->label('Vehicle Plate No.')
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('policy_status')
                    ->label('Policy Status')
                    ->badge(),
            ]);
    }
}
