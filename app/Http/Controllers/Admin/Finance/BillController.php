<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillPayment;
use App\Models\Project;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with('project', 'vendor');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bills = $query->latest()->paginate(15);
        $projects = Project::all();
        $vendors = Vendor::all();
        return view('admin.finance.bills.index', compact('bills', 'projects', 'vendors'));
    }

    public function create()
    {
        $projects = Project::all();
        $vendors = Vendor::all();
        return view('admin.finance.bills.create', compact('projects', 'vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'vendor_id'  => 'required|exists:vendors,id',
            'title'      => 'required|string|max:255',
            'reference'  => 'nullable|string|max:100',
            'bill_date'  => 'required|date',
            'due_date'   => 'required|date|after_or_equal:bill_date',
            'tax_rate'   => 'required|numeric|min:0|max:100',
            'notes'      => 'nullable|string',
        ]);

        $validated['bill_number'] = 'BILL-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        $validated['subtotal'] = 0;
        $validated['tax_amount'] = 0;
        $validated['total_amount'] = 0;
        $validated['paid_amount'] = 0;
        $validated['due_amount'] = 0;
        $validated['status'] = 'draft';
        $validated['created_by'] = Auth::id();

        Bill::create($validated);

        return redirect()->route('admin.finance.bills.index')
            ->with('success', 'Bill created. Add items from the detail page.');
    }

    public function show(Bill $bill)
    {
        $bill->load('project', 'vendor', 'creator', 'items', 'payments');
        return view('admin.finance.bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        $projects = Project::all();
        $vendors = Vendor::all();
        return view('admin.finance.bills.edit', compact('bill', 'projects', 'vendors'));
    }

    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'vendor_id'  => 'required|exists:vendors,id',
            'title'      => 'required|string|max:255',
            'reference'  => 'nullable|string|max:100',
            'bill_date'  => 'required|date',
            'due_date'   => 'required|date|after_or_equal:bill_date',
            'tax_rate'   => 'required|numeric|min:0|max:100',
            'notes'      => 'nullable|string',
        ]);

        $bill->update($validated);
        return redirect()->route('admin.finance.bills.index')
            ->with('success', 'Bill updated.');
    }

    public function destroy(Bill $bill)
    {
        $bill->payments()->delete();
        $bill->items()->delete();
        $bill->delete();
        return redirect()->route('admin.finance.bills.index')
            ->with('success', 'Bill deleted.');
    }

    public function addItem(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'quantity'    => 'required|numeric|min:0.0001',
            'unit_price'  => 'required|numeric|min:0',
        ]);

        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];
        $validated['bill_id'] = $bill->id;

        BillItem::create($validated);
        $this->recalculateBill($bill);

        return back()->with('success', 'Item added.');
    }

    public function removeItem(Bill $bill, BillItem $billItem)
    {
        $billItem->delete();
        $this->recalculateBill($bill);
        return back()->with('success', 'Item removed.');
    }

    public function addPayment(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'amount'        => 'required|numeric|min:0.01',
            'payment_date'  => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'reference'     => 'nullable|string|max:100',
            'notes'         => 'nullable|string',
        ]);

        $validated['bill_id'] = $bill->id;
        BillPayment::create($validated);

        $totalPaid = $bill->payments()->sum('amount');
        $dueAmount = $bill->total_amount - $totalPaid;
        $status = $dueAmount <= 0 ? 'paid' : ($totalPaid > 0 ? 'approved' : $bill->status);

        $bill->update([
            'paid_amount' => $totalPaid,
            'due_amount'  => max($dueAmount, 0),
            'status'      => $status,
        ]);

        return back()->with('success', 'Payment recorded.');
    }

    public function removePayment(Bill $bill, BillPayment $payment)
    {
        $payment->delete();
        $totalPaid = $bill->payments()->sum('amount');
        $dueAmount = $bill->total_amount - $totalPaid;
        $status = $dueAmount <= 0 ? 'paid' : ($totalPaid > 0 ? 'approved' : $bill->status);

        $bill->update([
            'paid_amount' => $totalPaid,
            'due_amount'  => max($dueAmount, 0),
            'status'      => $status,
        ]);

        return back()->with('success', 'Payment removed.');
    }

    private function recalculateBill(Bill $bill): void
    {
        $subtotal = $bill->items()->sum('total_price');
        $taxAmount = $subtotal * ($bill->tax_rate / 100);
        $total = $subtotal + $taxAmount;
        $paid = $bill->payments()->sum('amount');
        $due = $total - $paid;

        $bill->update([
            'subtotal'     => $subtotal,
            'tax_amount'   => $taxAmount,
            'total_amount' => $total,
            'paid_amount'  => $paid,
            'due_amount'   => max($due, 0),
        ]);
    }
}
