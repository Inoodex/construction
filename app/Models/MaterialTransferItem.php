<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialTransferItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_transfer_id',
        'material_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
    ];

    /**
     * Get the material transfer that owns the item.
     */
    public function transfer(): BelongsTo
    {
        return $this->belongsTo(MaterialTransfer::class, 'material_transfer_id');
    }

    /**
     * Get the material associated with the item.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
