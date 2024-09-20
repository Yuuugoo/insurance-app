<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Auth\Login;
use Filament\Actions\Action;
use App\Livewire\ReportStats;
use Livewire\Attributes\Lazy;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use App\Filament\Pages\ActivityLog;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\Filament\Pages\StaffDashboard;
use App\Filament\Widgets\TotalReports;
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\CashierDashboard;
use App\Livewire\AccountDashboardWidget;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Filament\Resources\ReportsResource;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Awcodes\FilamentStickyHeader\StickyHeaderPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

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
            ->navigationGroups([
                NavigationGroup::make('SUMMARY')
                    ->label('SUMMARY')
                    ->collapsed(true),
                NavigationGroup::make('CMS')
                    ->label('CMS')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('SETTINGS')
                    ->collapsed(true),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css') // Register Custom CSS
            ->renderHook(
                'panels::topbar.start',
                fn (): string => Blade::render('@livewire(\'TopbarTitle\')'), // Added logo at the top of Sidebar
            )
            ->renderHook(
                'panels::topbar.end',
                fn (): string => Blade::render('@livewire(\'TopbarRolename\')'), // Added logo at the top of Sidebar
            )
            ->renderHook(
                'panels::body.end',
                fn (): string => Blade::render('@livewire(\'Footer\')')
            )
            ->databaseNotifications(
                condition: fn () => Auth::user()->hasAnyRole(['acct-staff', 'acct-manager'])
            )
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
            // ->databaseNotificationsPolling('5s')
            // ->databaseNotifications() // Database Notifications
            ->plugins([
                FilamentApexChartsPlugin::make(),
            ])
            ->colors([
                'aap-blue' => Color::hex('#002C69'),
                'aap-yellow' => Color::hex('#FAE100'),
            ]);
    }
}
