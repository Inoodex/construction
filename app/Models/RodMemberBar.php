<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RodMemberBar extends Model
{
    use HasFactory;

    protected $fillable = [
        'rod_member_id',
        'bar_name',
        'direction',
        'diameter',
        'spacing',
        'hook_length',
        'bend_length',
        'lap_length',
        'actual_size',
        'cutting_length',
        'bars_count',
        'total_length',
        'unit_weight',
        'total_weight',
        'shape_code',
        'is_manual_count',
        'sort_order',
        'remarks',
    ];

    protected $casts = [
        'diameter'       => 'decimal:2',
        'spacing'        => 'decimal:2',
        'hook_length'    => 'decimal:2',
        'bend_length'    => 'decimal:2',
        'lap_length'     => 'decimal:2',
        'actual_size'    => 'decimal:2',
        'cutting_length' => 'decimal:2',
        'total_length'   => 'decimal:2',
        'unit_weight'    => 'decimal:4',
        'total_weight'   => 'decimal:2',
        'is_manual_count' => 'boolean',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(RodMember::class, 'rod_member_id');
    }
}
