<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class OverdueStats extends Component
{
    public function render()
    {
        $today = Carbon::now()->startOfDay();

        // Fetch overdue payment records (payments before today that are not paid)
        $overdueRecords = Report::when(Auth::user()->branch_id !== null, function (Builder $query) {
                                    $query->whereHas('costCenter', function (Builder $subQuery) {
                                        $subQuery->where('cost_center_id', Auth::user()->branch_id);
                                    });
                                })
                                ->where(function ($query) use ($today) {
                                    $query->where(function ($q) use ($today) {
                                        $q->where('1st_payment_date', '<', $today)
                                          ->where('1st_is_paid', 0);
                                    })
                                    ->orWhere(function ($q) use ($today) {
                                        $q->where('2nd_payment_date', '<', $today)
                                          ->where('2nd_is_paid', 0);
                                    })
                                    ->orWhere(function ($q) use ($today) {
                                        $q->where('3rd_payment_date', '<', $today)
                                          ->where('3rd_is_paid', 0);
                                    })
                                    ->orWhere(function ($q) use ($today) {
                                        $q->where('4th_payment_date', '<', $today)
                                          ->where('4th_is_paid', 0);
                                    })
                                    ->orWhere(function ($q) use ($today) {
                                        $q->where('5th_payment_date', '<', $today)
                                          ->where('5th_is_paid', 0);
                                    })
                                    ->orWhere(function ($q) use ($today) {
                                        $q->where('6th_payment_date', '<', $today)
                                          ->where('6th_is_paid', 0);
                                    });
                                })
                                ->get();

        $count = $overdueRecords->count();

        return view('livewire.overdue-stats', [
            'overdueRecords' => $overdueRecords,
            'count' => $count,
        ]);
    }
}
