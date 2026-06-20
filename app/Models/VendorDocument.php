<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorDocument extends Model
{
    protected $fillable = [
        'vendor_id',
        'document_type',
        'title',
        'file_path',
        'expiry_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
