<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprovalWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'document_type',
        'description',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created this workflow
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all approval matrices for this workflow
     */
    public function matrices()
    {
        return $this->hasMany(ApprovalMatrix::class);
    }

    /**
     * Get all approvals using this workflow
     */
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    /**
     * Get approval matrices ordered by level
     */
    public function getApprovalLevels()
    {
        return $this->matrices()
            ->where('is_active', true)
            ->orderBy('approval_level')
            ->get()
            ->groupBy('approval_level');
    }

    /**
     * Get approvers for a specific amount at a specific level
     */
    public function getApproversForLevel($level, $amount = 0)
    {
        return $this->matrices()
            ->where('approval_level', $level)
            ->where('is_active', true)
            ->where('min_amount', '<=', $amount)
            ->where('max_amount', '>=', $amount)
            ->get()
            ->pluck('role');
    }
}
