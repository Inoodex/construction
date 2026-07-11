<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractCloseoutItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'item',
        'description',
        'is_completed',
        'completed_date',
        'completed_by',
        'order_index',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_date' => 'date',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
