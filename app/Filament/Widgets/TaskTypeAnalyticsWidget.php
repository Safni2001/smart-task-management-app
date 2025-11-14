<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use App\Models\TaskType;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TaskTypeAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Tasks by Type';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $userId = Auth::id();
        
        try {
            $taskTypeCounts = Task::where('tasks.user_id', $userId)
                ->leftJoin('task_types', function($join) use ($userId) {
                    $join->on('tasks.task_type_id', '=', 'task_types.id')
                         ->where('task_types.user_id', $userId);
                })
                ->selectRaw('COALESCE(task_types.type, "Uncategorized") as type_name, COUNT(*) as count')
                ->groupBy('task_types.type')
                ->pluck('count', 'type_name')
                ->toArray();
        } catch (\Exception $e) {
            // Fallback: Simple query without join if there's an issue
            $taskTypeCounts = Task::where('user_id', $userId)
                ->whereNull('task_type_id')
                ->count();
            
            $taskTypeCounts = $taskTypeCounts > 0 ? ['Uncategorized' => $taskTypeCounts] : [];
        }

        if (empty($taskTypeCounts)) {
            return [
                'datasets' => [
                    [
                        'label' => 'Tasks by Type',
                        'data' => [1],
                        'backgroundColor' => ['#6b7280'],
                        'borderWidth' => 0,
                    ],
                ],
                'labels' => ['No tasks found'],
            ];
        }

        $colors = [
            '#3b82f6', // blue
            '#8b5cf6', // purple
            '#06b6d4', // cyan
            '#10b981', // green
            '#f59e0b', // amber
            '#ef4444', // red
            '#ec4899', // pink
            '#84cc16', // lime
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Tasks by Type',
                    'data' => array_values($taskTypeCounts),
                    'backgroundColor' => array_slice($colors, 0, count($taskTypeCounts)),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => array_keys($taskTypeCounts),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}