<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrawingTransmittalItem extends Model
{
    protected $fillable = [
        'drawing_transmittal_id',
        'drawing_id',
        'drawing_revision_id',
        'copies',
    ];

    public function transmittal(): BelongsTo
    {
        return $this->belongsTo(DrawingTransmittal::class, 'drawing_transmittal_id');
    }

    public function drawing(): BelongsTo
    {
        return $this->belongsTo(Drawing::class);
    }

    public function revision(): BelongsTo
    {
        return $this->belongsTo(DrawingRevision::class, 'drawing_revision_id');
    }
}
