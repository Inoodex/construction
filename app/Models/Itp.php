<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Itp extends Model
{
    protected $fillable = [
        'project_id', 'itp_number', 'title', 'description',
        'phase', 'status', 'created_by',
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
        return $this->hasMany(ItpItem::class)->orderBy('order_index');
    }

    public function getCompletionPercentAttribute(): int
    {
        $total = $this->items()->count();
        if ($total === 0) return 0;
        $passed = $this->items()->whereIn('status', ['passed', 'n_a'])->count();
        return round(($passed / $total) * 100);
    }

    public static function generateItpNumber(): string
    {
        $prefix = 'ITP-' . now()->format('Ymd') . '-';
        $last = static::where('itp_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
