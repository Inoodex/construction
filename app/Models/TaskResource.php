<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskResource extends Model
{
    protected $fillable = [
        'task_id',
        'project_resource_id',
        'allocated_quantity',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'allocated_quantity' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function projectResource(): BelongsTo
    {
        return $this->belongsTo(ProjectResource::class);
    }
}
