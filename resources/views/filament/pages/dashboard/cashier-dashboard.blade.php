<x-filament-panels::page>
    
    <!--Account Widget -->    
        @livewire(\App\Livewire\AccountDashboardWidget::class)
    <!--Barchart Widgets-->
    <div class="grid gap-2 md:grid-cols-2">
        @livewire(\App\Livewire\CurrentMonthReports::class)
        @livewire(\App\Livewire\PreviousMonth::class)
    </div>
    

    
</x-filament-panels::page>
