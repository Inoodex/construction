<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_template_id',
        'recipients',
        'frequency',
        'next_run_at',
        'last_run_at',
        'status',
    ];

    protected $casts = [
        'recipients' => 'array',
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime',
    ];

    /**
     * Get the report template that this schedule runs.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }
}
