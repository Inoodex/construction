<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\Phase;
use App\Models\Project;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function globalIndex()
    {
        $milestones = Milestone::with('project', 'phase')->latest()->paginate(15);
        return view('admin.core.milestones.global_index', compact('milestones'));
    }

    public function index(Project $project)
    {
        $milestones = $project->milestones()->with('phase')->paginate(15);
        return view('admin.core.milestones.index', compact('project', 'milestones'));
    }

    public function create(Project $project)
    {
        $phases = $project->phases;
        return view('admin.core.milestones.create', compact('project', 'phases'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'phase_id' => 'nullable|exists:phases,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_date' => 'nullable|date',
            'achieved_date' => 'nullable|date',
            'status' => 'required|in:pending,achieved,missed',
        ]);

        $project->milestones()->create($validated);

        return redirect()->route('admin.core.projects.milestones.index', $project)
            ->with('success', 'Milestone created successfully.');
    }

    public function show(Project $project, Milestone $milestone)
    {
        $milestone->load('phase');
        return view('admin.core.milestones.show', compact('project', 'milestone'));
    }

    public function edit(Project $project, Milestone $milestone)
    {
        $phases = $project->phases;
        return view('admin.core.milestones.edit', compact('project', 'milestone', 'phases'));
    }

    public function update(Request $request, Project $project, Milestone $milestone)
    {
        $validated = $request->validate([
            'phase_id' => 'nullable|exists:phases,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_date' => 'nullable|date',
            'achieved_date' => 'nullable|date',
            'status' => 'required|in:pending,achieved,missed',
        ]);

        $milestone->update($validated);

        return redirect()->route('admin.core.projects.milestones.index', $project)
            ->with('success', 'Milestone updated successfully.');
    }

    public function destroy(Project $project, Milestone $milestone)
    {
        $milestone->delete();
        return redirect()->route('admin.core.projects.milestones.index', $project)
            ->with('success', 'Milestone deleted successfully.');
    }
}
