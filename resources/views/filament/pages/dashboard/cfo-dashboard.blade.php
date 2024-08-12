<x-filament-panels::page>
    <!--Account Widget-->
    <div class="account-widget">
        @livewire(\App\Livewire\AccountDashboardWidget::class)
    </div>
    <!--Barchart Widgets-->
    <div class="grid gap-2 md:grid-cols-2">
        @livewire(\App\Livewire\CurrentMonthReports::class)
        @livewire(\App\Livewire\PreviousMonth::class)
    </div>

</x-filament-panels::page>
