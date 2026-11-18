<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'status',
        'keterangan',
        'check_in_at',
        'check_out_at',
        'attendance_photo',
        'latitude',
        'longitude',
        'check_out_photo',
        'check_out_latitude',
        'check_out_longitude',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
