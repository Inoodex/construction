<?php

namespace App\Traits;

use App\Models\Approval;

/**
 * Approvable Trait
 * 
 * This trait can be used by any model that requires approval workflows
 * (e.g., PurchaseRequisition, PurchaseOrder, Invoice, Tender)
 */
trait Approvable
{
    /**
     * Get all approvals for this model
     */
    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    /**
     * Get the current approval record
     */
    public function currentApproval()
    {
        return $this->approvals()->where('status', 'pending')->first();
    }

    /**
     * Check if model is pending approval
     */
    public function isPendingApproval(): bool
    {
        return $this->currentApproval() !== null;
    }

    /**
     * Check if model is approved
     */
    public function isApproved(): bool
    {
        return $this->approvals()
            ->where('status', 'approved')
            ->whereHas('workflow')
            ->count() > 0;
    }

    /**
     * Check if model is rejected
     */
    public function isRejected(): bool
    {
        return $this->approvals()
            ->where('status', 'rejected')
            ->first() !== null;
    }

    /**
     * Get approval status badge
     */
    public function getApprovalStatus(): string
    {
        $approval = $this->currentApproval();
        
        if (!$approval) {
            if ($this->isRejected()) {
                return 'rejected';
            }
            if ($this->isApproved()) {
                return 'approved';
            }
            return 'no_workflow';
        }

        return 'pending';
    }

    /**
     * Get remaining approvers at current level
     */
    public function getRemainingApprovers()
    {
        $approval = $this->currentApproval();
        
        if (!$approval) {
            return collect();
        }

        return $approval->getRemainingApprovers();
    }

    /**
     * Get current approval level
     */
    public function getCurrentApprovalLevel(): ?int
    {
        $approval = $this->currentApproval();
        return $approval ? $approval->current_level : null;
    }
}
