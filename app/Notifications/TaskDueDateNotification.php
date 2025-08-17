<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Task;
use Filament\Notifications\Notification as FilamentNotification;

class TaskDueDateNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Store in the notifications table
        $data = [
            'organization_id' => $this->task->organization_id,
            'title' => 'task Due Today',
            'body' => "{$this->task->title} is due today.",
            'task_id' => $this->task->id,
            'due_date' => $this->task->due_date,
        ];

        // Send Filament notification to appear in the bell icon
        FilamentNotification::make()
            ->title('Task Due Today')
            ->body("{$this->task->title} is due today.")
            ->warning()
            ->sendToDatabase($notifiable);

        return $data;
    }
}