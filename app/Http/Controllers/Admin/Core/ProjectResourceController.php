<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectResourceController extends Controller
{
    public function globalIndex(Request $request)
    {
        $query = ProjectResource::with('project');

        if ($request->filled('resource_type')) {
            $query->where('resource_type', $request->resource_type);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $resources = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.core.project-resources.global_index', compact('resources', 'projects'));
    }

    public function index(Project $project)
    {
        $resources = $project->resources()->with('taskAllocations.task')->latest()->get();
        $totals = [
            'labor' => $resources->where('resource_type', 'labor')->sum('total_cost'),
            'equipment' => $resources->where('resource_type', 'equipment')->sum('total_cost'),
            'material' => $resources->where('resource_type', 'material')->sum('total_cost'),
        ];
        $grandTotal = array_sum($totals);
        return view('admin.core.project-resources.index', compact('project', 'resources', 'totals', 'grandTotal'));
    }

    public function create(Project $project)
    {
        return view('admin.core.project-resources.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $resourceTypes = Category::resourceTypes()->pluck('value')->toArray();
        $validated = $request->validate([
            'resource_type' => ['required', Rule::in($resourceTypes)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:100',
            'unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['total_cost'] = $validated['quantity'] * $validated['unit_cost'];

        $project->resources()->create($validated);

        return redirect()->route('admin.core.projects.resources.index', $project)
            ->with('success', 'Resource added successfully.');
    }

    public function edit(Project $project, ProjectResource $resource)
    {
        return view('admin.core.project-resources.edit', compact('project', 'resource'));
    }

    public function update(Request $request, Project $project, ProjectResource $resource)
    {
        $resourceTypes = Category::resourceTypes()->pluck('value')->toArray();
        $validated = $request->validate([
            'resource_type' => ['required', Rule::in($resourceTypes)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:100',
            'unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['total_cost'] = $validated['quantity'] * $validated['unit_cost'];

        $resource->update($validated);

        return redirect()->route('admin.core.projects.resources.index', $project)
            ->with('success', 'Resource updated successfully.');
    }

    public function destroy(Project $project, ProjectResource $resource)
    {
        $resource->delete();
        return redirect()->route('admin.core.projects.resources.index', $project)
            ->with('success', 'Resource deleted successfully.');
    }
}
