<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRequisitionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_requisition_id',
        'material_id',
        'quantity',
        'estimated_unit_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'estimated_unit_price' => 'decimal:2',
    ];

    /**
     * Get the purchase requisition that owns the item.
     */
    public function requisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class, 'purchase_requisition_id');
    }

    /**
     * Get the material associated with the item.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
