<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'claim_number',
        'title',
        'description',
        'type',
        'claimed_amount',
        'claimed_days',
        'granted_amount',
        'granted_days',
        'status',
        'submitted_date',
        'response_date',
        'submitted_by',
        'reviewed_by',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'claimed_amount' => 'decimal:2',
        'granted_amount' => 'decimal:2',
        'submitted_date' => 'date',
        'response_date' => 'date',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function generateClaimNumber(): string
    {
        $today = now()->format('Ymd');
        $last = static::where('claim_number', 'like', "CLM-{$today}-%")
            ->orderByDesc('claim_number')
            ->first();

        $sequence = 1;
        if ($last) {
            $lastSeq = (int) substr($last->claim_number, -4);
            $sequence = $lastSeq + 1;
        }

        return 'CLM-' . $today . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'draft' => 'bg-secondary',
            'submitted' => 'bg-info',
            'under_review' => 'bg-warning',
            'granted' => 'bg-success',
            'partially_granted' => 'bg-info',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'time_extension' => 'Time Extension',
            'cost_compensation' => 'Cost Compensation',
            'both' => 'Time & Cost',
            default => $this->type,
        };
    }
}
