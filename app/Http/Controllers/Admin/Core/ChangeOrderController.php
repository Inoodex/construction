<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\ChangeOrder;
use App\Models\Project;
use App\Models\Rfi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ChangeOrder::with('project', 'rfi', 'requester', 'approver');

        $user = auth()->user();
        if ($user->hasRole('client') && $user->client_id) {
            $clientProjectIds = Project::where('client_id', $user->client_id)->pluck('id');
            $query->whereIn('project_id', $clientProjectIds);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('change_order_number', 'like', '%'.$request->search.'%')
                    ->orWhere('title', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $changeOrders = $query->latest()->paginate(15);
        $projects = $user->hasRole('client')
            ? Project::where('client_id', $user->client_id)->get()
            : Project::all();

        return view('admin.core.documents.change-orders.index', compact('changeOrders', 'projects'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $projects = Project::all();
        $rfis = Rfi::all();

        return view('admin.core.documents.change-orders.create', compact('projects', 'rfis'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:variation,change_order,extension',
            'cost_impact' => 'nullable|numeric|min:0',
            'time_impact_days' => 'nullable|integer|min:0',
            'rfi_id' => 'nullable|exists:rfis,id',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|max:51200',
        ]);

        $validated['change_order_number'] = ChangeOrder::generateChangeOrderNumber();
        $validated['requested_by'] = Auth::id();
        $validated['status'] = 'draft';

        unset($validated['attachment']);

        $changeOrder = ChangeOrder::create($validated);

        if ($request->hasFile('attachment')) {
            $changeOrder->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

        return redirect()->route('admin.core.documents.change-orders.index')
            ->with('success', 'Change order created successfully.');
    }

    public function show(ChangeOrder $changeOrder)
    {
        $changeOrder->load('project', 'rfi', 'requester', 'approver');

        $user = auth()->user();
        if ($user->hasRole('client') && $user->client_id) {
            if ($changeOrder->project->client_id !== $user->client_id) {
                abort(403);
            }
        }

        return view('admin.core.documents.change-orders.show', compact('changeOrder'));
    }

    public function printPdf(ChangeOrder $changeOrder)
    {
        $changeOrder->load('project', 'rfi', 'requester', 'approver');
        $pdf = Pdf::loadView('admin.core.documents.change-orders.pdf.change-order', compact('changeOrder'))
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream('CO-'.$changeOrder->change_order_number.'.pdf');
    }

    public function edit(ChangeOrder $changeOrder)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $projects = Project::all();
        $rfis = Rfi::all();

        return view('admin.core.documents.change-orders.edit', compact('changeOrder', 'projects', 'rfis'));
    }

    public function update(Request $request, ChangeOrder $changeOrder)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:variation,change_order,extension',
            'cost_impact' => 'nullable|numeric|min:0',
            'time_impact_days' => 'nullable|integer|min:0',
            'rfi_id' => 'nullable|exists:rfis,id',
            'status' => 'required|in:draft,submitted,under_review,approved,rejected,implemented',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|max:51200',
        ]);

        unset($validated['attachment']);

        $changeOrder->update($validated);

        if ($request->hasFile('attachment')) {
            $changeOrder->clearMediaCollection('attachment');
            $changeOrder->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

        return redirect()->route('admin.core.documents.change-orders.index')
            ->with('success', 'Change order updated successfully.');
    }

    public function destroy(ChangeOrder $changeOrder)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $changeOrder->clearMediaCollection('attachment');
        $changeOrder->delete();

        return redirect()->route('admin.core.documents.change-orders.index')
            ->with('success', 'Change order deleted successfully.');
    }

    public function approve(ChangeOrder $changeOrder)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $changeOrder->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Change order approved.');
    }

    public function reject(ChangeOrder $changeOrder)
    {
        if (auth()->user()->hasRole('client')) {
            abort(403);
        }

        $changeOrder->update([
            'status' => 'rejected',
            'approved_by' => null,
            'approved_date' => null,
        ]);

        return back()->with('success', 'Change order rejected.');
    }
}
