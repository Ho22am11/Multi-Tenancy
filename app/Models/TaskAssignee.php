<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignee extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'user_id',
    ];

    public function task()
    {
        return $this->hasMany(TaskAssignee::class);
    }

}
