<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HseChecklistItem extends Model
{
    protected $fillable = [
        'hse_checklist_id', 'item_name', 'is_compliant', 'remarks', 'order_index',
    ];

    protected function casts(): array
    {
        return [
            'is_compliant' => 'boolean',
            'order_index' => 'integer',
        ];
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(HseChecklist::class, 'hse_checklist_id');
    }
}
