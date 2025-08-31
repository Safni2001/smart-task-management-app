<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductivityTrendsWidget extends ChartWidget
{
    protected static ?string $heading = 'Task Completion Trends (Last 30 Days)';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $userId = Auth::id();
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();

        $completedTasks = Task::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('updated_at', '>=', $startDate)
            ->where('updated_at', '<=', $endDate)
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $createdTasks = Task::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $dates = [];
        $completedData = [];
        $createdData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('M j');
            $completedData[] = $completedTasks[$date] ?? 0;
            $createdData[] = $createdTasks[$date] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tasks Completed',
                    'data' => $completedData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Tasks Created',
                    'data' => $createdData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
        ];
    }
}