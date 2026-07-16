<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillPayment;
use App\Models\PaymentAccount;
use App\Models\AccountTransaction;
use App\Models\Project;
use App\Models\Vendor;
use App\Services\LedgerPostingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillController extends Controller
{
    public function __construct(private LedgerPostingService $ledger) {}

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
        $accounts = \App\Models\PaymentAccount::where('status', 'active')->get();
        return view('admin.finance.bills.show', compact('bill', 'accounts'));
    }

    public function printPdf(Bill $bill)
    {
        $bill->load('project', 'vendor', 'items', 'payments');
        $pdf = Pdf::loadView('admin.finance.bills.pdf.bill', compact('bill'))
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream('BILL-' . $bill->bill_number . '.pdf');
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
        $this->recalculateBill($bill);
        return redirect()->route('admin.finance.bills.index')
            ->with('success', 'Bill updated.');
    }

    public function destroy(Bill $bill)
    {
        DB::transaction(function () use ($bill) {
            foreach ($bill->payments as $payment) {
                if ($payment->payment_account_id) {
                    $account = PaymentAccount::find($payment->payment_account_id);
                    if ($account) {
                        $account->increment('current_balance', $payment->amount);
                        AccountTransaction::where('transactable_type', BillPayment::class)
                            ->where('transactable_id', $payment->id)
                            ->delete();
                    }
                }
                $this->ledger->reverse($payment, 'bill-payment');
            }
            $this->ledger->reverse($bill, 'bill');
            $bill->payments()->delete();
            $bill->items()->delete();
            $bill->delete();
        });

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
        abort_if(in_array($bill->status, ['draft', 'cancelled']), 400, 'Payments cannot be recorded for draft or cancelled bills.');

        $validated = $request->validate([
            'amount'        => 'required|numeric|min:0.01',
            'payment_date'  => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'reference'     => 'nullable|string|max:100',
            'notes'         => 'nullable|string',
            'payment_account_id' => 'nullable|exists:payment_accounts,id',
        ]);

        DB::transaction(function () use ($validated, $bill) {
            $validated['bill_id'] = $bill->id;
            $billPayment = BillPayment::create($validated);

            if (!empty($validated['payment_account_id'])) {
                $account = PaymentAccount::findOrFail($validated['payment_account_id']);
                $account->decrement('current_balance', $validated['amount']);
                $newBalance = $account->fresh()->current_balance;
                AccountTransaction::create([
                    'payment_account_id' => $account->id,
                    'type' => 'debit',
                    'amount' => $validated['amount'],
                    'balance_after' => $newBalance,
                    'description' => "Payment made for Bill #{$bill->bill_number}",
                    'transactable_type' => BillPayment::class,
                    'transactable_id' => $billPayment->id,
                    'reference' => $validated['reference'] ?? null,
                    'transaction_date' => $validated['payment_date'],
                ]);
            }

            $totalPaid = $bill->payments()->sum('amount');
            $dueAmount = $bill->total_amount - $totalPaid;
            $status = $dueAmount <= 0 ? 'paid' : ($totalPaid > 0 ? 'approved' : $bill->status);

            $bill->update([
                'paid_amount' => $totalPaid,
                'due_amount'  => max($dueAmount, 0),
                'status'      => $status,
            ]);

            $this->syncBillPosting($bill->fresh());
            $this->ledger->postBillPayment($billPayment, $bill);
        });

        return back()->with('success', 'Payment recorded.');
    }

    public function removePayment(Bill $bill, BillPayment $payment)
    {
        DB::transaction(function () use ($bill, $payment) {
            if ($payment->payment_account_id) {
                $account = PaymentAccount::find($payment->payment_account_id);
                if ($account) {
                    $account->increment('current_balance', $payment->amount);
                    AccountTransaction::where('transactable_type', BillPayment::class)
                        ->where('transactable_id', $payment->id)
                        ->delete();
                }
            }

            $this->ledger->reverse($payment, 'bill-payment');
            $payment->delete();

            $totalPaid = $bill->payments()->sum('amount');
            $dueAmount = $bill->total_amount - $totalPaid;
            $status = $dueAmount <= 0 ? 'paid' : ($totalPaid > 0 ? 'approved' : 'draft');

            $bill->update([
                'paid_amount' => $totalPaid,
                'due_amount'  => max($dueAmount, 0),
                'status'      => $status,
            ]);

            $this->syncBillPosting($bill->fresh());
        });

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

        if ($bill->status !== 'draft') {
            $this->syncBillPosting($bill->fresh());
        }
    }

    /**
     * Keep the bill's WIP/payable journal entry in step with its status/totals.
     * Draft and cancelled bills carry no posting; any other status posts (or
     * re-posts) the current figures. Payment entries are handled separately.
     */
    private function syncBillPosting(Bill $bill): void
    {
        DB::transaction(function () use ($bill) {
            $this->ledger->reverse($bill, 'bill');

            if (!in_array($bill->status, ['draft', 'cancelled'])) {
                $this->ledger->postBill($bill);
            }
        });
    }
}
