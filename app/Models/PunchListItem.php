<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PunchListItem extends Model
{
    protected $fillable = [
        'punch_list_id', 'description', 'location', 'trade', 'priority',
        'status', 'assigned_to', 'completed_date', 'verified_date',
        'notes', 'order_index',
    ];

    protected function casts(): array
    {
        return [
            'completed_date' => 'date',
            'verified_date' => 'date',
            'order_index' => 'integer',
        ];
    }

    public function punchList(): BelongsTo
    {
        return $this->belongsTo(PunchList::class);
    }
}
