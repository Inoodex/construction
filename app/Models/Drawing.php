<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Drawing extends Model
{
    protected $fillable = [
        'project_id',
        'drawing_number',
        'title',
        'drawing_type',
        'discipline',
        'current_revision',
        'status',
        'description',
        'created_by',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(DrawingRevision::class)->orderByDesc('revision_date')->orderByDesc('id');
    }

    public function currentRevision(): ?DrawingRevision
    {
        return $this->revisions()->where('is_current', true)->first();
    }

    public function rfis(): HasMany
    {
        return $this->hasMany(Rfi::class);
    }

    public static function generateDrawingNumber(int $projectId): string
    {
        $prefix = 'DRW-' . now()->format('Ymd') . '-';
        $last = static::where('project_id', $projectId)
            ->where('drawing_number', 'like', $prefix . '%')
            ->count();

        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
