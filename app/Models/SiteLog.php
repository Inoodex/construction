<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteLog extends Model
{
    protected $fillable = [
        'site_id',
        'submitted_by',
        'report_type',
        'title',
        'description',
        'log_date',
        'weather_conditions',
        'temperature',
        'worker_count',
        'work_completed',
        'equipment_used',
        'materials_received',
        'issues_notes',
        'status',
    ];

    protected $casts = [
        'log_date' => 'date',
        'temperature' => 'decimal:1',
        'worker_count' => 'integer',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
