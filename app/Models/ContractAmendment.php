<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractAmendment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'amendment_number',
        'title',
        'description',
        'type',
        'cost_impact',
        'time_impact_days',
        'status',
        'requested_by',
        'approved_by',
        'approved_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'cost_impact' => 'decimal:2',
        'approved_date' => 'date',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function generateAmendmentNumber(): string
    {
        $today = now()->format('Ymd');
        $last = static::where('amendment_number', 'like', "AMD-{$today}-%")
            ->orderByDesc('amendment_number')
            ->first();

        $sequence = 1;
        if ($last) {
            $lastSeq = (int) substr($last->amendment_number, -4);
            $sequence = $lastSeq + 1;
        }

        return 'AMD-' . $today . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'draft' => 'bg-secondary',
            'submitted' => 'bg-info',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'scope_change' => 'Scope Change',
            'time_extension' => 'Time Extension',
            'value_change' => 'Value Change',
            'other' => 'Other',
            default => $this->type,
        };
    }
}
