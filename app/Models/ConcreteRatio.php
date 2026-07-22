<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConcreteRatio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'reference_no',
        'title',
        'description',
        'grade',
        'rod_calculation_id',
        'status',
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

    public function rodCalculation(): BelongsTo
    {
        return $this->belongsTo(RodCalculation::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ConcreteRatioMember::class)->orderBy('sort_order');
    }

    public function getTotalVolumeM3Attribute(): float
    {
        return round($this->members->sum('volume_m3'), 4);
    }

    public function getTotalCementBagsAttribute(): float
    {
        return round($this->members->sum('cement_bags'), 2);
    }

    public function getTotalSandM3Attribute(): float
    {
        return round($this->members->sum('sand_m3'), 4);
    }

    public function getTotalAggregateM3Attribute(): float
    {
        return round($this->members->sum('aggregate_m3'), 4);
    }

    public function getTotalWaterLitersAttribute(): float
    {
        return round($this->members->sum('water_liters'), 2);
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
