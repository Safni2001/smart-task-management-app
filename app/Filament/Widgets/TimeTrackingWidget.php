<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TimeTrackingWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $userId = Auth::id();
        
        $totalEstimatedTime = Task::where('user_id', $userId)
            ->whereNotNull('estimated_time')
            ->sum('estimated_time');

        $completedEstimatedTime = Task::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('estimated_time')
            ->sum('estimated_time');

        $pendingEstimatedTime = Task::where('user_id', $userId)
            ->where('status', '!=', 'completed')
            ->whereNotNull('estimated_time')
            ->sum('estimated_time');

        $avgEstimatedTime = Task::where('user_id', $userId)
            ->whereNotNull('estimated_time')
            ->avg('estimated_time');

        $avgCompletedTime = Task::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('estimated_time')
            ->avg('estimated_time');

        $totalEstimatedHours = round($totalEstimatedTime / 60, 1);
        $completedHours = round($completedEstimatedTime / 60, 1);
        $pendingHours = round($pendingEstimatedTime / 60, 1);
        $avgHours = round(($avgEstimatedTime ?? 0) / 60, 1);

        return [
            Stat::make('Total Estimated Time', $totalEstimatedHours . 'h')
                ->description('All tasks combined')
                ->descriptionIcon('heroicon-m-clock')
                ->color('blue'),
            
            Stat::make('Completed Time', $completedHours . 'h')
                ->description('Time from completed tasks')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('Remaining Time', $pendingHours . 'h')
                ->description('Estimated time left')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),
            
            Stat::make('Avg. Task Time', $avgHours . 'h')
                ->description('Average estimated time per task')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('gray'),
        ];
    }
}