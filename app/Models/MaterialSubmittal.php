<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialSubmittal extends Model
{
    protected $fillable = [
        'project_id',
        'submittal_number',
        'title',
        'description',
        'material_name',
        'manufacturer',
        'brand',
        'model_reference',
        'specification_details',
        'quantity_unit',
        'status',
        'submitted_by',
        'submitted_date',
        'reviewed_by',
        'review_date',
        'review_comments',
        'resubmission_deadline',
    ];

    protected $casts = [
        'submitted_date' => 'date',
        'review_date' => 'date',
        'resubmission_deadline' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
