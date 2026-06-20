<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectResource extends Model
{
    protected $fillable = [
        'project_id',
        'resource_type',
        'name',
        'description',
        'quantity',
        'unit',
        'unit_cost',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function taskAllocations(): HasMany
    {
        return $this->hasMany(TaskResource::class);
    }

    public function getAllocatedQuantityAttribute(): float
    {
        return (float) $this->taskAllocations()->sum('allocated_quantity');
    }

    public function getPendingQuantityAttribute(): float
    {
        return max(0, (float) $this->quantity - $this->allocated_quantity);
    }
}
