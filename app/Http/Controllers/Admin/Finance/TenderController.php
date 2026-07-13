<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use App\Models\TenderBid;
use App\Models\Project;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TenderController extends Controller
{
    public function index(Request $request)
    {
        $query = Tender::with('project', 'creator');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tenders = $query->latest()->paginate(15);
        $projects = Project::all();
        return view('admin.finance.tenders.index', compact('tenders', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.finance.tenders.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'issue_date' => 'required|date',
            'close_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,open,closed,awarded,cancelled',
        ]);

        $validated['tender_number'] = 'TDR-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        $validated['created_by'] = Auth::id();

        Tender::create($validated);

        return redirect()->route('admin.finance.tenders.index')
            ->with('success', 'Tender created successfully.');
    }

    public function show(Tender $tender)
    {
        $tender->load(['project', 'creator', 'bids.vendor', 'packages']);
        $vendors = Vendor::where('status', 'approved')->get();
        return view('admin.finance.tenders.show', compact('tender', 'vendors'));
    }

    public function edit(Tender $tender)
    {
        $projects = Project::all();
        return view('admin.finance.tenders.edit', compact('tender', 'projects'));
    }

    public function update(Request $request, Tender $tender)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'issue_date' => 'required|date',
            'close_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,open,closed,awarded,cancelled',
        ]);

        $tender->update($validated);

        return redirect()->route('admin.finance.tenders.index')
            ->with('success', 'Tender updated successfully.');
    }

    public function destroy(Tender $tender)
    {
        $tender->bids()->delete();
        $tender->delete();
        return redirect()->route('admin.finance.tenders.index')
            ->with('success', 'Tender deleted successfully.');
    }

    public function addBid(Request $request, Tender $tender)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'bid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'technical_score' => 'nullable|integer|min:0|max:100',
            'financial_score' => 'nullable|integer|min:0|max:100',
            'submitted_at' => 'required|date',
        ]);

        $validated['tender_id'] = $tender->id;
        $validated['total_score'] = null;
        if ($validated['technical_score'] !== null && $validated['financial_score'] !== null) {
            $validated['total_score'] = $validated['technical_score'] + $validated['financial_score'];
        }

        TenderBid::create($validated);

        return back()->with('success', 'Bid added to tender.');
    }

    public function updateBid(Request $request, Tender $tender, TenderBid $tenderBid)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'bid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'technical_score' => 'nullable|integer|min:0|max:100',
            'financial_score' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:submitted,evaluated,shortlisted,awarded,rejected',
            'submitted_at' => 'required|date',
        ]);

        $validated['total_score'] = null;
        if ($validated['technical_score'] !== null && $validated['financial_score'] !== null) {
            $validated['total_score'] = $validated['technical_score'] + $validated['financial_score'];
        }

        $tenderBid->update($validated);

        return back()->with('success', 'Bid updated.');
    }

    public function removeBid(Tender $tender, TenderBid $tenderBid)
    {
        $tenderBid->delete();
        return back()->with('success', 'Bid removed from tender.');
    }

    public function evaluationMatrix(Tender $tender)
    {
        $tender->load(['bids.vendor', 'project', 'creator']);

        return view('admin.finance.tenders.evaluation-matrix', compact('tender'));
    }

    public function awardLetter(Tender $tender)
    {
        $tender->load(['bids.vendor', 'project', 'creator']);
        $awardedBid = $tender->bids()->where('status', 'awarded')->first();

        return view('admin.finance.tenders.award-letter', compact('tender', 'awardedBid'));
    }
}
