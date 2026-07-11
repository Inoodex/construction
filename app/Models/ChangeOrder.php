<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ChangeOrder extends Model
{
    use InteractsWithMedia;
    protected $fillable = [
        'project_id',
        'change_order_number',
        'title',
        'description',
        'type',
        'status',
        'cost_impact',
        'time_impact_days',
        'rfi_id',
        'requested_by',
        'approved_by',
        'approved_date',
        'attachment_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'cost_impact' => 'decimal:2',
            'approved_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function rfi(): BelongsTo
    {
        return $this->belongsTo(Rfi::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public static function generateChangeOrderNumber(): string
    {
        $prefix = 'VO-' . now()->format('Ymd') . '-';
        $last = static::where('change_order_number', 'like', $prefix . '%')->count();

        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachment')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        //
    }
}
