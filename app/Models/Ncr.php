<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ncr extends Model
{
    protected $fillable = [
        'project_id', 'ncr_number', 'title', 'description', 'category',
        'severity', 'status', 'identified_date', 'due_date', 'location',
        'identified_by', 'root_cause', 'corrective_action', 'preventive_action',
        'closed_date', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'identified_date' => 'date',
            'due_date' => 'date',
            'closed_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function identifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'identified_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function correctiveActions(): HasMany
    {
        return $this->hasMany(CorrectiveAction::class);
    }

    public static function generateNcrNumber(): string
    {
        $prefix = 'NCR-' . now()->format('Ymd') . '-';
        $last = static::where('ncr_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
