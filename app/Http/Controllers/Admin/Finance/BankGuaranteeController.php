<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\BankGuarantee;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankGuaranteeController extends Controller
{
    public function index(Request $request)
    {
        $query = BankGuarantee::with('project');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_number', 'like', "%{$s}%")
                    ->orWhere('issuing_bank', 'like', "%{$s}%")
                    ->orWhere('beneficiary', 'like', "%{$s}%");
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $guarantees = $query->latest()->paginate(15);

        return view('admin.finance.bank-guarantees.index', compact('guarantees'));
    }

    public function create()
    {
        $projects = Project::pluck('name', 'id');
        return view('admin.finance.bank-guarantees.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string|max:100|unique:bank_guarantees',
            'type' => 'required|in:bid,performance,advance,retention',
            'issuing_bank' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'beneficiary' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:issue_date',
            'narration' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('document')) {
            $validated['document_path'] = $request->file('document')->store('bank-guarantees', 'public');
        }

        if (now()->lt($validated['issue_date'])) {
            $validated['status'] = 'active';
        } elseif (now()->between($validated['issue_date'], $validated['expiry_date'])) {
            $validated['status'] = 'active';
        } else {
            $validated['status'] = 'expired';
        }
        $validated['created_by'] = auth()->id();

        BankGuarantee::create($validated);

        return redirect()->route('admin.finance.bank-guarantees.index')
            ->with('success', 'Bank guarantee registered successfully.');
    }

    public function show(BankGuarantee $bankGuarantee)
    {
        $bankGuarantee->load('project');
        return view('admin.finance.bank-guarantees.show', compact('bankGuarantee'));
    }

    public function edit(BankGuarantee $bankGuarantee)
    {
        $projects = Project::pluck('name', 'id');
        return view('admin.finance.bank-guarantees.edit', compact('bankGuarantee', 'projects'));
    }

    public function update(Request $request, BankGuarantee $bankGuarantee)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string|max:100|unique:bank_guarantees,reference_number,' . $bankGuarantee->id,
            'type' => 'required|in:bid,performance,advance,retention',
            'issuing_bank' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'beneficiary' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:issue_date',
            'narration' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('document')) {
            if ($bankGuarantee->document_path && Storage::disk('public')->exists($bankGuarantee->document_path)) {
                Storage::disk('public')->delete($bankGuarantee->document_path);
            }
            $validated['document_path'] = $request->file('document')->store('bank-guarantees', 'public');
        }

        $bankGuarantee->update($validated);

        return redirect()->route('admin.finance.bank-guarantees.index')
            ->with('success', 'Bank guarantee updated successfully.');
    }

    public function updateStatus(Request $request, BankGuarantee $bankGuarantee)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,expired,encashed,returned',
            'return_date' => 'nullable|date|required_if:status,returned',
            'narration' => 'nullable|string',
        ]);

        if ($validated['status'] === 'returned') {
            $bankGuarantee->return_date = $validated['return_date'] ?? now();
        }
        $bankGuarantee->status = $validated['status'];
        if ($request->filled('narration')) {
            $bankGuarantee->narration = $validated['narration'];
        }
        $bankGuarantee->save();

        return back()->with('success', 'Status updated successfully.');
    }

    public function destroy(BankGuarantee $bankGuarantee)
    {
        if ($bankGuarantee->document_path && Storage::disk('public')->exists($bankGuarantee->document_path)) {
            Storage::disk('public')->delete($bankGuarantee->document_path);
        }

        $bankGuarantee->delete();
        return redirect()->route('admin.finance.bank-guarantees.index')
            ->with('success', 'Bank guarantee deleted.');
    }

    public function printPdf(BankGuarantee $bankGuarantee)
    {
        $bankGuarantee->load('project');
        $pdf = Pdf::loadView('admin.finance.bank-guarantees.pdf.guarantee', compact('bankGuarantee'));
        return $pdf->stream();
    }
}
