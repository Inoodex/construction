<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterimPaymentApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'ipa_number',
        'title',
        'application_date',
        'period_start',
        'period_end',
        'previous_cumulative_amount',
        'applied_amount',
        'certified_amount',
        'retention_rate',
        'retention_amount',
        'net_amount',
        'paid_amount',
        'status',
        'submitted_by',
        'certified_by',
        'approved_by',
        'invoice_id',
        'submitted_at',
        'certified_at',
        'approved_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'application_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'previous_cumulative_amount' => 'decimal:2',
        'applied_amount' => 'decimal:2',
        'certified_amount' => 'decimal:2',
        'retention_rate' => 'decimal:2',
        'retention_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
        'certified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function certifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'certified_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(IpaItem::class);
    }
}
