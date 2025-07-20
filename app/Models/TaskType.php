<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    protected $fillable = ['user_id','type'];
     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Task()
    {
        return $this->belongsTo(Task::class, 'task_type_id');
    }
}
