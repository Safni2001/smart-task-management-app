<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
                'danger' => Color::Red,
                'info' => Color::Blue,
                'warning' => Color::Orange,
                'success' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\Calendar::class,
                \App\Filament\Pages\TaskAnalytics::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Analytics widgets for main dashboard (clean, no heading)
                \App\Filament\Widgets\TaskCompletionStatsWidget::class,
                \App\Filament\Widgets\TimeTrackingWidget::class,
                \App\Filament\Widgets\TaskPriorityDistributionWidget::class,
                \App\Filament\Widgets\TaskTypeAnalyticsWidget::class,
                \App\Filament\Widgets\ProductivityTrendsWidget::class,
                // CalendarWidget is excluded via $isDiscovered = false
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
            ->databaseTransactions()
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->collapsibleNavigationGroups()
            ->navigationGroups([
                NavigationGroup::make('Tasks Management')->icon('heroicon-o-list-bullet'),
                NavigationGroup::make('Calender')->icon('heroicon-o-calendar'),
            ])
            ->plugin(
                FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable()
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
