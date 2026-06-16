<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RateAnalysisItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate_analysis_id',
        'resource_type',
        'resource_description',
        'unit',
        'quantity',
        'unit_rate',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_rate' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function rateAnalysis(): BelongsTo
    {
        return $this->belongsTo(RateAnalysis::class);
    }
}
