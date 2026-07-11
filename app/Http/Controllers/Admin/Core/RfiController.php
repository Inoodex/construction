<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Drawing;
use App\Models\Project;
use App\Models\Rfi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RfiController extends Controller
{
    public function index(Request $request)
    {
        $query = Rfi::with('project', 'drawing', 'raiser', 'assignee');

        $user = auth()->user();
        if ($user->hasRole('client') && $user->client_id) {
            $clientProjectIds = Project::where('client_id', $user->client_id)->pluck('id');
            $query->whereIn('project_id', $clientProjectIds);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('rfi_number', 'like', '%'.$request->search.'%')
                    ->orWhere('subject', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $rfis = $query->latest()->paginate(15);
        $projects = $user->hasRole('client')
            ? Project::where('client_id', $user->client_id)->get()
            : Project::all();

        return view('admin.core.documents.rfis.index', compact('rfis', 'projects'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $projects = Project::all();
        $users = User::all();
        $drawings = Drawing::all();

        return view('admin.core.documents.rfis.create', compact('projects', 'users', 'drawings'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'subject' => 'required|string|max:255',
            'question' => 'required|string',
            'drawing_id' => 'nullable|exists:drawings,id',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'attachment' => 'nullable|file|max:51200',
        ]);

        $validated['rfi_number'] = Rfi::generateRfiNumber();
        $validated['raised_by'] = Auth::id();
        $validated['status'] = 'open';

        unset($validated['attachment']);

        $rfi = Rfi::create($validated);

        if ($request->hasFile('attachment')) {
            $rfi->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

        return redirect()->route('admin.core.documents.rfis.index')
            ->with('success', 'RFI created successfully.');
    }

    public function show(Rfi $rfi)
    {
        $rfi->load('project', 'drawing', 'raiser', 'assignee', 'answerer', 'changeOrders');

        $user = auth()->user();
        if ($user->hasRole('client') && $user->client_id) {
            if ($rfi->project->client_id !== $user->client_id) {
                abort(403);
            }
        }

        return view('admin.core.documents.rfis.show', compact('rfi'));
    }

    public function edit(Rfi $rfi)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $projects = Project::all();
        $users = User::all();
        $drawings = Drawing::all();

        return view('admin.core.documents.rfis.edit', compact('rfi', 'projects', 'users', 'drawings'));
    }

    public function update(Request $request, Rfi $rfi)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'subject' => 'required|string|max:255',
            'question' => 'required|string',
            'drawing_id' => 'nullable|exists:drawings,id',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:open,answered,closed',
            'attachment' => 'nullable|file|max:51200',
        ]);

        unset($validated['attachment']);

        $rfi->update($validated);

        if ($request->hasFile('attachment')) {
            $rfi->clearMediaCollection('attachment');
            $rfi->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

        return redirect()->route('admin.core.documents.rfis.index')
            ->with('success', 'RFI updated successfully.');
    }

    public function destroy(Rfi $rfi)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $rfi->clearMediaCollection('attachment');
        $rfi->delete();

        return redirect()->route('admin.core.documents.rfis.index')
            ->with('success', 'RFI deleted successfully.');
    }

    public function answer(Request $request, Rfi $rfi)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'answer' => 'required|string',
        ]);

        $rfi->update([
            'answer' => $validated['answer'],
            'answered_by' => Auth::id(),
            'answered_date' => now()->toDateString(),
            'status' => 'answered',
        ]);

        return back()->with('success', 'RFI answered successfully.');
    }
}
