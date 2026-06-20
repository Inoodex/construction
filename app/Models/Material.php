<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'unit',
        'reorder_level',
        'description',
    ];

    protected $casts = [
        'reorder_level' => 'decimal:4',
    ];
}
