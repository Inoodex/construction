<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $fillable = [
        'rfq_id',
        'vendor_id',
        'quotation_number',
        'submitted_date',
        'notes',
        'is_winner',
    ];

    protected $casts = [
        'submitted_date' => 'date',
        'is_winner' => 'boolean',
    ];

    public function rfq(): BelongsTo
    {
        return $this->belongsTo(Rfq::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function totalAmount(): float
    {
        return $this->items->sum(fn ($i) => $i->unit_price * $i->rfqItem->quantity);
    }
}
