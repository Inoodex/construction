<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'report_type',
        'configuration',
        'created_by',
    ];

    protected $casts = [
        'configuration' => 'array',
    ];

    /**
     * Get the user who created the report template.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the scheduled deliveries of this report template.
     */
    public function scheduledReports(): HasMany
    {
        return $this->hasMany(ScheduledReport::class);
    }
}
