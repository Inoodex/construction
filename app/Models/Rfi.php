<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Rfi extends Model
{
    use InteractsWithMedia;
    protected $fillable = [
        'project_id',
        'rfi_number',
        'subject',
        'question',
        'drawing_id',
        'priority',
        'status',
        'raised_by',
        'assigned_to',
        'due_date',
        'answer',
        'answered_by',
        'answered_date',
        'attachment_path',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'answered_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function drawing(): BelongsTo
    {
        return $this->belongsTo(Drawing::class);
    }

    public function raiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'raised_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function answerer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function changeOrders(): HasMany
    {
        return $this->hasMany(ChangeOrder::class);
    }

    public static function generateRfiNumber(): string
    {
        $prefix = 'RFI-' . now()->format('Ymd') . '-';
        $last = static::where('rfi_number', 'like', $prefix . '%')->count();

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
