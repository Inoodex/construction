<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Services\LedgerPostingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct(private LedgerPostingService $ledger) {}

    private function isClientPortalUser($user = null): bool
    {
        $user = $user ?? auth()->user();

        return $user && $user->hasRole('client');
    }

    public function index(Request $request)
    {
        $query = Project::with('creator', 'client');

        $user = auth()->user();
        if ($this->isClientPortalUser($user)) {
            $query->where('client_id', $user->client_id);
        }

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
        if ($this->isClientPortalUser()) {
            abort(403);
        }

        $clients = Client::where('status', 'active')->orderBy('company_name')->get();

        return view('admin.core.projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        if ($this->isClientPortalUser()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,on_hold,completed',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $validated['created_by'] = Auth::id();

        Project::create($validated);

        return redirect()->route('admin.core.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $user = auth()->user();
        if ($this->isClientPortalUser($user) && $project->client_id !== $user->client_id) {
            abort(403);
        }

        $project->load('creator', 'sites', 'tasks', 'phases', 'milestones', 'resources');

        return view('admin.core.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        if ($this->isClientPortalUser()) {
            abort(403);
        }

        $clients = Client::where('status', 'active')->orderBy('company_name')->get();

        return view('admin.core.projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project)
    {
        if ($this->isClientPortalUser()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,on_hold,completed',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $wasCompleted = $project->status === 'completed';
        $project->update($validated);

        // On completion, transfer accumulated project WIP into recognised cost.
        if (!$wasCompleted && $project->status === 'completed') {
            $this->ledger->transferProjectWipToCost($project);
        }

        return redirect()->route('admin.core.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if ($this->isClientPortalUser()) {
            abort(403);
        }

        $project->delete();

        return redirect()->route('admin.core.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function gantt(Project $project)
    {
        $user = auth()->user();
        if ($this->isClientPortalUser($user) && $project->client_id !== $user->client_id) {
            abort(403);
        }
        $project->load(['phases', 'tasks', 'milestones']);

        $tasks = $project->tasks()->whereNotNull('start_date')->whereNotNull('end_date')->get();
        $phases = $project->phases()->whereNotNull('start_date')->whereNotNull('end_date')->get();
        $milestones = $project->milestones()->whereNotNull('target_date')->get();

        $allDates = collect();
        foreach ($tasks as $t) {
            $allDates->push($t->start_date, $t->end_date);
        }
        foreach ($phases as $p) {
            $allDates->push($p->start_date, $p->end_date);
        }
        foreach ($milestones as $m) {
            $allDates->push($m->target_date);
        }

        $chartStart = $allDates->min() ?? $project->start_date;
        $chartEnd = $allDates->max() ?? $project->end_date;
        $totalDays = $chartStart->diffInDays($chartEnd) ?: 1;

        $weeks = [];
        $current = $chartStart->copy()->startOfWeek();
        while ($current->lte($chartEnd)) {
            $weeks[] = $current->copy();
            $current->addWeek();
        }

        $barColor = fn($type) => match ($type) {
            'phase' => 'bg-primary',
            'task' => 'bg-info',
            'milestone' => 'bg-warning',
            default => 'bg-secondary',
        };

        return view('admin.core.projects.gantt', compact(
            'project',
            'tasks',
            'phases',
            'milestones',
            'chartStart',
            'chartEnd',
            'totalDays',
            'weeks',
            'barColor'
        ));
    }

    public function resourceGanttIndex()
    {
        $query = Project::withCount(['resources' => function ($q) {
            $q->whereHas('taskAllocations');
        }]);

        $user = auth()->user();
        if ($user->hasRole('client') && $user->client_id) {
            $query->where('client_id', $user->client_id);
        }

        $projects = $query->orderBy('name')->get();

        return view('admin.core.projects.resource-gantt-index', compact('projects'));
    }

    public function resourceGantt(Project $project)
    {
        $user = auth()->user();
        if ($this->isClientPortalUser($user) && $project->client_id !== $user->client_id) {
            abort(403);
        }
        $project->load('resources.taskAllocations.task', 'tasks');

        $resources = $project->resources;

        $allDates = collect();
        foreach ($resources as $r) {
            foreach ($r->taskAllocations as $ta) {
                if ($ta->start_date) {
                    $allDates->push($ta->start_date);
                }
                if ($ta->end_date) {
                    $allDates->push($ta->end_date);
                }
            }
        }

        $chartStart = $allDates->min() ?? now()->startOfMonth();
        $chartEnd = $allDates->max() ?? now()->endOfMonth();
        $totalDays = $chartStart->diffInDays($chartEnd) ?: 1;

        $weeks = [];
        $current = $chartStart->copy()->startOfWeek();
        while ($current->lte($chartEnd)) {
            $weeks[] = $current->copy();
            $current->addWeek();
        }

        return view('admin.core.projects.resource-gantt', compact(
            'project',
            'resources',
            'chartStart',
            'chartEnd',
            'totalDays',
            'weeks'
        ));
    }
}
