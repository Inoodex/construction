<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubcontractAgreement extends Model
{
    protected $fillable = [
        'project_id',
        'subcontractor_id',
        'agreement_number',
        'title',
        'scope_of_work',
        'agreement_date',
        'start_date',
        'end_date',
        'contract_value',
        'retention_percentage',
        'payment_terms',
        'special_conditions',
        'insurance_requirements',
        'status',
        'created_by',
    ];

    protected $casts = [
        'agreement_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'contract_value' => 'decimal:2',
        'retention_percentage' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function subcontractor(): BelongsTo
    {
        return $this->belongsTo(Subcontractor::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function retentionAmount(): float
    {
        return $this->contract_value * ($this->retention_percentage / 100);
    }

    public function progressPayments(): HasMany
    {
        return $this->hasMany(SubcontractProgressPayment::class, 'subcontract_agreement_id');
    }

    public function totalCertifiedToDate(): float
    {
        return $this->progressPayments()
            ->whereIn('status', ['certified', 'paid'])
            ->sum('work_completed_value');
    }
}
