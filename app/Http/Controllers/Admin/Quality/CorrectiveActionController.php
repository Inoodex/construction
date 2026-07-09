<?php

namespace App\Http\Controllers\Admin\Quality;

use App\Http\Controllers\Controller;
use App\Models\CorrectiveAction;
use App\Models\Ncr;
use App\Models\PunchListItem;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class CorrectiveActionController extends Controller
{
    public function index(Request $request)
    {
        $query = CorrectiveAction::with('project', 'ncr', 'punchListItem', 'verifier');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest('target_date')->paginate(20);
        $projects = Project::orderBy('name')->get();

        return view('admin.quality.corrective-actions.index', compact('records', 'projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $ncrs = Ncr::whereIn('status', ['open', 'under_investigation', 'corrective_action'])->orderBy('ncr_number')->get();
        $punchListItems = PunchListItem::where('status', '!=', 'verified')->orderBy('id', 'desc')->get();
        $users = User::orderBy('name')->get();
        return view('admin.quality.corrective-actions.create', compact('projects', 'ncrs', 'punchListItems', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'ncr_id' => 'nullable|exists:ncrs,id',
            'punch_list_item_id' => 'nullable|exists:punch_list_items,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'root_cause' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'preventive_action' => 'nullable|string',
            'responsible_person' => 'nullable|string|max:255',
            'target_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['car_number'] = CorrectiveAction::generateCarNumber();
        $validated['status'] = 'open';
        $validated['created_by'] = auth()->id();

        CorrectiveAction::create($validated);

        return redirect()->route('admin.quality.corrective-actions.index')
            ->with('success', 'Corrective action created.');
    }

    public function show(CorrectiveAction $correctiveAction)
    {
        $correctiveAction->load('project', 'ncr', 'punchListItem', 'verifier', 'creator');
        return view('admin.quality.corrective-actions.show', compact('correctiveAction'));
    }

    public function edit(CorrectiveAction $correctiveAction)
    {
        $projects = Project::orderBy('name')->get();
        $ncrs = Ncr::whereIn('status', ['open', 'under_investigation', 'corrective_action'])->orderBy('ncr_number')->get();
        $punchListItems = PunchListItem::where('status', '!=', 'verified')->orderBy('id', 'desc')->get();
        $users = User::orderBy('name')->get();
        return view('admin.quality.corrective-actions.edit', compact('correctiveAction', 'projects', 'ncrs', 'punchListItems', 'users'));
    }

    public function update(Request $request, CorrectiveAction $correctiveAction)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'ncr_id' => 'nullable|exists:ncrs,id',
            'punch_list_item_id' => 'nullable|exists:punch_list_items,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'root_cause' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'preventive_action' => 'nullable|string',
            'responsible_person' => 'nullable|string|max:255',
            'target_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
            'status' => 'required|in:open,in_progress,completed,verified,closed',
            'verified_by' => 'nullable|exists:users,id',
            'verified_date' => 'nullable|date',
            'effectiveness_check' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $correctiveAction->update($validated);

        return redirect()->route('admin.quality.corrective-actions.index')
            ->with('success', 'Corrective action updated.');
    }

    public function destroy(CorrectiveAction $correctiveAction)
    {
        $correctiveAction->delete();
        return back()->with('success', 'Corrective action deleted.');
    }
}
