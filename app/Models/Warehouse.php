<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_address',
        'status',
    ];

    /**
     * Get the stock items stored in this warehouse.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
