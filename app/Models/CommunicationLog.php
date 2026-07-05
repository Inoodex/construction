<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationLog extends Model
{
    protected $fillable = [
        'communicable_type', 'communicable_id', 'type',
        'subject', 'notes', 'date', 'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function communicable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
