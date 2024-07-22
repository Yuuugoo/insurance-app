<x-filament-panels::page>
    <div class="account-widget">
        @livewire(\App\Livewire\AccountDashboardWidget::class)
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        @livewire(\App\Livewire\BarChart::class)
        @livewire(\App\Livewire\PreviousMonth::class)

      
    </div>
  


</x-filament-panels::page>
