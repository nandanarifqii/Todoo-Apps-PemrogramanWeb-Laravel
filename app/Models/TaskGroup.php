<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskGroup extends Model
{
    use HasFactory;

    protected $table = 'task_groups';

    protected $fillable = [
        'name',
        'description',
        'priority',
        'due_date',
        'group_id',
        'hasFinished',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
