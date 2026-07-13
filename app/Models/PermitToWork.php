<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermitToWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'project_id',
        'site_id',
        'requested_by',
        'approved_by',
        'permit_type',
        'work_location',
        'description_of_work',
        'hazards_identified',
        'safety_measures',
        'valid_from',
        'valid_until',
        'status',
        'conditions',
        'cancellation_reason',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public static function generatePermitNumber(): string
    {
        $date = now()->format('Ymd');
        $last = static::where('permit_number', 'like', "PTW-{$date}-%")->count();
        return sprintf('PTW-%s-%04d', $date, $last + 1);
    }
}
