<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Drawing;
use App\Models\DrawingRevision;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrawingController extends Controller
{
    public function index(Request $request)
    {
        $query = Drawing::with('project', 'creator');

        $user = auth()->user();
        if ($user->hasRole('client') && $user->client_id) {
            $clientProjectIds = Project::where('client_id', $user->client_id)->pluck('id');
            $query->whereIn('project_id', $clientProjectIds);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('drawing_number', 'like', '%'.$request->search.'%')
                    ->orWhere('title', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('drawing_type')) {
            $query->where('drawing_type', $request->drawing_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $drawings = $query->latest()->paginate(15);
        $projects = $user->hasRole('client')
            ? Project::where('client_id', $user->client_id)->get()
            : Project::all();

        return view('admin.core.documents.drawings.index', compact('drawings', 'projects'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $projects = Project::all();

        return view('admin.core.documents.drawings.create', compact('projects'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'drawing_type' => 'required|in:architectural,structural,mep,shop,as_built,other',
            'discipline' => 'nullable|string|max:255',
            'status' => 'required|in:draft,issued,superseded,obsolete',
            'description' => 'nullable|string',
        ]);

        $validated['drawing_number'] = Drawing::generateDrawingNumber($validated['project_id']);
        $validated['created_by'] = Auth::id();

        Drawing::create($validated);

        return redirect()->route('admin.core.documents.drawings.index')
            ->with('success', 'Drawing created successfully.');
    }

    public function show(Drawing $drawing)
    {
        $drawing->load('project', 'creator', 'revisions.uploader', 'rfis');

        $user = auth()->user();
        if ($user->hasRole('client') && $user->client_id) {
            if ($drawing->project->client_id !== $user->client_id) {
                abort(403);
            }
        }

        return view('admin.core.documents.drawings.show', compact('drawing'));
    }

    public function edit(Drawing $drawing)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $projects = Project::all();

        return view('admin.core.documents.drawings.edit', compact('drawing', 'projects'));
    }

    public function update(Request $request, Drawing $drawing)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'drawing_type' => 'required|in:architectural,structural,mep,shop,as_built,other',
            'discipline' => 'nullable|string|max:255',
            'status' => 'required|in:draft,issued,superseded,obsolete',
            'description' => 'nullable|string',
        ]);

        $drawing->update($validated);

        return redirect()->route('admin.core.documents.drawings.index')
            ->with('success', 'Drawing updated successfully.');
    }

    public function destroy(Drawing $drawing)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $drawing->revisions->each(function ($rev) {
            $rev->clearMediaCollection('drawing_file');
        });
        $drawing->delete();

        return redirect()->route('admin.core.documents.drawings.index')
            ->with('success', 'Drawing deleted successfully.');
    }

    public function addRevision(Request $request, Drawing $drawing)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'revision' => 'required|string|max:50',
            'revision_date' => 'required|date',
            'description' => 'nullable|string',
            'file' => 'required|file|max:51200',
        ]);

        $revision = DrawingRevision::create([
            'drawing_id' => $drawing->id,
            'revision' => $validated['revision'],
            'revision_date' => $validated['revision_date'],
            'description' => $validated['description'] ?? null,
            'uploaded_by' => Auth::id(),
            'is_current' => true,
        ]);

        $drawing->revisions()->where('id', '!=', $revision->id)->update(['is_current' => false]);
        $drawing->update(['current_revision' => $validated['revision']]);

        $revision->addMediaFromRequest('file')->toMediaCollection('drawing_file');

        return back()->with('success', 'Revision added successfully.');
    }
}
