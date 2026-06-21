<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HseChecklist extends Model
{
    protected $fillable = [
        'title', 'checklist_type', 'location', 'inspection_date',
        'employee_id', 'status', 'findings', 'corrective_actions',
        'closure_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'inspection_date' => 'date',
            'closure_date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(HseChecklistItem::class)->orderBy('order_index');
    }
}
