<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolboxTalk extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'topic', 'duration_minutes',
        'location', 'attendees', 'discussion_points', 'action_items', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'duration_minutes' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
