<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Phase;
use App\Models\Project;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    public function globalIndex()
    {
        $phases = Phase::with('project')->latest()->paginate(15);
        return view('admin.core.phases.global_index', compact('phases'));
    }

    public function index(Project $project)
    {
        $phases = $project->phases()->with('milestones')->paginate(15);
        return view('admin.core.phases.index', compact('project', 'phases'));
    }

    public function create(Project $project)
    {
        return view('admin.core.phases.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,completed,delayed',
            'order_index' => 'nullable|integer|min:0',
        ]);

        $project->phases()->create($validated);

        return redirect()->route('admin.core.projects.phases.index', $project)
            ->with('success', 'Phase created successfully.');
    }

    public function show(Project $project, Phase $phase)
    {
        $phase->load('milestones');
        return view('admin.core.phases.show', compact('project', 'phase'));
    }

    public function edit(Project $project, Phase $phase)
    {
        return view('admin.core.phases.edit', compact('project', 'phase'));
    }

    public function update(Request $request, Project $project, Phase $phase)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,completed,delayed',
            'order_index' => 'nullable|integer|min:0',
        ]);

        $phase->update($validated);

        return redirect()->route('admin.core.projects.phases.index', $project)
            ->with('success', 'Phase updated successfully.');
    }

    public function destroy(Project $project, Phase $phase)
    {
        $phase->delete();
        return redirect()->route('admin.core.projects.phases.index', $project)
            ->with('success', 'Phase deleted successfully.');
    }
}
