<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DrawingRevision extends Model
{
    use InteractsWithMedia;
    protected $fillable = [
        'drawing_id',
        'revision',
        'revision_date',
        'description',
        'file_path',
        'uploaded_by',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'revision_date' => 'date',
            'is_current' => 'boolean',
        ];
    }

    public function drawing(): BelongsTo
    {
        return $this->belongsTo(Drawing::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('drawing_file')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        //
    }
}
