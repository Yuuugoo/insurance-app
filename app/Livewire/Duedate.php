<?php

namespace App\Livewire;

use Carbon\Carbon;
use Filament\Tables;
use App\Models\Report;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReportsResource;
use Filament\Widgets\TableWidget as BaseWidget;

class Duedate extends BaseWidget
{
    public function table(Table $table): Table
    {
        $today = Carbon::now()->startOfDay();
        $tomorrow = Carbon::now()->endOfDay();

        return $table
            ->query(
                Report::when(Auth::user()->branch_id !== null, function (Builder $query) {
                    $query->whereHas('costCenter', function (Builder $subQuery) {
                        $subQuery->where('cost_center_id', Auth::user()->branch_id);
                    });
                })
                ->where(function ($query) use ($today, $tomorrow) {
                    $query->whereBetween('1st_payment_date', [$today, $tomorrow])
                          ->where('1st_is_paid', 0)
                          ->orWhereBetween('2nd_payment_date', [$today, $tomorrow])
                          ->where('2nd_is_paid', 0);
                })
            )
            ->columns([
                TextColumn::make('arpr_date')
                    ->sortable()
                    ->searchable()
                    ->date('m-d-Y')
                    ->label('AR/PR Date'),
                TextColumn::make('costCenter.name')
                    ->label('Cost Center')
                    ->searchable()
                    ->visibleFrom('md')
                    ->icon('heroicon-o-map-pin'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.')
                    ->searchable(),
                TextColumn::make('providers.name')
                    ->label('Insurance Provider')
                    ->label(fn () => new HtmlString('Insurance<br>Provider'))
                    ->visibleFrom('md'),
                TextColumn::make('types.name')
                    ->label('Insurance Type')
                    ->visibleFrom('md'),
                TextColumn::make('payment_status_aap')
                    ->label(fn () => new HtmlString('Payment Status<br>to AAP'))
                    ->badge(),  
                TextColumn::make('payment_status')
                    ->label(fn () => new HtmlString('Payment Status<br>to Provider'))
                    ->badge(),
                TextColumn::make('cashier.name')
                    ->label('Submitted By'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->url(fn (Report $record) => ReportsResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye'),
            ]);
    }
}
