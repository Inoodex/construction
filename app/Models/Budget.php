<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Approvable;

class Budget extends Model
{
    use HasFactory, Approvable;

    protected $fillable = [
        'project_id',
        'cost_code',
        'description',
        'budgeted_amount',
        'planned_value',
        'earned_value',
        'actual_cost',
        'financial_year',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'planned_value' => 'decimal:2',
        'earned_value' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    public function getSpIAttribute(): float
    {
        return $this->planned_value > 0
            ? round($this->earned_value / $this->planned_value, 2)
            : 0;
    }

    public function getCpIAttribute(): float
    {
        return $this->actual_cost > 0
            ? round($this->earned_value / $this->actual_cost, 2)
            : 0;
    }

    public function getEtcAttribute(): float
    {
        $cpi = $this->cpi;
        return $cpi > 0
            ? round(($this->budgeted_amount - $this->earned_value) / $cpi, 2)
            : 0;
    }

    public function getEacAttribute(): float
    {
        return round($this->actual_cost + $this->etc, 2);
    }

    public function getVarianceAttribute(): float
    {
        return round($this->budgeted_amount - $this->actual_cost, 2);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
