<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Tables;
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
                TextInputColumn::make('title')
                    ->label('Title'),
            ]);
    }
}
