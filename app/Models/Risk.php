<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Risk extends Model
{
    protected $fillable = [
        'project_id',
        'risk_number',
        'title',
        'description',
        'category',
        'probability',
        'impact',
        'risk_score',
        'status',
        'risk_owner_id',
        'identified_date',
        'review_date',
        'due_date',
        'closed_date',
        'mitigation_plan',
        'contingency_plan',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'identified_date' => 'date',
            'review_date' => 'date',
            'due_date' => 'date',
            'closed_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'risk_owner_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateRiskNumber(): string
    {
        $prefix = 'RSK-' . now()->format('Ymd') . '-';
        $last = static::where('risk_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public static function calculateScore(string $probability, string $impact): int
    {
        $probMap = ['very_low' => 1, 'low' => 2, 'medium' => 3, 'high' => 4, 'very_high' => 5];
        $impMap = ['very_low' => 1, 'low' => 2, 'medium' => 3, 'high' => 4, 'very_high' => 5];

        return ($probMap[$probability] ?? 3) * ($impMap[$impact] ?? 3);
    }

    public static function scoreLabel(int $score): string
    {
        if ($score <= 5) return 'Low';
        if ($score <= 12) return 'Medium';
        return 'High';
    }

    public static function scoreColor(int $score): string
    {
        if ($score <= 5) return 'badge-outline-success';
        if ($score <= 12) return 'badge-outline-warning';
        return 'badge-outline-danger';
    }
}
