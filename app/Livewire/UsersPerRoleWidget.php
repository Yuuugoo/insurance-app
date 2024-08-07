<?php

namespace App\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;

class UsersPerRoleWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Cashiers', Role::findByName('cashier')->users()->count())
                ->descriptionIcon('heroicon-m-users')
                ->description('Total')
                ->color('info'),
            Stat::make('Accounting Staffs', Role::findByName('acct-staff')->users()->count())
                ->descriptionIcon('heroicon-m-users')
                ->description('Total')
                ->color('info'),
            Stat::make('Accounting Managers', Role::findByName('acct-manager')->users()->count())
                ->descriptionIcon('heroicon-m-users')
                ->description('Total')
                ->color('info'),
            Stat::make('Agents', Role::findByName('agent')->users()->count())
                ->descriptionIcon('heroicon-m-users')
                ->description('Total')
                ->color('info'),
        ];
    }

}
