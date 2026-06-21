<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentReport extends Model
{
    protected $fillable = [
        'employee_id',
        'incident_date',
        'incident_time',
        'location',
        'incident_type',
        'severity',
        'description',
        'immediate_action',
        'root_cause',
        'corrective_action',
        'affected_persons',
        'property_damage',
        'reported_by',
        'status',
        'investigation_notes',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'incident_time' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
