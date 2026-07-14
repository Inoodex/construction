<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Project;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with('project', 'creator');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('contract_number', 'like', '%'.$request->search.'%')
                    ->orWhere('title', 'like', '%'.$request->search.'%')
                    ->orWhere('client_name', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        $contracts = $query->latest()->paginate(20);
        $projects = Project::all();

        return view('admin.core.contracts.index', compact('contracts', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $users = User::all();

        return view('admin.core.contracts.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'contract_type' => 'required|in:main,subcontract,supply,consultancy',
            'contract_value' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'signing_date' => 'required|date',
            'commencement_date' => 'required|date',
            'completion_date' => 'nullable|date|after_or_equal:commencement_date',
            'extended_completion_date' => 'nullable|date|after_or_equal:commencement_date',
            'retention_percentage' => 'nullable|numeric|min:0|max:100',
            'liquidated_damages_rate' => 'nullable|numeric|min:0',
            'advance_payment_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:draft,active,suspended,completed,terminated',
            'notes' => 'nullable|string',
        ]);

        $validated['contract_number'] = (new Contract())->generateContractNumber();
        $validated['created_by'] = \App\Models\User::where('id', auth()->id())->exists() ? auth()->id() : null;
        $validated['currency'] = $validated['currency'] ?? 'BDT';

        Contract::create($validated);

        return redirect()->route('admin.core.contracts.index')
            ->with('success', 'Contract created successfully.');
    }

    public function show(Contract $contract)
    {
        $contract->load('project', 'creator', 'amendments', 'claims', 'closeoutItems');

        return view('admin.core.contracts.show', compact('contract'));
    }

    public function printPdf(Contract $contract)
    {
        $contract->load('project');
        $pdf = Pdf::loadView('admin.core.contracts.pdf.contract', compact('contract'))
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream('CTR-'.$contract->contract_number.'.pdf');
    }

    public function edit(Contract $contract)
    {
        $projects = Project::all();
        $users = User::all();

        return view('admin.core.contracts.edit', compact('contract', 'projects', 'users'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'contract_type' => 'required|in:main,subcontract,supply,consultancy',
            'contract_value' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'signing_date' => 'required|date',
            'commencement_date' => 'required|date',
            'completion_date' => 'nullable|date|after_or_equal:commencement_date',
            'extended_completion_date' => 'nullable|date|after_or_equal:commencement_date',
            'retention_percentage' => 'nullable|numeric|min:0|max:100',
            'liquidated_damages_rate' => 'nullable|numeric|min:0',
            'advance_payment_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:draft,active,suspended,completed,terminated',
            'notes' => 'nullable|string',
        ]);

        $contract->update($validated);

        return redirect()->route('admin.core.contracts.index')
            ->with('success', 'Contract updated successfully.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return back()->with('success', 'Contract deleted successfully.');
    }
}
