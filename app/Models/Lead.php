<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'email',
        'phone',
        'current_education',
        'preferred_country',
        'preferred_course',
        'source',
        'status',
        'notes',
        'last_contacted_at',
        'next_follow_up_at',
        'created_by',
        'consultant_id',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function consultant()
    {
        return $this->belongsTo(\App\Models\User::class, 'consultant_id');
    }
}
