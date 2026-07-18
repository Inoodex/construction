<?php

namespace App\Models;

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
}
