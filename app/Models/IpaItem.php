<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipa_id',
        'boq_item_id',
        'item_number',
        'description',
        'unit',
        'previous_quantity',
        'current_quantity',
        'cumulative_quantity',
        'unit_price',
        'previous_amount',
        'current_amount',
        'cumulative_amount',
        'notes',
    ];

    protected $casts = [
        'previous_quantity' => 'decimal:4',
        'current_quantity' => 'decimal:4',
        'cumulative_quantity' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'previous_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'cumulative_amount' => 'decimal:2',
    ];

    public function ipa(): BelongsTo
    {
        return $this->belongsTo(InterimPaymentApplication::class, 'ipa_id');
    }

    public function boqItem(): BelongsTo
    {
        return $this->belongsTo(BoqItem::class);
    }
}
