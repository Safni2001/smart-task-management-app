<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static ?string $navigationGroup = 'Tasks Management';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Task Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('due_date')
                            ->native(false),
                        Forms\Components\Textarea::make('description')->columnSpanFull(),
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
                            ->relationship('taskType', 'type', fn($query) => $query->where('user_id', Auth::id()))
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
                                'completed' => 'Completed'
                            ])
                            ->default('pending'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Task::query()->where('user_id', Auth::id()))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_time')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('taskType.type')
                    ->label('Task Type')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('parent.title')
                    ->label('Depends On')
                    ->default('-'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view_priority')
                        ->label('View Priority')
                        ->color('success')
                        ->action(function ($record) {
                            $tasks = collect([$record])->map(function ($task) {
                                return [
                                    'title' => $task->title ?? 'Untitled',
                                    'due_date' => $task->due_date ? $task->due_date->format('Y-m-d H:i:s') : null,
                                    'estimated_time' => $task->estimated_time ?? 0,
                                    'parent_id' => $task->parent_id ?? -1,
                                    'status' => $task->status ?? 'pending',
                                    'task_type' => $task->taskType ? $task->taskType->type : 'General',
                                ];
                            })->toArray();

                            Log::info('Tasks sent to FastAPI (view_priority):', ['tasks' => $tasks]);

                            $response = Http::timeout(30)->post('http://localhost:8000/predict', ['tasks' => $tasks]);
                            if ($response->successful()) {
                                $predictions = $response->json()['tasks'];
                                foreach ($predictions as $prediction) {
                                    Task::where('title', $prediction['title'])->update([
                                        'priority' => $prediction['priority'],
                                        'order' => $prediction['order'],
                                    ]);
                                }

                                Notification::make()
                                    ->title('Priority Updated')
                                    ->success()
                                    ->send();
                            } else {
                                $errorDetail = $response->json()['detail'] ?? $response->body();
                                Log::error('FastAPI error (view_priority):', ['error' => $errorDetail]);
                                Notification::make()
                                    ->title('Failed to Update Priority')
                                    ->body($errorDetail)
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('view_priority_bulk')
                    ->label('Update Priorities')
                    ->color('success')
                    ->icon('heroicon-o-eye')
                    ->action(function (Collection $records) {
                        $tasks = $records->map(function ($task) {
                            return [
                                'title' => $task->title ?? 'Untitled',
                                'due_date' => $task->due_date ? $task->due_date->format('Y-m-d H:i:s') : null,
                                'estimated_time' => $task->estimated_time ?? 0,
                                'parent_id' => $task->parent_id ?? -1,
                                'status' => $task->status ?? 'pending',
                                'task_type' => $task->taskType ? $task->taskType->type : 'General',
                            ];
                        })->toArray();

                        Log::info('Tasks sent to FastAPI (view_priority_bulk):', ['tasks' => $tasks]);

                        $response = Http::timeout(30)->post('http://localhost:8000/predict', ['tasks' => $tasks]);
                        if ($response->successful()) {
                            $predictions = $response->json()['tasks'];
                            foreach ($predictions as $prediction) {
                                Task::where('title', $prediction['title'])->update([
                                    'priority' => $prediction['priority'],
                                    'order' => $prediction['order'],
                                ]);
                            }

                            Notification::make()
                                ->title('Priorities Updated')
                                ->success()
                                ->send();
                        } else {
                            $errorDetail = $response->json()['detail'] ?? $response->body();
                            Log::error('FastAPI error (view_priority_bulk):', ['error' => $errorDetail]);
                            Notification::make()
                                ->title('Failed to Update Priorities')
                                ->body($errorDetail)
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Task Details')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Title'),
                        TextEntry::make('description')
                            ->label('Description'),
                        TextEntry::make('due_date')
                            ->label('Due Date'),
                        TextEntry::make('priority')
                            ->label('Priority'),
                        TextEntry::make('status')
                            ->label('Status'),
                        TextEntry::make('estimated_time')
                            ->label('Estimated Time (minutes)'),
                        TextEntry::make('taskType.type')
                            ->label('Task Type'),
                        TextEntry::make('parent.title')
                            ->label('Depends On'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}