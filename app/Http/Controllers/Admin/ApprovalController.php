<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\ApprovalWorkflow;
use App\Models\ApprovalMatrix;
use App\Models\Role;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ApprovalController extends Controller
{
    private ApprovalService $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    /**
     * Display pending approvals for current user
     */
    public function index()
    {
        $pendingApprovals = $this->approvalService->getPendingApprovalsForUser(auth()->id());

        return view('admin.approvals.index', [
            'approvals' => $pendingApprovals,
        ]);
    }

    /**
     * Show a specific approval request with detail
     */
    public function show(Approval $approval)
    {
        // Check if user can view this approval
        if (!$this->approvalService->canApprove($approval, auth()->id())) {
            abort(403, 'Unauthorized');
        }

        return view('admin.approvals.show', [
            'approval' => $approval->load(['approvable', 'workflow', 'history.approver', 'submitter']),
            'canApprove' => true,
        ]);
    }

    /**
     * Approve an approval request
     */
    public function approve(Request $request, Approval $approval)
    {
        // Validate user can approve
        if (!$this->approvalService->canApprove($approval, auth()->id())) {
            return response()->json(['error' => 'You are not authorized to approve this request'], 403);
        }

        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $isFinalApproval = $this->approvalService->approve(
                $approval,
                auth()->id(),
                $request->input('comment', '')
            );

            return response()->json([
                'success' => true,
                'message' => $isFinalApproval ? 'Request approved successfully!' : 'Approval recorded. Waiting for other approvers.',
                'status' => $approval->fresh()->status,
                'level' => $approval->fresh()->current_level,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Reject an approval request
     */
    public function reject(Request $request, Approval $approval)
    {
        // Validate user can approve
        if (!$this->approvalService->canApprove($approval, auth()->id())) {
            return response()->json(['error' => 'You are not authorized to reject this request'], 403);
        }

        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        try {
            $this->approvalService->reject(
                $approval,
                auth()->id(),
                $request->input('comment')
            );

            return response()->json([
                'success' => true,
                'message' => 'Request rejected successfully!',
                'status' => 'rejected',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Withdraw an approval request (user only)
     */
    public function withdraw(Request $request, Approval $approval)
    {
        // Only submitter can withdraw
        if ($approval->submitted_by !== auth()->id()) {
            return response()->json(['error' => 'Only the submitter can withdraw this request'], 403);
        }

        try {
            $this->approvalService->withdraw($approval, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Request withdrawn successfully!',
                'status' => 'withdrawn',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ===== Workflow Configuration Methods =====

    /**
     * Show approval workflow configuration page
     */
    public function configureWorkflows()
    {
        

        $workflows = ApprovalWorkflow::with('matrices.role')->get();

        return view('admin.approvals.workflows.index', [
            'workflows' => $workflows,
        ]);
    }

    /**
     * Show form to create new workflow
     */
    public function createWorkflow()
    {
        

        $roles = Role::where('is_active', true)->get();

        return view('admin.approvals.workflows.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a new workflow
     */
    public function storeWorkflow(Request $request)
    {
        

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document_type' => 'required|string|unique:approval_workflows,document_type',
            'description' => 'nullable|string',
            'matrices' => 'required|array|min:1',
            'matrices.*.role_id' => 'required|exists:roles,id',
            'matrices.*.approval_level' => 'required|integer|min:1',
            'matrices.*.min_amount' => 'required|numeric|min:0',
            'matrices.*.max_amount' => 'required|numeric|gte:matrices.*.min_amount',
        ]);

        DB::beginTransaction();

        try {
            $workflow = ApprovalWorkflow::create([
                'name' => $validated['name'],
                'document_type' => $validated['document_type'],
                'description' => $validated['description'] ?? null,
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['matrices'] as $matrix) {
                ApprovalMatrix::create([
                    'approval_workflow_id' => $workflow->id,
                    'role_id' => $matrix['role_id'],
                    'approval_level' => $matrix['approval_level'],
                    'min_amount' => $matrix['min_amount'],
                    'max_amount' => $matrix['max_amount'],
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.approvals.workflows.index')
                ->with('success', 'Approval workflow created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create workflow: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit a workflow
     */
    public function editWorkflow(ApprovalWorkflow $workflow)
    {
        

        $roles = Role::where('is_active', true)->get();

        return view('admin.approvals.workflows.edit', [
            'workflow' => $workflow->load('matrices'),
            'roles' => $roles,
        ]);
    }

    /**
     * Update a workflow
     */
    public function updateWorkflow(Request $request, ApprovalWorkflow $workflow)
    {
        

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'matrices' => 'required|array|min:1',
            'matrices.*.id' => 'nullable|exists:approval_matrices,id',
            'matrices.*.role_id' => 'required|exists:roles,id',
            'matrices.*.approval_level' => 'required|integer|min:1',
            'matrices.*.min_amount' => 'required|numeric|min:0',
            'matrices.*.max_amount' => 'required|numeric|gte:matrices.*.min_amount',
        ]);

        DB::beginTransaction();

        try {
            $workflow->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            // Delete existing matrices and create new ones
            $workflow->matrices()->delete();

            foreach ($validated['matrices'] as $matrix) {
                ApprovalMatrix::create([
                    'approval_workflow_id' => $workflow->id,
                    'role_id' => $matrix['role_id'],
                    'approval_level' => $matrix['approval_level'],
                    'min_amount' => $matrix['min_amount'],
                    'max_amount' => $matrix['max_amount'],
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.approvals.workflows.index')
                ->with('success', 'Approval workflow updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update workflow: ' . $e->getMessage());
        }
    }

    /**
     * Delete a workflow
     */
    public function deleteWorkflow(ApprovalWorkflow $workflow)
    {
        

        // Check if workflow has active approvals
        if ($workflow->approvals()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Cannot delete workflow with pending approvals!');
        }

        $workflow->delete();

        return back()->with('success', 'Approval workflow deleted successfully!');
    }
}
