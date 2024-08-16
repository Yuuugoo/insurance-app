<x-filament-panels::page>
    <!--Account Widget-->
    <div class="account-widget">
        @livewire(\App\Livewire\AccountDashboardWidget::class)
    </div>
    <div class="users-widget">
        @livewire(\App\Livewire\UsersPerRoleWidget::class)
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
