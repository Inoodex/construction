<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('project');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(15);
        $projects = Project::all();
        return view('admin.finance.invoices.index', compact('invoices', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.finance.invoices.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'retention_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,sent,partially_paid,paid,overdue,cancelled',
        ]);

        $validated['invoice_number'] = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        $validated['subtotal'] = 0;
        $validated['tax_amount'] = 0;
        $validated['retention_amount'] = 0;
        $validated['total_amount'] = 0;
        $validated['paid_amount'] = 0;
        $validated['due_amount'] = 0;
        $validated['created_by'] = Auth::id();

        Invoice::create($validated);

        return redirect()->route('admin.finance.invoices.index')
            ->with('success', 'Invoice created. Add items from the detail page.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('project', 'creator', 'items', 'payments');
        return view('admin.finance.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $projects = Project::all();
        return view('admin.finance.invoices.edit', compact('invoice', 'projects'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'retention_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,sent,partially_paid,paid,overdue,cancelled',
        ]);

        $invoice->update($validated);

        return redirect()->route('admin.finance.invoices.index')
            ->with('success', 'Invoice updated.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->payments()->delete();
        $invoice->items()->delete();
        $invoice->delete();
        return redirect()->route('admin.finance.invoices.index')
            ->with('success', 'Invoice deleted.');
    }

    public function addItem(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'quantity' => 'required|numeric|min:0.0001',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];
        $validated['invoice_id'] = $invoice->id;

        InvoiceItem::create($validated);

        $this->recalculateInvoice($invoice);

        return back()->with('success', 'Item added.');
    }

    public function removeItem(Invoice $invoice, InvoiceItem $invoiceItem)
    {
        $invoiceItem->delete();
        $this->recalculateInvoice($invoice);
        return back()->with('success', 'Item removed.');
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['invoice_id'] = $invoice->id;

        Payment::create($validated);

        $totalPaid = $invoice->payments()->sum('amount');
        $dueAmount = $invoice->total_amount - $totalPaid;

        $status = $dueAmount <= 0 ? 'paid' : ($totalPaid > 0 ? 'partially_paid' : $invoice->status);
        if ($invoice->status === 'draft') $status = 'draft';

        $invoice->update([
            'paid_amount' => $totalPaid,
            'due_amount' => max($dueAmount, 0),
            'status' => $status,
        ]);

        return back()->with('success', 'Payment recorded.');
    }

    public function removePayment(Invoice $invoice, Payment $payment)
    {
        $payment->delete();
        $totalPaid = $invoice->payments()->sum('amount');
        $dueAmount = $invoice->total_amount - $totalPaid;
        $status = $dueAmount <= 0 ? 'paid' : ($totalPaid > 0 ? 'partially_paid' : $invoice->status);

        $invoice->update([
            'paid_amount' => $totalPaid,
            'due_amount' => max($dueAmount, 0),
            'status' => $status,
        ]);

        return back()->with('success', 'Payment removed.');
    }

    private function recalculateInvoice(Invoice $invoice)
    {
        $subtotal = $invoice->items()->sum('total_price');
        $taxAmount = $subtotal * ($invoice->tax_rate / 100);
        $retentionAmount = $subtotal * ($invoice->retention_rate / 100);
        $total = $subtotal + $taxAmount - $retentionAmount;
        $paid = $invoice->payments()->sum('amount');
        $due = $total - $paid;

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'retention_amount' => $retentionAmount,
            'total_amount' => $total,
            'paid_amount' => $paid,
            'due_amount' => max($due, 0),
        ]);
    }
}
