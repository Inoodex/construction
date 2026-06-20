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
        'transfer_type',
        'from_warehouse_id',
        'from_site_id',
        'to_site_id',
        'to_warehouse_id',
        'transfer_number',
        'status',
        'transfer_date',
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function fromSite(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'from_site_id');
    }

    public function toSite(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'to_site_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MaterialTransferItem::class);
    }

    public function getFromLocationLabelAttribute(): string
    {
        return match($this->transfer_type) {
            'warehouse_to_site', 'warehouse_to_warehouse' => $this->fromWarehouse?->name ?? 'N/A',
            'site_to_warehouse', 'site_to_site' => $this->fromSite?->name ?? 'N/A',
            default => 'N/A',
        };
    }

    public function getToLocationLabelAttribute(): string
    {
        return match($this->transfer_type) {
            'warehouse_to_site', 'site_to_site' => $this->toSite?->name ?? 'N/A',
            'warehouse_to_warehouse', 'site_to_warehouse' => $this->toWarehouse?->name ?? 'N/A',
            default => 'N/A',
        };
    }

    public function getTransferTypeLabelAttribute(): string
    {
        return match($this->transfer_type) {
            'warehouse_to_site' => 'Warehouse → Site',
            'site_to_warehouse' => 'Site → Warehouse (Return)',
            'site_to_site' => 'Site → Site',
            'warehouse_to_warehouse' => 'Warehouse → Warehouse',
            default => $this->transfer_type,
        };
    }
}
