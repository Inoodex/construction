<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HseChecklist extends Model
{
    protected $fillable = [
        'title', 'checklist_type', 'project_id', 'site_id', 'inspection_date',
        'user_id', 'status', 'findings', 'corrective_actions',
        'closure_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'inspection_date' => 'date',
            'closure_date' => 'date',
        ];
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(HseChecklistItem::class)->orderBy('order_index');
    }
}
