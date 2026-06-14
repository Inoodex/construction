<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionChecklistItem extends Model
{
    protected $fillable = [
        'inspection_checklist_id', 'item_name', 'is_checked', 'remarks', 'order_index',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
        'order_index' => 'integer',
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(InspectionChecklist::class, 'inspection_checklist_id');
    }
}
