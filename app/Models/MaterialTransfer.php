<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_warehouse_id',
        'to_site_id',
        'transfer_number',
        'status',
        'transfer_date',
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    /**
     * Get the source warehouse of the transfer.
     */
    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    /**
     * Get the destination site of the transfer.
     */
    public function toSite(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'to_site_id');
    }

    /**
     * Get the items in this transfer.
     */
    public function items(): HasMany
    {
        return $this->hasMany(MaterialTransferItem::class);
    }
}
