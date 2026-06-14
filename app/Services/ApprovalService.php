<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\ApprovalWorkflow;
use App\Models\ApprovalHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    /**
     * Submit a model for approval
     * 
     * @param Model $model The model to submit for approval (must use Approvable trait)
     * @param string $documentType The document type (e.g., 'purchase_requisition')
     * @param float $amount The total amount for approval routing
     * @param int $userId The user submitting for approval
     * @return Approval|null
     */
    public function submitForApproval(Model $model, string $documentType, float $amount, int $userId): ?Approval
    {
        DB::beginTransaction();

        try {
            // Get the workflow for this document type
            $workflow = ApprovalWorkflow::where('document_type', $documentType)
                ->where('is_active', true)
                ->first();

            // If no workflow exists, mark as approved automatically
            if (!$workflow) {
                DB::commit();
                return null;
            }

            // Create approval record
            $approval = Approval::create([
                'approval_workflow_id' => $workflow->id,
                'approvable_type' => get_class($model),
                'approvable_id' => $model->id,
                'current_level' => 1,
                'status' => 'pending',
                'submitted_by' => $userId,
                'submitted_at' => now(),
                'total_amount' => $amount,
            ]);

            DB::commit();

            return $approval;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Approve a pending request
     * 
     * @param Approval $approval The approval record
     * @param int $userId The user approving
     * @param string $comment Optional comment
     * @return bool True if final approval, false if moved to next level
     */
    public function approve(Approval $approval, int $userId, string $comment = ''): bool
    {
        DB::beginTransaction();

        try {
            // Record approval in history
            ApprovalHistory::create([
                'approval_id' => $approval->id,
                'approval_level' => $approval->current_level,
                'approved_by' => $userId,
                'status' => 'approved',
                'comment' => $comment,
                'approved_at' => now(),
            ]);

            // Check if all approvers at this level have approved
            if ($approval->isCurrentLevelApproved()) {
                // Check if there's a next level
                $nextLevel = $approval->getNextLevel();

                if ($nextLevel) {
                    // Move to next level
                    $approval->update([
                        'current_level' => $nextLevel,
                    ]);

                    DB::commit();
                    return false; // More approvals needed
                } else {
                    // Final approval
                    $approval->update([
                        'status' => 'approved',
                    ]);

                    DB::commit();
                    return true; // Fully approved
                }
            }

            DB::commit();
            return false; // Waiting for other approvers at this level
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject a pending request
     * 
     * @param Approval $approval The approval record
     * @param int $userId The user rejecting
     * @param string $comment Rejection reason
     * @return void
     */
    public function reject(Approval $approval, int $userId, string $comment = ''): void
    {
        DB::beginTransaction();

        try {
            // Record rejection in history
            ApprovalHistory::create([
                'approval_id' => $approval->id,
                'approval_level' => $approval->current_level,
                'approved_by' => $userId,
                'status' => 'rejected',
                'comment' => $comment,
                'approved_at' => now(),
            ]);

            // Update approval status
            $approval->update([
                'status' => 'rejected',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check if a user can approve a request
     * 
     * @param Approval $approval The approval record
     * @param int $userId The user ID to check
     * @return bool
     */
    public function canApprove(Approval $approval, int $userId): bool
    {
        // Check if user has already approved at this level
        $hasApproved = $approval->history()
            ->where('approval_level', $approval->current_level)
            ->where('approved_by', $userId)
            ->where('status', 'approved')
            ->exists();

        if ($hasApproved) {
            return false;
        }

        // Check if user is in the approvers list at current level
        $approvers = $approval->getCurrentLevelApprovers();
        return $approvers->pluck('id')->contains($userId);
    }

    /**
     * Get pending approvals for a user
     * 
     * @param int $userId The user ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingApprovalsForUser(int $userId)
    {
        // Get user's roles
        $user = \App\Models\User::find($userId);
        $roleIds = $user->roles()->pluck('roles.id')->toArray();

        // Get approval workflows where user's role is an approver
        return Approval::where('status', 'pending')
            ->whereHas('workflow.matrices', function ($query) use ($roleIds) {
                $query->whereIn('role_id', $roleIds)
                    ->where('is_active', true);
            })
            ->with(['approvable', 'workflow', 'history'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Withdraw an approval request (only before final approval)
     * 
     * @param Approval $approval
     * @param int $userId
     * @return bool
     */
    public function withdraw(Approval $approval, int $userId): bool
    {
        // Only the submitter can withdraw
        if ($approval->submitted_by !== $userId) {
            return false;
        }

        // Can only withdraw if pending
        if ($approval->status !== 'pending') {
            return false;
        }

        $approval->update(['status' => 'withdrawn']);
        return true;
    }
}
