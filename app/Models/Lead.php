<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    protected $fillable = [
        'company_name', 'contact_person', 'email', 'phone', 'source',
        'estimated_value', 'description', 'status', 'assigned_to',
        'last_contacted_at', 'next_follow_up_at', 'notes', 'created_by',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'last_contacted_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function communications(): MorphMany
    {
        return $this->morphMany(CommunicationLog::class, 'communicable');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
