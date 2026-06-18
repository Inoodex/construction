<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['type', 'value', 'label', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type)->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeTradeCategories($query)
    {
        return $query->byType('trade_category');
    }

    public function scopeResourceTypes($query)
    {
        return $query->byType('resource_type');
    }
}
