<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcontractor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'phone',
        'address',
        'trade_category',
        'specialization',
        'license_number',
        'status',
        'performance_rating',
    ];

    protected $casts = [
        'performance_rating' => 'integer',
    ];
}
