<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'intern_id',
        'link_submission',
        'notes',
        'status',
        'supervisor_feedback',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function intern()
    {
        return $this->belongsTo(User::class, 'intern_id');
    }
}
