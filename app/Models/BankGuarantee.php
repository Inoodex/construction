<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankGuarantee extends Model
{
    protected $fillable = [
        'reference_number',
        'type',
        'issuing_bank',
        'project_id',
        'beneficiary',
        'amount',
        'issue_date',
        'expiry_date',
        'return_date',
        'status',
        'narration',
        'document_path',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'return_date' => 'date',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
