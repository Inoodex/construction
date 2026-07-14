<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AccountTransaction extends Model
{
    protected $fillable = [
        'payment_account_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'transactable_type',
        'transactable_id',
        'reference',
        'transaction_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_after' => 'decimal:2',
            'transaction_date' => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(PaymentAccount::class);
    }

    public function transactable(): MorphTo
    {
        return $this->morphTo();
    }
}
