<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourEntry extends Model
{
    protected $fillable = [
        'project_id',
        'employee_id',
        'date',
        'hours',
        'hourly_rate',
        'description',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'hours' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTotalCostAttribute()
    {
        return $this->hours * $this->hourly_rate;
    }
}
