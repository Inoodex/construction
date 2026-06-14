<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Project;
use App\Models\Task;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkOrder::with('project', 'site', 'assignee', 'task');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('work_order_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $workOrders = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.core.work-orders.index', compact('workOrders', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $users = User::all();
        $sites = Site::all();
        $tasks = Task::all();
        return view('admin.core.work-orders.create', compact('projects', 'users', 'sites', 'tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'site_id' => 'nullable|exists:sites,id',
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,issued,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $validated['issued_by'] = Auth::id();

        $project = Project::findOrFail($validated['project_id']);
        $year = now()->format('Y');
        $count = WorkOrder::whereYear('created_at', $year)->count() + 1;
        $validated['work_order_number'] = 'WO-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        WorkOrder::create($validated);

        return redirect()->route('admin.core.work-orders.index')
            ->with('success', 'Work order created successfully.');
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load('project', 'task', 'site', 'assignee', 'issuer');
        return view('admin.core.work-orders.show', compact('workOrder'));
    }

    public function print(WorkOrder $workOrder)
    {
        $workOrder->load('project', 'task', 'site', 'assignee', 'issuer');
        return view('admin.core.work-orders.print', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $projects = Project::all();
        $users = User::all();
        $sites = Site::all();
        $tasks = Task::all();
        return view('admin.core.work-orders.edit', compact('workOrder', 'projects', 'users', 'sites', 'tasks'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'site_id' => 'nullable|exists:sites,id',
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'completed_date' => 'nullable|date',
            'status' => 'required|in:draft,issued,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $workOrder->update($validated);

        return redirect()->route('admin.core.work-orders.index')
            ->with('success', 'Work order updated successfully.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();
        return redirect()->route('admin.core.work-orders.index')
            ->with('success', 'Work order deleted successfully.');
    }
}
