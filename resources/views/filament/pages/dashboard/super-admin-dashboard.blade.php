<x-filament-panels::page>
    <!--Account Widget-->
    <div class="account-widget">
        @livewire(\App\Livewire\AccountDashboardWidget::class)
    </div>
    <div class="users-widget">
        @livewire(\App\Livewire\UsersPerRoleWidget::class)
    </div>
</x-filament-panels::page>
