<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\PermitToWork;
use App\Models\Project;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;

class PermitToWorkController extends Controller
{
    public function index(Request $request)
    {
        $query = PermitToWork::with(['project', 'site', 'requester', 'approver']);

        if ($request->filled('permit_type')) {
            $query->where('permit_type', $request->permit_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $permits = $query->latest()->paginate(20)->withQueryString();
        $projects = Project::orderBy('name')->get();

        return view('admin.hr.permits-to-work.index', compact('permits', 'projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.hr.permits-to-work.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'requested_by' => 'required|exists:users,id',
            'permit_type' => 'required|in:hot_work,confined_space,working_at_height,electrical,excavation,lifting,radiography,other',
            'work_location' => 'required|string|max:255',
            'description_of_work' => 'required|string',
            'hazards_identified' => 'required|string',
            'safety_measures' => 'required|string',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'conditions' => 'nullable|string',
        ]);

        $validated['permit_number'] = PermitToWork::generatePermitNumber();
        $validated['status'] = 'draft';

        PermitToWork::create($validated);

        return redirect()->route('admin.hr.permits-to-work.index')
            ->with('success', 'Permit to Work created successfully.');
    }

    public function show(PermitToWork $permitToWork)
    {
        $permitToWork->load(['project', 'site', 'requester', 'approver']);

        return view('admin.hr.permits-to-work.show', compact('permitToWork'));
    }

    public function edit(PermitToWork $permitToWork)
    {
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.hr.permits-to-work.edit', compact('permitToWork', 'projects', 'users'));
    }

    public function update(Request $request, PermitToWork $permitToWork)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'requested_by' => 'required|exists:users,id',
            'permit_type' => 'required|in:hot_work,confined_space,working_at_height,electrical,excavation,lifting,radiography,other',
            'work_location' => 'required|string|max:255',
            'description_of_work' => 'required|string',
            'hazards_identified' => 'required|string',
            'safety_measures' => 'required|string',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'status' => 'required|in:draft,pending_approval,approved,active,completed,cancelled',
            'conditions' => 'nullable|string',
            'cancellation_reason' => 'nullable|string',
        ]);

        $permitToWork->update($validated);

        return redirect()->route('admin.hr.permits-to-work.show', $permitToWork)
            ->with('success', 'Permit to Work updated successfully.');
    }

    public function destroy(PermitToWork $permitToWork)
    {
        $permitToWork->delete();

        return redirect()->route('admin.hr.permits-to-work.index')
            ->with('success', 'Permit to Work deleted successfully.');
    }
}
