<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorrectiveAction extends Model
{
    protected $fillable = [
        'ncr_id', 'punch_list_item_id', 'project_id', 'car_number', 'title',
        'description', 'root_cause', 'corrective_action', 'preventive_action',
        'responsible_person', 'target_date', 'completed_date', 'status',
        'verified_by', 'verified_date', 'effectiveness_check', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
            'completed_date' => 'date',
            'verified_date' => 'date',
        ];
    }

    public function ncr(): BelongsTo
    {
        return $this->belongsTo(Ncr::class);
    }

    public function punchListItem(): BelongsTo
    {
        return $this->belongsTo(PunchListItem::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateCarNumber(): string
    {
        $prefix = 'CAR-' . now()->format('Ymd') . '-';
        $last = static::where('car_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
