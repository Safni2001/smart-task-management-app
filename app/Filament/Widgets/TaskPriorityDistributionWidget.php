<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TaskPriorityDistributionWidget extends ChartWidget
{
    protected static ?string $heading = 'Task Priority Distribution';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $userId = Auth::id();

        $priorityCounts = Task::where('user_id', $userId)
            ->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        $priorities = ['High', 'Medium', 'Low'];
        $data = [];
        $labels = [];

        foreach ($priorities as $priority) {
            $count = $priorityCounts[$priority] ?? 0;
            if ($count > 0) {
                $data[] = $count;
                $labels[] = ucfirst($priority) . ' Priority';
            }
        }

        if (empty($data)) {
            $data = [1];
            $labels = ['No tasks with priority'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tasks by Priority',
                    'data' => $data,
                    'backgroundColor' => [
                        '#ef4444',
                        '#f59e0b',
                        '#10b981',
                        '#6b7280',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
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