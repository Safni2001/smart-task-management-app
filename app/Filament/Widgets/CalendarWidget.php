<?php

namespace App\Filament\Widgets;

use Filament\Forms;
use App\Models\Task;
use App\Models\TaskType;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\TaskResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Illuminate\Support\Facades\Auth;

class CalendarWidget extends FullCalendarWidget
{
    public Model|string|null $model = Task::class; // Updated type declaration
    
    // Prevent this widget from showing on the main dashboard
    protected static bool $isDiscovered = false;

    public function fetchEvents(array $fetchInfo): array
    {
        return Task::query()
            ->where('user_id', Auth::id())
            ->where('due_date', '>=', $fetchInfo['start'])
            ->where('due_date', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Task $task) => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'start' => $task->due_date,
                    'end' => $task->due_date,
                ]
            )
            ->toArray();
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Task Details')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DateTimePicker::make('due_date')
                        ->native(false)
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull(),
                    Forms\Components\Select::make('parent_id')
                        ->label('Depends On')
                        ->options(function () {
                            return Task::where('user_id', Auth::id())
                                ->where('status', 'pending')
                                ->pluck('title', 'id')
                                ->toArray();
                        })
                        ->nullable()
                        ->searchable(),
                    Forms\Components\Select::make('task_type_id')
                        ->label('Task Type')
                        ->options(function () {
                            return TaskType::where('user_id', Auth::id())
                                ->pluck('type', 'id')
                                ->toArray();
                        })
                        ->nullable()
                        ->preload()
                        ->searchable(),
                    Forms\Components\TextInput::make('estimated_time')
                        ->label('Estimated Time (minutes)')
                        ->numeric()
                        ->nullable(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                        ])
                        ->default('pending'),
                ])
                ->columns(2),
        ];
    }

    protected function modalActions(): array
    {
        return [
            \Saade\FilamentFullCalendar\Actions\ViewAction::make()
                ->infolist(function (Infolist $infolist) {
                    return $infolist
                        ->schema([
                            Section::make('Task Details')
                                ->schema([
                                    TextEntry::make('title')->label('Title'),
                                    TextEntry::make('description')->label('Description'),
                                    TextEntry::make('due_date')->label('Due Date'),
                                    TextEntry::make('priority')->label('Priority'),
                                    TextEntry::make('status')->label('Status'),
                                    TextEntry::make('estimated_time')->label('Estimated Time (minutes)'),
                                    TextEntry::make('taskType.type')->label('Task Type'),
                                    TextEntry::make('parent.title')->label('Depends On'),
                                ])
                                ->columns(2),
                        ]);
                }),
            \Saade\FilamentFullCalendar\Actions\DeleteAction::make(),
        ];
    }


    protected function headerActions(): array
    {
        return [
            \Saade\FilamentFullCalendar\Actions\CreateAction::make()
                ->mountUsing(
                    function (Forms\Form $form, array $arguments) {
                        $form->fill([
                            'due_date' => $arguments['start'] ?? null,
                            'user_id' => Auth::id(),
                        ]);
                    }
                )
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = Auth::id();
                    return $data;
                }),
        ];
    }

    public function config(): array
    {
        return [
            'eventContent' => 'function(arg) {
            var title = arg.event.title;
            return { html: title };
        }',
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridMonth,dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }
}