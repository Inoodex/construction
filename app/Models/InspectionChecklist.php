<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionChecklist extends Model
{
    protected $fillable = [
        'site_id', 'title', 'description', 'inspector_id',
        'inspection_date', 'status', 'notes',
    ];

    protected $casts = [
        'inspection_date' => 'date',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InspectionChecklistItem::class)->orderBy('order_index');
    }
}
