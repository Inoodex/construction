<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'vendor_id',
        'bid_amount',
        'notes',
        'technical_score',
        'financial_score',
        'total_score',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'bid_amount' => 'decimal:2',
        'submitted_at' => 'date',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
