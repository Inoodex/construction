<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubcontractProgressPayment extends Model
{
    protected $fillable = [
        'subcontract_agreement_id',
        'certificate_number',
        'period_start',
        'period_end',
        'work_completed_value',
        'previous_certified_value',
        'total_certified_to_date',
        'retention_amount',
        'retention_released',
        'net_payable',
        'status',
        'certified_by',
        'certified_at',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'certified_at' => 'datetime',
        'work_completed_value' => 'decimal:2',
        'previous_certified_value' => 'decimal:2',
        'total_certified_to_date' => 'decimal:2',
        'retention_amount' => 'decimal:2',
        'retention_released' => 'decimal:2',
        'net_payable' => 'decimal:2',
    ];

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(SubcontractAgreement::class, 'subcontract_agreement_id');
    }

    public function certifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'certified_by');
    }
}
