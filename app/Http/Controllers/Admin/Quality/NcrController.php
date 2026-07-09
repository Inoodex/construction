<?php

namespace App\Http\Controllers\Admin\Quality;

use App\Http\Controllers\Controller;
use App\Models\Ncr;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class NcrController extends Controller
{
    public function index(Request $request)
    {
        $query = Ncr::with('project', 'identifier');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $records = $query->latest('identified_date')->paginate(20);
        $projects = Project::orderBy('name')->get();

        return view('admin.quality.ncrs.index', compact('records', 'projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('admin.quality.ncrs.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:structural,material,workmanship,safety,other',
            'severity' => 'required|in:minor,major,critical',
            'identified_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:identified_date',
            'location' => 'nullable|string|max:255',
            'identified_by' => 'nullable|exists:users,id',
            'root_cause' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'preventive_action' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['ncr_number'] = Ncr::generateNcrNumber();
        $validated['status'] = 'open';
        $validated['created_by'] = auth()->id();

        Ncr::create($validated);

        return redirect()->route('admin.quality.ncrs.index')
            ->with('success', 'NCR created.');
    }

    public function show(Ncr $ncr)
    {
        $ncr->load('project', 'identifier', 'creator', 'correctiveActions');
        return view('admin.quality.ncrs.show', compact('ncr'));
    }

    public function edit(Ncr $ncr)
    {
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('admin.quality.ncrs.edit', compact('ncr', 'projects', 'users'));
    }

    public function update(Request $request, Ncr $ncr)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:structural,material,workmanship,safety,other',
            'severity' => 'required|in:minor,major,critical',
            'status' => 'required|in:open,under_investigation,corrective_action,closed',
            'identified_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:identified_date',
            'location' => 'nullable|string|max:255',
            'identified_by' => 'nullable|exists:users,id',
            'root_cause' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'preventive_action' => 'nullable|string',
            'closed_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($request->status === 'closed' && !$ncr->closed_date) {
            $validated['closed_date'] = now()->toDateString();
        }

        $ncr->update($validated);

        return redirect()->route('admin.quality.ncrs.index')
            ->with('success', 'NCR updated.');
    }

    public function destroy(Ncr $ncr)
    {
        $ncr->delete();
        return back()->with('success', 'NCR deleted.');
    }
}
