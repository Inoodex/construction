<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractClaim;
use App\Models\User;
use Illuminate\Http\Request;

class ContractClaimController extends Controller
{
    public function index(Request $request)
    {
        $query = ContractClaim::with('contract', 'submitter', 'reviewer');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('claim_number', 'like', '%'.$request->search.'%')
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

        $claims = $query->latest()->paginate(20);
        $contracts = Contract::all();

        return view('admin.core.contract-claims.index', compact('claims', 'contracts'));
    }

    public function create(Request $request)
    {
        $contracts = Contract::all();
        $users = User::all();
        $selectedContract = $request->contract_id;

        return view('admin.core.contract-claims.create', compact('contracts', 'users', 'selectedContract'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:time_extension,cost_compensation,both',
            'claimed_amount' => 'nullable|numeric|min:0',
            'claimed_days' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,submitted,under_review,granted,partially_granted,rejected',
            'notes' => 'nullable|string',
        ]);

        $validated['claim_number'] = (new ContractClaim())->generateClaimNumber();
        $validated['created_by'] = auth()->id();

        if ($validated['status'] === 'submitted') {
            $validated['submitted_date'] = now()->toDateString();
            $validated['submitted_by'] = auth()->id();
        }

        ContractClaim::create($validated);

        return redirect()->route('admin.core.contract-claims.index')
            ->with('success', 'Claim created successfully.');
    }

    public function show(ContractClaim $contractClaim)
    {
        $contractClaim->load('contract', 'submitter', 'reviewer', 'creator');

        return view('admin.core.contract-claims.show', compact('contractClaim'));
    }

    public function edit(ContractClaim $contractClaim)
    {
        $contracts = Contract::all();
        $users = User::all();

        return view('admin.core.contract-claims.edit', compact('contractClaim', 'contracts', 'users'));
    }

    public function update(Request $request, ContractClaim $contractClaim)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:time_extension,cost_compensation,both',
            'claimed_amount' => 'nullable|numeric|min:0',
            'claimed_days' => 'nullable|integer|min:0',
            'granted_amount' => 'nullable|numeric|min:0',
            'granted_days' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,submitted,under_review,granted,partially_granted,rejected',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'submitted' && $contractClaim->status !== 'submitted') {
            $validated['submitted_date'] = now()->toDateString();
            $validated['submitted_by'] = auth()->id();
        }

        if (in_array($validated['status'], ['granted', 'partially_granted', 'rejected']) && !$contractClaim->response_date) {
            $validated['response_date'] = now()->toDateString();
            $validated['reviewed_by'] = auth()->id();
        }

        $contractClaim->update($validated);

        return redirect()->route('admin.core.contract-claims.index')
            ->with('success', 'Claim updated successfully.');
    }

    public function destroy(ContractClaim $contractClaim)
    {
        $contractClaim->delete();

        return back()->with('success', 'Claim deleted successfully.');
    }
}
