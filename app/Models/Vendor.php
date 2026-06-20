<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'phone',
        'address',
        'trade_category',
        'status',
        'qualification_status',
        'qualified_at',
        'qualified_by',
        'credit_limit',
        'payment_terms',
        'performance_rating',
        'is_blacklisted',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'performance_rating' => 'integer',
        'is_blacklisted' => 'boolean',
        'qualified_at' => 'datetime',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function qualifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qualified_by');
    }
}
