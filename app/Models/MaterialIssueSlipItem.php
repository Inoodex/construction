<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialIssueSlipItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_issue_slip_id',
        'material_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
    ];

    /**
     * Get the issue slip that owns the item.
     */
    public function issueSlip(): BelongsTo
    {
        return $this->belongsTo(MaterialIssueSlip::class, 'material_issue_slip_id');
    }

    /**
     * Get the material associated with the item.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
