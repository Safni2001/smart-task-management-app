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
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\Collection;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static ?string $navigationGroup = 'Tasks Management';

    protected static ?int $navigationSort = 1;

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
                        Forms\Components\Select::make('task_type')
                            ->label('Task Type')
                            ->options([
                                'analyze' => 'Analyze',
                                'review' => 'Review',
                                'planning' => 'Planning',
                                'meeting' => 'Meeting'
                            ])
                            ->nullable()
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
                Tables\Columns\TextColumn::make('task_type')
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
                                    'title' => $task->title,
                                    'due_date' => $task->due_date,
                                    'estimated_time' => $task->estimated_time,
                                    'parent_id' => $task->parent_id ?? 0,
                                    'status' => $task->status,
                                ];
                            })->toArray();

                            $response = Http::post('http://localhost:8000/predict', ['tasks' => $tasks]);
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
                                throw new \Exception('Failed to update task order: ' . $response->body());
                            }
                        })
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make(),
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
                                'title' => $task->title,
                                'due_date' => $task->due_date,
                                'estimated_time' => $task->estimated_time,
                                'parent_id' => $task->parent_id ?? 0,
                                'status' => $task->status,
                            ];
                        })->toArray();

                        $response = Http::post('http://localhost:8000/predict', ['tasks' => $tasks]);
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
                            throw new \Exception('Failed to update task order: ' . $response->body());
                        }
                    }),
                Tables\Actions\DeleteBulkAction::make(),
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
