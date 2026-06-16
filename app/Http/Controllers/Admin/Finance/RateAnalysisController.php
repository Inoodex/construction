<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\RateAnalysis;
use App\Models\RateAnalysisItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RateAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $query = RateAnalysis::with('project', 'creator');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('resource_type')) {
            $query->whereHas('items', fn($q) => $q->where('resource_type', $request->resource_type));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rateAnalyses = $query->latest()->paginate(15);
        $projects = Project::all();
        return view('admin.finance.rate-analysis.index', compact('rateAnalyses', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.finance.rate-analysis.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,approved,revised',
        ]);

        $validated['ra_number'] = 'RA-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        $validated['total_rate'] = 0;
        $validated['created_by'] = Auth::id();

        $rateAnalysis = RateAnalysis::create($validated);

        return redirect()->route('admin.finance.rate-analysis.show', $rateAnalysis->id)
            ->with('success', 'Rate Analysis created. Add items now.');
    }

    public function show(RateAnalysis $rateAnalysis)
    {
        $rateAnalysis->load('project', 'creator', 'items');
        return view('admin.finance.rate-analysis.show', compact('rateAnalysis'));
    }

    public function edit(RateAnalysis $rateAnalysis)
    {
        $projects = Project::all();
        return view('admin.finance.rate-analysis.edit', compact('rateAnalysis', 'projects'));
    }

    public function update(Request $request, RateAnalysis $rateAnalysis)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,approved,revised',
        ]);

        $rateAnalysis->update($validated);

        return redirect()->route('admin.finance.rate-analysis.index')
            ->with('success', 'Rate Analysis updated successfully.');
    }

    public function destroy(RateAnalysis $rateAnalysis)
    {
        $rateAnalysis->items()->delete();
        $rateAnalysis->delete();
        return redirect()->route('admin.finance.rate-analysis.index')
            ->with('success', 'Rate Analysis deleted successfully.');
    }

    public function addItem(Request $request, RateAnalysis $rateAnalysis)
    {
        $validated = $request->validate([
            'resource_type' => 'required|in:labour,material,equipment,subcontract,overhead',
            'resource_description' => 'required|string',
            'unit' => 'required|string|max:20',
            'quantity' => 'required|numeric|min:0.0001',
            'unit_rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['total_cost'] = $validated['quantity'] * $validated['unit_rate'];
        $validated['rate_analysis_id'] = $rateAnalysis->id;

        RateAnalysisItem::create($validated);

        $rateAnalysis->update(['total_rate' => $rateAnalysis->items()->sum('total_cost')]);

        return back()->with('success', 'Item added to Rate Analysis.');
    }

    public function removeItem(RateAnalysis $rateAnalysis, RateAnalysisItem $rateAnalysisItem)
    {
        $rateAnalysisItem->delete();
        $rateAnalysis->update(['total_rate' => $rateAnalysis->items()->sum('total_cost')]);

        return back()->with('success', 'Item removed from Rate Analysis.');
    }
}
