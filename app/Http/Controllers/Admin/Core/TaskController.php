<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Site;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with('project', 'site', 'assignee');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.core.tasks.index', compact('tasks', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $users = User::all();
        $sites = Site::all();
        return view('admin.core.tasks.create', compact('projects', 'users', 'sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,in_progress,review,closed',
        ]);

        Task::create($validated);

        return redirect()->route('admin.core.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load('project', 'site', 'assignee', 'dependencies', 'dependentTasks');
        return view('admin.core.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = User::all();
        $sites = Site::where('project_id', $task->project_id)->get();
        return view('admin.core.tasks.edit', compact('task', 'projects', 'users', 'sites'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,in_progress,review,closed',
            'progress_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $task->update($validated);

        return redirect()->route('admin.core.tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.core.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
