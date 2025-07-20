<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['user_id', 'parent_id', 'title', 'description', 'due_date', 'order', 'priority', 'status', 'task_type_id'];

    protected $casts = [
        'due_date' => 'datetime',
        'priority' => 'string',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function dependents()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function taskType()
    {
        return $this->belongsTo(TaskType::class, 'task_type_id');
    }
}
