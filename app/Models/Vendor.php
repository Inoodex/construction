<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'credit_limit',
        'payment_terms',
        'performance_rating',
        'is_blacklisted',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'performance_rating' => 'integer',
        'is_blacklisted' => 'boolean',
    ];
}
