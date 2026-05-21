<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'requested_by',
        'requisition_number',
        'status',
        'required_date',
    ];

    protected $casts = [
        'required_date' => 'date',
    ];

    /**
     * Get the project associated with the requisition.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who requested the requisition.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the items in this requisition.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequisitionItem::class);
    }
}
