<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceivedNoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_received_note_id',
        'material_id',
        'quantity_received',
        'quantity_accepted',
        'quantity_rejected',
    ];

    protected $casts = [
        'quantity_received' => 'decimal:4',
        'quantity_accepted' => 'decimal:4',
        'quantity_rejected' => 'decimal:4',
    ];

    /**
     * Get the goods received note that owns this item.
     */
    public function goodsReceivedNote(): BelongsTo
    {
        return $this->belongsTo(GoodsReceivedNote::class);
    }

    /**
     * Get the material associated with the item.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
