<?php

namespace App\Models;

use App\Constants\ConcreteRatioConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RodMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'rod_calculation_id',
        'type',
        'member_code',
        'quantity',
        'length',
        'width',
        'height',
        'depth',
        'thickness',
        'cover',
        'sort_order',
        'remarks',
    ];

    protected $casts = [
        'length'    => 'decimal:2',
        'width'     => 'decimal:2',
        'height'    => 'decimal:2',
        'depth'     => 'decimal:2',
        'thickness' => 'decimal:2',
        'cover'     => 'decimal:2',
    ];

    public function calculation(): BelongsTo
    {
        return $this->belongsTo(RodCalculation::class, 'rod_calculation_id');
    }

    public function bars(): HasMany
    {
        return $this->hasMany(RodMemberBar::class, 'rod_member_id')->orderBy('sort_order');
    }

    public function resolveThirdDimension(): float
    {
        $type      = strtolower($this->type ?? '');
        $height    = (float) ($this->height ?? 0);
        $depth     = (float) ($this->depth ?? 0);
        $thickness = (float) ($this->thickness ?? 0);

        return match (true) {
            in_array($type, ['slab', 'wall'])              => $thickness ?: $height ?: $depth,
            in_array($type, ['footing', 'pile_cap'])       => $depth ?: $height ?: $thickness,
            in_array($type, ['pile'])                      => $height ?: $depth,
            in_array($type, ['column', 'beam', 'grade_beam', 'lintel', 'stair']) => $height ?: $depth ?: $thickness,
            default                                        => $height ?: $depth ?: $thickness,
        };
    }

    public function getVolumeM3Attribute(): float
    {
        $length   = (float) ($this->length ?? 0);
        $width    = (float) ($this->width ?? 0);
        $third    = $this->resolveThirdDimension();
        $quantity = max(1, (int) $this->quantity);

        return round(($length * $width * $third) / 1_000_000_000 * $quantity, 4);
    }

    public function getVolumeCftAttribute(): float
    {
        return round($this->volume_m3 * ConcreteRatioConstants::M3_TO_CFT, 2);
    }

    public function getConcreteBreakdownAttribute(): array
    {
        $vol   = $this->volume_m3;
        $grade = $this->calculation->concrete_grade ?? null;

        if ($grade && isset(ConcreteRatioConstants::MATERIAL_FACTORS[$grade])) {
            $factors = ConcreteRatioConstants::MATERIAL_FACTORS[$grade];
            $cement  = round($vol * $factors['cement_bags'], 2);
            $sandM3  = round($vol * $factors['sand_m3'], 4);
            $aggM3   = round($vol * $factors['aggregate_m3'], 4);
            $water   = round($vol * $factors['water_liters'], 2);
        } else {
            $cement = 0; $sandM3 = 0; $aggM3 = 0; $water = 0;
        }

        $m3ToCft = ConcreteRatioConstants::M3_TO_CFT;

        return [
            'volume_m3'     => $vol,
            'volume_cft'    => round($vol * $m3ToCft, 2),
            'cement_bags'   => $cement,
            'sand_m3'       => $sandM3,
            'sand_cft'      => round($sandM3 * $m3ToCft, 2),
            'aggregate_m3'  => $aggM3,
            'aggregate_cft' => round($aggM3 * $m3ToCft, 2),
            'water_liters'  => $water,
        ];
    }
}
