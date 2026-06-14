<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Phase;
use App\Models\Task;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('creator');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->latest()->paginate(15);

        return view('admin.core.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.core.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,on_hold,completed',
        ]);

        $validated['created_by'] = Auth::id();

        Project::create($validated);

        return redirect()->route('admin.core.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load('creator', 'sites', 'tasks', 'phases', 'milestones', 'resources');
        return view('admin.core.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('admin.core.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,on_hold,completed',
        ]);

        $project->update($validated);

        return redirect()->route('admin.core.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function gantt(Project $project)
    {
        $project->load(['phases', 'tasks', 'milestones']);

        $tasks = $project->tasks()->whereNotNull('start_date')->whereNotNull('end_date')->get();
        $phases = $project->phases()->whereNotNull('start_date')->whereNotNull('end_date')->get();
        $milestones = $project->milestones()->whereNotNull('target_date')->get();

        $allDates = collect();
        foreach ($tasks as $t) { $allDates->push($t->start_date, $t->end_date); }
        foreach ($phases as $p) { $allDates->push($p->start_date, $p->end_date); }
        foreach ($milestones as $m) { $allDates->push($m->target_date); }

        $chartStart = $allDates->min() ?? $project->start_date;
        $chartEnd = $allDates->max() ?? $project->end_date;
        $totalDays = $chartStart->diffInDays($chartEnd) ?: 1;

        $weeks = [];
        $current = $chartStart->copy()->startOfWeek();
        while ($current->lte($chartEnd)) {
            $weeks[] = $current->copy();
            $current->addWeek();
        }

        $barColor = fn($type) => match($type) {
            'phase' => 'bg-primary',
            'task' => 'bg-info',
            'milestone' => 'bg-warning',
            default => 'bg-secondary',
        };

        return view('admin.core.projects.gantt', compact(
            'project', 'tasks', 'phases', 'milestones',
            'chartStart', 'chartEnd', 'totalDays', 'weeks', 'barColor'
        ));
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.core.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
