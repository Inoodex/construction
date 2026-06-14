<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $query = Site::with('project');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('location_address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $sites = $query->latest()->paginate(15);
        $projects = Project::where('status', 'active')->get();

        return view('admin.core.sites.index', compact('sites', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.core.sites.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'location_address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Site::create($validated);

        return redirect()->route('admin.core.sites.index')
            ->with('success', 'Site created successfully.');
    }

    public function show(Site $site)
    {
        $site->load('project', 'tasks', 'siteLogs', 'photos');
        return view('admin.core.sites.show', compact('site'));
    }

    public function edit(Site $site)
    {
        $projects = Project::all();
        return view('admin.core.sites.edit', compact('site', 'projects'));
    }

    public function update(Request $request, Site $site)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'location_address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $site->update($validated);

        return redirect()->route('admin.core.sites.index')
            ->with('success', 'Site updated successfully.');
    }

    public function destroy(Site $site)
    {
        $site->delete();
        return redirect()->route('admin.core.sites.index')
            ->with('success', 'Site deleted successfully.');
    }
}
