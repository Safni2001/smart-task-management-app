<?php

namespace App\Filament\Imports;

use App\Models\Task;
use App\Models\TaskType;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskImporter extends Importer
{
    protected static ?string $model = Task::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('due_date')
                ->rules(['nullable']),
            ImportColumn::make('estimated_time')
                ->numeric()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required', 'in:pending,in_progress,completed']),
        ];
    }

    public function resolveRecord(): ?Task
    {
        $userId = $this->data['user_id'] ?? Auth::id();
        $title = $this->data['title'] ?? null;

        if (!$userId || !$title) {
            return new Task();
        }

        return Task::firstOrNew([
            'user_id' => $userId,
            'title' => $title,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your task import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
