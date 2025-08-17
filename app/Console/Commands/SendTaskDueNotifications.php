<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\TaskDueDateNotification;

class SendTaskDueNotifications extends Command
{
    protected $signature = 'tasks:send-due-notifications';
    protected $description = 'Send daily notifications for tasks due today';

    public function handle()
    {
        $today = Carbon::now('Asia/Singapore')->startOfDay();
        $dueTasks = Task::whereDate('due_date', $today)->get();

        if ($dueTasks->isEmpty()) {
            $this->info('No tasks due today.');
            return;
        }

        $users = User::all();

        foreach ($dueTasks as $task) {
            foreach ($users as $user) {
                $user->notify(new TaskDueDateNotification($task));
            }
        }

        $this->info('Task due notifications sent successfully.');
    }
}
