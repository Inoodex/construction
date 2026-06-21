<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpeIssuance extends Model
{
    protected $fillable = [
        'employee_id',
        'item_name',
        'category',
        'issue_date',
        'quantity',
        'size',
        'condition_on_issue',
        'return_date',
        'condition_on_return',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'return_date' => 'date',
            'quantity' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
