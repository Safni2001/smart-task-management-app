<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TaskCompletionStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();
        
        $totalTasks = Task::where('user_id', $userId)->count();
        $completedTasks = Task::where('user_id', $userId)->where('status', 'completed')->count();
        $pendingTasks = Task::where('user_id', $userId)->where('status', 'pending')->count();
        $inProgressTasks = Task::where('user_id', $userId)->where('status', 'in_progress')->count();
        $overdueTasks = Task::where('user_id', $userId)
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->count();

        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        return [
            Stat::make('Total Tasks', $totalTasks)
                ->description('All tasks created')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('gray'),
            
            Stat::make('Completed', $completedTasks)
                ->description($completionRate . '% completion rate')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('In Progress', $inProgressTasks)
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('Pending', $pendingTasks)
                ->description('Not yet started')
                ->descriptionIcon('heroicon-m-pause-circle')
                ->color('info'),
            
            Stat::make('Overdue', $overdueTasks)
                ->description('Past due date')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}