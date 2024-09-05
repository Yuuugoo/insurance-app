<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class OverdueStats extends Component
{
    use WithPagination;

    // To avoid page reset after actions like search or sorting
    protected $paginationTheme = 'tailwind';
    public $showTable = false; // Property to control table visibility

    public function render()
    {
        $today = Carbon::now()->startOfDay();

        // Fetch overdue payment records with pagination
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
                                ->with('costCenter:cost_center_id,name')
                                ->paginate(5); // Paginate the records, 5 per page

        $count = $overdueRecords->total(); // Update the count to reflect total records, not just current page

        return view('livewire.overdue-stats', [
            'overdueRecords' => $overdueRecords,
            'count' => $count,
        ]);
    }
    public function toggleTable()
    {
        $this->showTable = !$this->showTable;
    }
}
