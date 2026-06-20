<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\SubcontractAgreement;
use App\Models\SubcontractProgressPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubcontractProgressPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = SubcontractProgressPayment::with('agreement.subcontractor');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('agreement_id')) {
            $query->where('subcontract_agreement_id', $request->agreement_id);
        }

        $payments = $query->latest()->paginate(15);
        $agreements = SubcontractAgreement::whereIn('status', ['active', 'completed'])->get();

        return view('admin.procurement.subcontract-progress-payments.index', compact('payments', 'agreements'));
    }

    public function create()
    {
        $agreements = SubcontractAgreement::with('subcontractor')
            ->where('status', 'active')
            ->get();
        return view('admin.procurement.subcontract-progress-payments.create', compact('agreements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subcontract_agreement_id' => 'required|exists:subcontract_agreements,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'work_completed_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:2000',
        ]);

        $agreement = SubcontractAgreement::findOrFail($validated['subcontract_agreement_id']);

        $previousCertified = $agreement->totalCertifiedToDate();
        $totalCertified = $previousCertified + $validated['work_completed_value'];
        $retentionAmount = $validated['work_completed_value'] * ($agreement->retention_percentage / 100);
        $netPayable = $validated['work_completed_value'] - $retentionAmount;

        $number = 'PPC-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        SubcontractProgressPayment::create([
            'subcontract_agreement_id' => $validated['subcontract_agreement_id'],
            'certificate_number' => $number,
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'work_completed_value' => $validated['work_completed_value'],
            'previous_certified_value' => $previousCertified,
            'total_certified_to_date' => $totalCertified,
            'retention_amount' => $retentionAmount,
            'retention_released' => 0,
            'net_payable' => $netPayable,
            'status' => 'draft',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.procurement.subcontract-progress-payments.index')
            ->with('success', "Progress payment certificate $number created.");
    }

    public function show(SubcontractProgressPayment $subcontractProgressPayment)
    {
        $subcontractProgressPayment->load('agreement.subcontractor', 'agreement.project', 'certifier');
        return view('admin.procurement.subcontract-progress-payments.show', compact('subcontractProgressPayment'));
    }

    public function updateStatus(Request $request, SubcontractProgressPayment $subcontractProgressPayment)
    {
        $request->validate([
            'status' => 'required|in:draft,submitted,certified,paid',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'certified') {
            $data['certified_by'] = Auth::id();
            $data['certified_at'] = now();
        }

        if ($request->status === 'paid' && $request->filled('retention_released')) {
            $data['retention_released'] = $request->retention_released;
        }

        $subcontractProgressPayment->update($data);

        $labels = ['draft' => 'Draft', 'submitted' => 'Submitted', 'certified' => 'Certified', 'paid' => 'Paid'];

        return back()->with('success', 'Status updated to "' . ($labels[$request->status] ?? $request->status) . '".');
    }

    public function destroy(SubcontractProgressPayment $subcontractProgressPayment)
    {
        $subcontractProgressPayment->delete();
        return redirect()->route('admin.procurement.subcontract-progress-payments.index')
            ->with('success', 'Progress payment deleted.');
    }
}
