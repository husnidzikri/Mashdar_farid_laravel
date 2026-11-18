<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'division',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isSupervisor()
    {
        return $this->role === 'supervisor';
    }

    public function isIntern()
    {
        return $this->role === 'intern';
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'supervisor_id');
    }

 public function submissions()
{
    return $this->hasMany(TaskSubmission::class, 'intern_id', 'id');
}
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
