<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafetyAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_number',
        'project_id',
        'site_id',
        'auditor_id',
        'audit_date',
        'audit_type',
        'scope',
        'findings',
        'non_conformances',
        'recommendations',
        'status',
        'score',
        'notes',
    ];

    protected $casts = [
        'audit_date' => 'date',
        'score' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public static function generateAuditNumber(): string
    {
        $date = now()->format('Ymd');
        $last = static::where('audit_number', 'like', "SA-{$date}-%")->count();
        return sprintf('SA-%s-%04d', $date, $last + 1);
    }
}
