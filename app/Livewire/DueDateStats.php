<?php

namespace App\Livewire;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class DueDateStats extends BaseWidget
{

    
    protected function getStats(): array
    {
        $today = Carbon::now()->startOfDay();
        $tomorrow = Carbon::now()->endOfDay();

        // Calculate the number of due dates
        $dueDates = Report::when(Auth::user()->branch_id !== null, function (Builder $query) {
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
                            ->count();

        // Calculate the number of overdue reports
        // $overdues = Report::when(Auth::user()->branch_id !== null, function (Builder $query) {
        //                         $query->whereHas('costCenter', function (Builder $subQuery) {
        //                             $subQuery->where('cost_center_id', Auth::user()->branch_id);
        //                         });
        //                     })
        //                     ->where(function ($query) {
        //                         $query->where('1st_payment_date', '<', Carbon::now())
        //                               ->where('1st_is_paid', 0)
        //                               ->orWhere('2nd_payment_date', '<', Carbon::now())
        //                               ->where('2nd_is_paid', 0);
        //                     })
        //                     ->count();

        return [
            Stat::make('Due Dates', 'Due Dates Today')
                ->label('Due Dates Today')
                ->value($dueDates)
                ->color('primary'),
                
            // Stat::make('Overdues', 'Overdue Reports')
            //     ->value($overdues)
            //     ->color('danger'),
        ];
    }
}
