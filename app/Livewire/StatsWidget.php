<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class StatsWidget extends Component
{
    use WithPagination;

    // To avoid page reset after actions like search or sorting
    protected $paginationTheme = 'tailwind';

    public $showTable = false; // Property to control table visibility

    public function render()
    {
        $today = Carbon::now()->startOfDay();
        $tomorrow = Carbon::now()->endOfDay();

        $dueRecords = Report::when(Auth::user()->branch_id !== null, function (Builder $query) {
                                $query->whereHas('costCenter', function (Builder $subQuery) {
                                    $subQuery->where('cost_center_id', Auth::user()->branch_id);
                                });
                            })
                            ->where(function ($query) use ($today, $tomorrow) {
                                $query->where(function ($q) use ($today, $tomorrow) {
                                    $q->whereBetween('1st_payment_date', [$today, $tomorrow])
                                      ->where('1st_is_paid', 0);
                                })
                                ->orWhere(function ($q) use ($today, $tomorrow) {
                                    $q->whereBetween('2nd_payment_date', [$today, $tomorrow])
                                      ->where('2nd_is_paid', 0);
                                })
                                ->orWhere(function ($q) use ($today, $tomorrow) {
                                    $q->whereBetween('3rd_payment_date', [$today, $tomorrow])
                                      ->where('3rd_is_paid', 0);
                                })
                                ->orWhere(function ($q) use ($today, $tomorrow) {
                                    $q->whereBetween('4th_payment_date', [$today, $tomorrow])
                                      ->where('4th_is_paid', 0);
                                })
                                ->orWhere(function ($q) use ($today, $tomorrow) {
                                    $q->whereBetween('5th_payment_date', [$today, $tomorrow])
                                      ->where('5th_is_paid', 0);
                                })
                                ->orWhere(function ($q) use ($today, $tomorrow) {
                                    $q->whereBetween('6th_payment_date', [$today, $tomorrow])
                                      ->where('6th_is_paid', 0);
                                });
                            })
                            ->with('costCenter:cost_center_id,name')
                            ->paginate(5); // Limit to 5 records per page

        $count = $dueRecords->total(); // Use total to count all records, not just the current page

        return view('livewire.stats-widget', [
            'dueRecords' => $dueRecords,
            'count' => $count,
        ]);
    }

    public function toggleTable()
    {
        $this->showTable = !$this->showTable;
    }
}
