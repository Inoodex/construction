<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DrawingTransmittal extends Model
{
    protected $fillable = [
        'project_id',
        'transmittal_number',
        'to_party',
        'from_user_id',
        'sent_date',
        'purpose',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sent_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DrawingTransmittalItem::class);
    }

    public static function generateTransmittalNumber(): string
    {
        $prefix = 'TRN-' . now()->format('Ymd') . '-';
        $last = static::where('transmittal_number', 'like', $prefix . '%')->count();

        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
