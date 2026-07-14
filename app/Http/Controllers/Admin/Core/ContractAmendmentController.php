<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractAmendment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ContractAmendmentController extends Controller
{
    public function index(Request $request)
    {
        $query = ContractAmendment::with('contract', 'requester', 'approver');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('amendment_number', 'like', '%'.$request->search.'%')
                    ->orWhere('title', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('contract_id')) {
            $query->where('contract_id', $request->contract_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $amendments = $query->latest()->paginate(20);
        $contracts = Contract::all();

        return view('admin.core.contract-amendments.index', compact('amendments', 'contracts'));
    }

    public function create(Request $request)
    {
        $contracts = Contract::all();
        $users = User::all();
        $selectedContract = $request->contract_id;

        return view('admin.core.contract-amendments.create', compact('contracts', 'users', 'selectedContract'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:scope_change,time_extension,value_change,other',
            'cost_impact' => 'nullable|numeric',
            'time_impact_days' => 'nullable|integer',
            'status' => 'required|in:draft,submitted,approved,rejected',
            'requested_by' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $validated['amendment_number'] = (new ContractAmendment())->generateAmendmentNumber();
        $validated['created_by'] = auth()->id();

        if ($validated['status'] === 'approved') {
            $validated['approved_date'] = now()->toDateString();
            $validated['approved_by'] = auth()->id();
        }

        ContractAmendment::create($validated);

        return redirect()->route('admin.core.contract-amendments.index')
            ->with('success', 'Amendment created successfully.');
    }

    public function show(ContractAmendment $contractAmendment)
    {
        $contractAmendment->load('contract', 'requester', 'approver', 'creator');

        return view('admin.core.contract-amendments.show', compact('contractAmendment'));
    }

    public function edit(ContractAmendment $contractAmendment)
    {
        $contracts = Contract::all();
        $users = User::all();

        return view('admin.core.contract-amendments.edit', compact('contractAmendment', 'contracts', 'users'));
    }

    public function update(Request $request, ContractAmendment $contractAmendment)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:scope_change,time_extension,value_change,other',
            'cost_impact' => 'nullable|numeric',
            'time_impact_days' => 'nullable|integer',
            'status' => 'required|in:draft,submitted,approved,rejected',
            'requested_by' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'approved' && $contractAmendment->status !== 'approved') {
            $validated['approved_date'] = now()->toDateString();
            $validated['approved_by'] = auth()->id();
        }

        $contractAmendment->update($validated);

        return redirect()->route('admin.core.contract-amendments.index')
            ->with('success', 'Amendment updated successfully.');
    }

    public function destroy(ContractAmendment $contractAmendment)
    {
        $contractAmendment->delete();

        return back()->with('success', 'Amendment deleted successfully.');
    }

    public function printPdf(ContractAmendment $contractAmendment)
    {
        $contractAmendment->load('contract', 'requester', 'approver', 'creator');
        $pdf = Pdf::loadView('admin.core.contract-amendments.pdf.amendment', compact('contractAmendment'));
        return $pdf->stream();
    }
}
