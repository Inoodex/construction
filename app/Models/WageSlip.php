<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WageSlip extends Model
{
    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'total_days',
        'present_days',
        'absent_days',
        'late_days',
        'half_days',
        'holidays',
        'basic_pay',
        'overtime_pay',
        'allowances',
        'deductions',
        'net_pay',
        'status',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'basic_pay' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
