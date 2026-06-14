<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_workflow_id',
        'approvable_type',
        'approvable_id',
        'current_level',
        'status',
        'submitted_by',
        'submitted_at',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the approval workflow
     */
    public function workflow()
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'approval_workflow_id');
    }

    /**
     * Get the approvable model
     */
    public function approvable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who submitted for approval
     */
    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get all approval history for this approval
     */
    public function history()
    {
        return $this->hasMany(ApprovalHistory::class)->orderBy('created_at');
    }

    /**
     * Get approvers required at current level
     */
    public function getCurrentLevelApprovers()
    {
        return $this->workflow
            ->matrices()
            ->where('approval_level', $this->current_level)
            ->where('is_active', true)
            ->where('min_amount', '<=', $this->total_amount)
            ->where('max_amount', '>=', $this->total_amount)
            ->get()
            ->flatMap(fn($matrix) => $matrix->getApprovers());
    }

    /**
     * Get remaining approvers at current level
     */
    public function getRemainingApprovers()
    {
        $approvedByIds = $this->history()
            ->where('approval_level', $this->current_level)
            ->where('status', 'approved')
            ->pluck('approved_by')
            ->toArray();

        return $this->getCurrentLevelApprovers()
            ->reject(fn($user) => in_array($user->id, $approvedByIds));
    }

    /**
     * Check if approval level has all approvals
     */
    public function isCurrentLevelApproved(): bool
    {
        $requiredApprovals = $this->getCurrentLevelApprovers()->count();
        $approvedCount = $this->history()
            ->where('approval_level', $this->current_level)
            ->where('status', 'approved')
            ->distinct('approved_by')
            ->count();

        return $approvedCount >= $requiredApprovals;
    }

    /**
     * Get next approval level count
     */
    public function getNextLevel()
    {
        return $this->workflow
            ->matrices()
            ->where('approval_level', '>', $this->current_level)
            ->where('is_active', true)
            ->where('min_amount', '<=', $this->total_amount)
            ->where('max_amount', '>=', $this->total_amount)
            ->min('approval_level');
    }

    /**
     * Check if this is the final approval level
     */
    public function isFinalLevel(): bool
    {
        return $this->getNextLevel() === null;
    }
}
