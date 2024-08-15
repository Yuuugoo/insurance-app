<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Auth\Login;
use App\Filament\Pages\ActivityLog;
use Filament\Actions\Action;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Blade;
use App\Filament\Pages\StaffDashboard;
use App\Filament\Widgets\TotalReports;
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\CashierDashboard;
use App\Filament\Resources\ReportsResource;
use App\Livewire\AccountDashboardWidget;
use App\Livewire\ReportStats;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Awcodes\FilamentStickyHeader\StickyHeaderPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Livewire\Attributes\Lazy;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/')
            ->maxContentWidth(MaxWidth::Full) 
            ->login(Login::class) // Login Form
            ->brandName('') // Remove Brand Name
            ->brandLogo(asset('images/aap-logo-2.png'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->maxContentWidth(MaxWidth::Full)
            ->darkMode(false)
            ->viteTheme('resources/css/filament/admin/theme.css') // Register Custom CSS
            ->renderHook(
                'panels::topbar.start',
                fn (): string => Blade::render('@livewire(\'TopbarTitle\')'), // Added logo at the top of Sidebar
            )
            ->renderHook(
                'panels::body.end',
                fn (): string => Blade::render('@livewire(\'Footer\')')
            )
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotificationsPolling('5s')
            ->databaseNotifications()// Database Notifications
            ->plugins([
                FilamentApexChartsPlugin::make(),
            ])
            ->colors([
                'aap-blue' => Color::hex('#002C69'),
                'aap-yellow' => Color::hex('#FAE100'),
            ]);
    }
}
