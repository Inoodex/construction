<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SubcontractAgreement;
use App\Models\Subcontractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubcontractAgreementController extends Controller
{
    public function index(Request $request)
    {
        $query = SubcontractAgreement::with('project', 'subcontractor', 'creator');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subcontractor_id')) {
            $query->where('subcontractor_id', $request->subcontractor_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $agreements = $query->latest()->paginate(15);
        $projects = Project::all();
        $subcontractors = Subcontractor::all();

        return view('admin.procurement.subcontract-agreements.index', compact('agreements', 'projects', 'subcontractors'));
    }

    public function create()
    {
        $projects = Project::all();
        $subcontractors = Subcontractor::all();
        return view('admin.procurement.subcontract-agreements.create', compact('projects', 'subcontractors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'subcontractor_id' => 'required|exists:subcontractors,id',
            'title' => 'required|string|max:255',
            'scope_of_work' => 'nullable|string',
            'agreement_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'contract_value' => 'required|numeric|min:0',
            'retention_percentage' => 'required|numeric|min:0|max:100',
            'payment_terms' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'insurance_requirements' => 'nullable|string',
        ]);

        $number = 'SCA-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        SubcontractAgreement::create(array_merge($validated, [
            'agreement_number' => $number,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]));

        return redirect()->route('admin.procurement.subcontract-agreements.index')
            ->with('success', "Agreement $number created.");
    }

    public function show(SubcontractAgreement $subcontractAgreement)
    {
        $subcontractAgreement->load('project', 'subcontractor', 'creator');
        return view('admin.procurement.subcontract-agreements.show', compact('subcontractAgreement'));
    }

    public function edit(SubcontractAgreement $subcontractAgreement)
    {
        if (!in_array($subcontractAgreement->status, ['draft', 'active'])) {
            return redirect()->route('admin.procurement.subcontract-agreements.show', $subcontractAgreement)
                ->with('error', 'Only draft or active agreements can be edited.');
        }

        $projects = Project::all();
        $subcontractors = Subcontractor::all();
        return view('admin.procurement.subcontract-agreements.edit', compact('subcontractAgreement', 'projects', 'subcontractors'));
    }

    public function update(Request $request, SubcontractAgreement $subcontractAgreement)
    {
        if (!in_array($subcontractAgreement->status, ['draft', 'active'])) {
            return redirect()->route('admin.procurement.subcontract-agreements.show', $subcontractAgreement)
                ->with('error', 'Only draft or active agreements can be updated.');
        }

        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'subcontractor_id' => 'required|exists:subcontractors,id',
            'title' => 'required|string|max:255',
            'scope_of_work' => 'nullable|string',
            'agreement_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'contract_value' => 'required|numeric|min:0',
            'retention_percentage' => 'required|numeric|min:0|max:100',
            'payment_terms' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'insurance_requirements' => 'nullable|string',
            'status' => 'required|in:draft,active',
        ]);

        $subcontractAgreement->update($validated);

        return redirect()->route('admin.procurement.subcontract-agreements.show', $subcontractAgreement)
            ->with('success', 'Agreement updated.');
    }

    public function destroy(SubcontractAgreement $subcontractAgreement)
    {
        if (!in_array($subcontractAgreement->status, ['draft', 'cancelled', 'terminated'])) {
            return back()->with('error', 'Only draft, cancelled, or terminated agreements can be deleted.');
        }

        $subcontractAgreement->delete();
        return redirect()->route('admin.procurement.subcontract-agreements.index')
            ->with('success', 'Agreement deleted.');
    }

    public function activate(SubcontractAgreement $subcontractAgreement)
    {
        if ($subcontractAgreement->status !== 'draft') {
            return back()->with('error', 'Only draft agreements can be activated.');
        }

        $subcontractAgreement->update(['status' => 'active']);
        return back()->with('success', 'Agreement activated.');
    }

    public function complete(SubcontractAgreement $subcontractAgreement)
    {
        if ($subcontractAgreement->status !== 'active') {
            return back()->with('error', 'Only active agreements can be completed.');
        }

        $subcontractAgreement->update(['status' => 'completed']);
        return back()->with('success', 'Agreement completed.');
    }

    public function terminate(Request $request, SubcontractAgreement $subcontractAgreement)
    {
        if (!in_array($subcontractAgreement->status, ['draft', 'active'])) {
            return back()->with('error', 'Only draft or active agreements can be terminated.');
        }

        $subcontractAgreement->update(['status' => 'terminated']);
        return back()->with('success', 'Agreement terminated.');
    }
}
