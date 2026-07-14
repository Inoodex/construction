<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAccount extends Model
{
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'bank_name',
        'opening_balance',
        'current_balance',
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'opening_balance' => 'decimal:2',
            'current_balance' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AccountTransaction::class)->latest('transaction_date');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'bank' => 'Bank Account',
            'mfs' => 'MFS Account',
            'cash' => 'Cash',
            default => $this->type,
        };
    }
}
