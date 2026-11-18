<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'priority',
        'deadline',
        'supervisor_id',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_submissions')->withPivot('status', 'progress', 'work_link', 'notes', 'supervisor_comment');
    }
}
