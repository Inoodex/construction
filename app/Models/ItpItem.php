<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItpItem extends Model
{
    protected $fillable = [
        'itp_id', 'description', 'specification_reference', 'inspection_type',
        'acceptance_criteria', 'method', 'frequency', 'status', 'result',
        'inspected_date', 'inspector', 'order_index',
    ];

    protected function casts(): array
    {
        return [
            'inspected_date' => 'date',
            'order_index' => 'integer',
        ];
    }

    public function itp(): BelongsTo
    {
        return $this->belongsTo(Itp::class);
    }
}
