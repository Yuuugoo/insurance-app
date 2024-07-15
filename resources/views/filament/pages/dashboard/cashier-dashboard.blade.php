<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-2">
        <div class="mr-4">@livewire(\App\Livewire\MonthlyReportsChart::class)</div>
        <div>@livewire(\App\Livewire\MonthlyReportsChart::class)</div>
    </div>
    <div>@livewire(\App\Livewire\ReportsTable::class)</div>

    
</x-filament-panels::page>
