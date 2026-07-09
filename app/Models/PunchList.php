<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PunchList extends Model
{
    protected $fillable = [
        'project_id', 'punch_list_number', 'title', 'description',
        'status', 'inspection_date', 'due_date', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'inspection_date' => 'date',
            'due_date' => 'date',
        ];
    }

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
        return $this->hasMany(PunchListItem::class)->orderBy('order_index');
    }

    public function getCompletionPercentAttribute(): int
    {
        $total = $this->items()->count();
        if ($total === 0) return 0;
        $completed = $this->items()->whereIn('status', ['completed', 'verified'])->count();
        return round(($completed / $total) * 100);
    }

    public static function generatePunchListNumber(): string
    {
        $prefix = 'PL-' . now()->format('Ymd') . '-';
        $last = static::where('punch_list_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
