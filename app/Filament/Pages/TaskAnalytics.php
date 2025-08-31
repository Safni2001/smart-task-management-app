<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class TaskAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.task-analytics';
    protected static ?string $title = 'Task Analytics';
    protected static ?string $navigationGroup = 'Tasks Management';
    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\TaskCompletionStatsWidget::class,
            \App\Filament\Widgets\TimeTrackingWidget::class,
            \App\Filament\Widgets\TaskPriorityDistributionWidget::class,
            \App\Filament\Widgets\TaskTypeAnalyticsWidget::class,
            \App\Filament\Widgets\ProductivityTrendsWidget::class,
        ];
    }

    public function getHeaderWidgets(): array
    {
        return $this->getWidgets();
    }
}