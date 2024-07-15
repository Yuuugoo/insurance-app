<?php

namespace App\Livewire;

use Filament\Tables;
use Pages\EditReports;
use Pages\CreateReports;
use App\Enums\CostCenter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\ReportsResource;
use App\Filament\Resources\ReportsResource\Pages\EditReports as PagesEditReports;
use App\Filament\Resources\ReportsResource\Pages\ViewReports;
use Filament\Widgets\TableWidget as BaseWidget;

class ReportsTable extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                ReportsResource::getEloquentQuery()
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')

            ->columns([
                TextColumn::make('created_at')
                    ->searchable()
                    ->dateTime()
                    ->label('Date Created')
                    ->icon('heroicon-o-calendar-days'),
                // TextColumn::make('sale_person')
                //     ->label('Sales Person')
                //     ->icon('heroicon-o-user')
                //     ->visibleFrom('md'),
                TextColumn::make('cost_center')
                    ->label('Cost Center')
                    ->icon('heroicon-o-map-pin'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.')
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('arpr_date')
                    ->label('AR/PR Date')
                    ->icon('heroicon-o-calendar-days')
                    ->visibleFrom('md'),
                TextColumn::make('plate_num')
                    ->label('Vehicle Plate No.')
                    ->searchable(),
                TextColumn::make('car_details')
                    ->label('Car Details'),
                TextColumn::make('payment_status')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('policy_status')
                    ->searchable()
                    ->label('Policy Status')
                    ->sortable()
                    ->badge(),
                
          
            ]);
    }
    
}
