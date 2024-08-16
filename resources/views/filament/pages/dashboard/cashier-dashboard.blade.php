<x-filament-panels::page>
    
    <!--Account Widget -->    
        @livewire(\App\Livewire\AccountDashboardWidget::class)
    <!--Barchart Widgets-->
    <div class="grid gap-2 md:grid-cols-2">
        @livewire(\App\Livewire\CurrentMonthReports::class)
        @livewire(\App\Livewire\PreviousMonth::class)
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            if (typeof Alpine !== 'undefined' && Alpine.store('sidebar')) {
                Alpine.store('sidebar').toggleCollapsedGroup('SUMMARY');
                Alpine.store('sidebar').toggleCollapsedGroup('CMS');
                Alpine.store('sidebar').toggleCollapsedGroup('SETTINGS');
            }
        });
    </script>
</x-filament-panels::page>
