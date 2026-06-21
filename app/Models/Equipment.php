<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'code', 'name', 'category', 'make', 'model', 'year', 'serial_number',
        'acquisition_type', 'purchase_cost', 'purchase_date', 'useful_life_years',
        'salvage_value', 'current_value', 'status', 'location', 'operator',
        'meter_hours', 'maintenance_interval_hours', 'next_maintenance_hours', 'notes',
        'project_id', 'site_id', 'allocated_date', 'deallocated_date',
        'hire_rate', 'hire_rate_period', 'hire_start_date', 'hire_end_date', 'hire_vendor',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'allocated_date' => 'date',
        'deallocated_date' => 'date',
        'hire_start_date' => 'date',
        'hire_end_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'hire_rate' => 'decimal:2',
    ];

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(EquipmentMaintenance::class, 'equipment_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
