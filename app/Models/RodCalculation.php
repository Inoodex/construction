<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RodCalculation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'reference_no',
        'title',
        'description',
        'steel_grade',
        'revision',
        'status',
        'formula_version',
        'waste_percent',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'waste_percent' => 'decimal:2',
        'approved_at'   => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(RodMember::class)->orderBy('sort_order');
    }

    public function getTotalWeightKgAttribute(): float
    {
        return round(
            $this->members->sum(fn($member) => $member->bars->sum('total_weight')),
            2
        );
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
