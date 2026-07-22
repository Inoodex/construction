<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConcreteRatioMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'concrete_ratio_id',
        'rod_member_id',
        'type',
        'member_code',
        'quantity',
        'length',
        'width',
        'height',
        'depth',
        'thickness',
        'volume_m3',
        'cement_bags',
        'sand_m3',
        'aggregate_m3',
        'water_liters',
        'sort_order',
        'remarks',
    ];

    protected $casts = [
        'length'         => 'decimal:2',
        'width'          => 'decimal:2',
        'height'         => 'decimal:2',
        'depth'          => 'decimal:2',
        'thickness'      => 'decimal:2',
        'volume_m3'      => 'decimal:4',
        'cement_bags'    => 'decimal:2',
        'sand_m3'        => 'decimal:4',
        'aggregate_m3'   => 'decimal:4',
        'water_liters'   => 'decimal:2',
    ];

    public function ratio(): BelongsTo
    {
        return $this->belongsTo(ConcreteRatio::class, 'concrete_ratio_id');
    }

    public function rodMember(): BelongsTo
    {
        return $this->belongsTo(RodMember::class);
    }
}
