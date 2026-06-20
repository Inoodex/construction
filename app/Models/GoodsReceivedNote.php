<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodsReceivedNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'site_id',
        'grn_number',
        'received_date',
        'received_by',
        'delivery_note',
        'vehicle_number',
        'status',
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    /**
     * Get the purchase order associated with the GRN.
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the site where goods were delivered.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the user who received the goods.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Get the items in this GRN.
     */
    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceivedNoteItem::class);
    }
}
