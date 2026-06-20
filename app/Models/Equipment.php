<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'code', 'name', 'category', 'make', 'model', 'year', 'serial_number',
        'acquisition_type', 'purchase_cost', 'purchase_date', 'useful_life_years',
        'salvage_value', 'current_value', 'status', 'location', 'operator',
        'meter_hours', 'maintenance_interval_hours', 'next_maintenance_hours', 'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(EquipmentMaintenance::class, 'equipment_id');
    }
}
