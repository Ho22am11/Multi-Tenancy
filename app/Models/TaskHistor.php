<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHistor extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'changed_by',
        'old_value' ,
        'new_value'
    ];
}
