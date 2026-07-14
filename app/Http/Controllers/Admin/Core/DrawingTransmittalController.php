<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Drawing;
use App\Models\DrawingRevision;
use App\Models\DrawingTransmittal;
use App\Models\DrawingTransmittalItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class DrawingTransmittalController extends Controller
{
    public function index(Request $request)
    {
        $query = DrawingTransmittal::with('project', 'fromUser', 'items.drawing');

        $user = auth()->user();
        if ($user->hasRole('client') && $user->client_id) {
            $clientProjectIds = Project::where('client_id', $user->client_id)->pluck('id');
            $query->whereIn('project_id', $clientProjectIds);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('transmittal_number', 'like', '%'.$request->search.'%')
                    ->orWhere('to_party', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transmittals = $query->latest()->paginate(15);
        $projects = $user->hasRole('client')
            ? Project::where('client_id', $user->client_id)->get()
            : Project::all();

        return view('admin.core.documents.transmittals.index', compact('transmittals', 'projects'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $projects = Project::all();
        $drawings = Drawing::all();

        return view('admin.core.documents.transmittals.create', compact('projects', 'drawings'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'to_party' => 'required|string|max:255',
            'sent_date' => 'required|date',
            'purpose' => 'required|in:for_approval,for_information,for_construction,as_built',
            'status' => 'required|in:draft,sent,acknowledged',
            'notes' => 'nullable|string',
            'drawings' => 'required|array|min:1',
            'drawings.*.drawing_id' => 'required|exists:drawings,id',
            'drawings.*.revision_id' => 'nullable|exists:drawing_revisions,id',
            'drawings.*.copies' => 'nullable|integer|min:1',
        ]);

        $validated['transmittal_number'] = DrawingTransmittal::generateTransmittalNumber();
        $validated['from_user_id'] = Auth::id();

        unset($validated['drawings']);

        $transmittal = DrawingTransmittal::create($validated);

        foreach ($request->drawings as $item) {
            DrawingTransmittalItem::create([
                'drawing_transmittal_id' => $transmittal->id,
                'drawing_id' => $item['drawing_id'],
                'drawing_revision_id' => $item['revision_id'] ?? null,
                'copies' => $item['copies'] ?? 1,
            ]);
        }

        return redirect()->route('admin.core.documents.transmittals.index')
            ->with('success', 'Transmittal created successfully.');
    }

    public function show(DrawingTransmittal $transmittal)
    {
        $transmittal->load('project', 'fromUser', 'items.drawing', 'items.revision');

        $user = auth()->user();
        if ($user->hasRole('client') && $user->client_id) {
            if ($transmittal->project->client_id !== $user->client_id) {
                abort(403);
            }
        }

        return view('admin.core.documents.transmittals.show', compact('transmittal'));
    }

    public function edit(DrawingTransmittal $transmittal)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $projects = Project::all();
        $drawings = Drawing::all();
        $transmittal->load('items');

        return view('admin.core.documents.transmittals.edit', compact('transmittal', 'projects', 'drawings'));
    }

    public function update(Request $request, DrawingTransmittal $transmittal)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'to_party' => 'required|string|max:255',
            'sent_date' => 'required|date',
            'purpose' => 'required|in:for_approval,for_information,for_construction,as_built',
            'status' => 'required|in:draft,sent,acknowledged',
            'notes' => 'nullable|string',
            'drawings' => 'required|array|min:1',
            'drawings.*.drawing_id' => 'required|exists:drawings,id',
            'drawings.*.revision_id' => 'nullable|exists:drawing_revisions,id',
            'drawings.*.copies' => 'nullable|integer|min:1',
        ]);

        unset($validated['drawings']);

        $transmittal->update($validated);

        $transmittal->items()->delete();
        foreach ($request->drawings as $item) {
            DrawingTransmittalItem::create([
                'drawing_transmittal_id' => $transmittal->id,
                'drawing_id' => $item['drawing_id'],
                'drawing_revision_id' => $item['revision_id'] ?? null,
                'copies' => $item['copies'] ?? 1,
            ]);
        }

        return redirect()->route('admin.core.documents.transmittals.index')
            ->with('success', 'Transmittal updated successfully.');
    }

    public function destroy(DrawingTransmittal $transmittal)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $transmittal->items()->delete();
        $transmittal->delete();

        return redirect()->route('admin.core.documents.transmittals.index')
            ->with('success', 'Transmittal deleted successfully.');
    }

    public function printPdf(DrawingTransmittal $transmittal)
    {
        $transmittal->load('project', 'fromUser', 'items.drawing', 'items.revision');
        $pdf = Pdf::loadView('admin.core.documents.transmittals.pdf.transmittal', compact('transmittal'));
        return $pdf->stream();
    }
}
