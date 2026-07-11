<?php

namespace App\Http\Controllers\Admin\Quality;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Risk;
use App\Models\User;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index(Request $request)
    {
        $query = Risk::with('project', 'owner');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('risk_number', 'like', '%'.$request->search.'%')
                    ->orWhere('title', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('probability')) {
            $query->where('probability', $request->probability);
        }

        if ($request->filled('impact')) {
            $query->where('impact', $request->impact);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $risks = $query->latest('identified_date')->paginate(20);
        $projects = Project::all();

        return view('admin.quality.risks.index', compact('risks', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $users = User::all();

        return view('admin.quality.risks.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:technical,safety,financial,environmental,schedule,other',
            'probability' => 'required|in:very_low,low,medium,high,very_high',
            'impact' => 'required|in:very_low,low,medium,high,very_high',
            'status' => 'required|in:open,in_progress,mitigated,closed',
            'risk_owner_id' => 'nullable|exists:users,id',
            'identified_date' => 'required|date',
            'review_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'mitigation_plan' => 'nullable|string',
            'contingency_plan' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['risk_number'] = Risk::generateRiskNumber();
        $validated['risk_score'] = Risk::calculateScore($validated['probability'], $validated['impact']);
        $validated['created_by'] = auth()->id();

        if ($validated['status'] === 'closed') {
            $validated['closed_date'] = now()->toDateString();
        }

        Risk::create($validated);

        return redirect()->route('admin.quality.risks.index')
            ->with('success', 'Risk registered successfully.');
    }

    public function show(Risk $risk)
    {
        $risk->load('project', 'owner', 'creator');

        return view('admin.quality.risks.show', compact('risk'));
    }

    public function edit(Risk $risk)
    {
        $projects = Project::all();
        $users = User::all();

        return view('admin.quality.risks.edit', compact('risk', 'projects', 'users'));
    }

    public function update(Request $request, Risk $risk)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:technical,safety,financial,environmental,schedule,other',
            'probability' => 'required|in:very_low,low,medium,high,very_high',
            'impact' => 'required|in:very_low,low,medium,high,very_high',
            'status' => 'required|in:open,in_progress,mitigated,closed',
            'risk_owner_id' => 'nullable|exists:users,id',
            'identified_date' => 'required|date',
            'review_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'mitigation_plan' => 'nullable|string',
            'contingency_plan' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['risk_score'] = Risk::calculateScore($validated['probability'], $validated['impact']);

        if ($validated['status'] === 'closed' && !$risk->closed_date) {
            $validated['closed_date'] = now()->toDateString();
        }

        $risk->update($validated);

        return redirect()->route('admin.quality.risks.index')
            ->with('success', 'Risk updated successfully.');
    }

    public function destroy(Risk $risk)
    {
        $risk->delete();

        return back()->with('success', 'Risk deleted successfully.');
    }
}
