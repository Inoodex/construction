<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentMaintenance extends Model
{
    protected $table = 'equipment_maintenance';

    protected $fillable = [
        'equipment_id', 'maintenance_date', 'type', 'description',
        'meter_hours', 'cost', 'vendor', 'next_due_date', 'notes', 'status',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_due_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
