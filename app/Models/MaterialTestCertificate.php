<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialTestCertificate extends Model
{
    protected $fillable = [
        'project_id', 'material_name', 'material_type', 'supplier',
        'batch_number', 'certificate_number', 'test_date', 'test_result',
        'test_parameters', 'compliance_status', 'certificate_file',
        'valid_until', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'test_date' => 'date',
            'valid_until' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
