<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'contract_number',
        'title',
        'client_name',
        'contract_type',
        'contract_value',
        'currency',
        'signing_date',
        'commencement_date',
        'completion_date',
        'extended_completion_date',
        'retention_percentage',
        'liquidated_damages_rate',
        'advance_payment_percentage',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'contract_value' => 'decimal:2',
        'retention_percentage' => 'decimal:2',
        'liquidated_damages_rate' => 'decimal:2',
        'advance_payment_percentage' => 'decimal:2',
        'signing_date' => 'date',
        'commencement_date' => 'date',
        'completion_date' => 'date',
        'extended_completion_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function amendments(): HasMany
    {
        return $this->hasMany(ContractAmendment::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(ContractClaim::class);
    }

    public function closeoutItems(): HasMany
    {
        return $this->hasMany(ContractCloseoutItem::class)->orderBy('order_index');
    }

    public function bankGuarantees(): HasMany
    {
        return $this->hasMany(BankGuarantee::class);
    }

    public function changeOrders(): HasMany
    {
        return $this->hasMany(ChangeOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function generateContractNumber(): string
    {
        $today = now()->format('Ymd');
        $last = static::where('contract_number', 'like', "CTR-{$today}-%")
            ->orderByDesc('contract_number')
            ->first();

        $sequence = 1;
        if ($last) {
            $lastSeq = (int) substr($last->contract_number, -4);
            $sequence = $lastSeq + 1;
        }

        return 'CTR-' . $today . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function totalAmendmentsValue(): float
    {
        return (float) $this->amendments()
            ->where('status', 'approved')
            ->sum('cost_impact');
    }

    public function revisedContractValue(): float
    {
        return (float) $this->contract_value + $this->totalAmendmentsValue();
    }

    public function totalRetentionHeld(): float
    {
        return (float) $this->claims()->where('status', 'granted')->sum('granted_amount');
    }

    public function totalClaimsValue(): float
    {
        return (float) $this->claims()
            ->whereIn('status', ['granted', 'partially_granted'])
            ->sum('granted_amount');
    }

    public function closeoutChecklistComplete(): bool
    {
        return $this->closeoutItems()->count() > 0 &&
               $this->closeoutItems()->where('is_completed', false)->count() === 0;
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'draft' => 'bg-secondary',
            'active' => 'bg-success',
            'suspended' => 'bg-warning',
            'completed' => 'bg-info',
            'terminated' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
