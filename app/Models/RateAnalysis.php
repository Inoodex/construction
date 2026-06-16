<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RateAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'ra_number',
        'title',
        'description',
        'total_rate',
        'status',
        'created_by',
    ];

    protected $casts = [
        'total_rate' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RateAnalysisItem::class);
    }
}
