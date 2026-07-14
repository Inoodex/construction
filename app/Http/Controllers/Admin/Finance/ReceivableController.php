<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Receivable;
use App\Models\PaymentAccount;
use App\Models\AccountTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    public function index(Request $request)
    {
        $query = Receivable::with('project');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('payer_name', 'like', "%{$s}%")
                    ->orWhere('receivable_number', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $receivables = $query->latest()->paginate(15);

        return view('admin.finance.receivables.index', compact('receivables'));
    }

    public function create()
    {
        $projects = Project::pluck('name', 'id');
        $nextNumber = 'AR-' . date('Ymd') . '-' . str_pad(Receivable::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.finance.receivables.create', compact('projects', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receivable_number' => 'required|string|max:50|unique:receivables',
            'project_id' => 'nullable|exists:projects,id',
            'payer_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';
        $validated['created_by'] = auth()->id();

        Receivable::create($validated);

        return redirect()->route('admin.finance.receivables.index')
            ->with('success', 'Receivable created successfully.');
    }

    public function show(Receivable $receivable)
    {
        $receivable->load('project', 'payments');
        $accounts = \App\Models\PaymentAccount::where('status', 'active')->get();
        return view('admin.finance.receivables.show', compact('receivable', 'accounts'));
    }

    public function destroy(Receivable $receivable)
    {
        $receivable->delete();
        return redirect()->route('admin.finance.receivables.index')
            ->with('success', 'Receivable deleted successfully.');
    }

    public function addPayment(Request $request, Receivable $receivable)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $receivable->due_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_account_id' => 'nullable|exists:payment_accounts,id',
        ]);

        $receivable->payments()->create($validated);
        $receivable->increment('paid_amount', $validated['amount']);

        if (!empty($validated['payment_account_id'])) {
            $account = PaymentAccount::findOrFail($validated['payment_account_id']);
            $newBalance = $account->current_balance + $validated['amount'];
            $account->update(['current_balance' => $newBalance]);
            AccountTransaction::create([
                'payment_account_id' => $account->id,
                'type' => 'credit',
                'amount' => $validated['amount'],
                'balance_after' => $newBalance,
                'description' => "Receivable payment received for #{$receivable->receivable_number}",
                'transactable_type' => \App\Models\ReceivablePayment::class,
                'transactable_id' => $receivable->payments()->latest()->first()->id,
                'reference' => $validated['reference'] ?? null,
                'transaction_date' => $validated['payment_date'],
            ]);
        }

        if ($receivable->paid_amount >= $receivable->amount) {
            $receivable->update(['status' => 'paid']);
        } elseif ($receivable->paid_amount > 0) {
            $receivable->update(['status' => 'partial']);
        }

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function printPdf(Receivable $receivable)
    {
        $receivable->load('project', 'payments');
        $pdf = Pdf::loadView('admin.finance.receivables.pdf.receivable', compact('receivable'))
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream('AR-'.$receivable->receivable_number.'.pdf');
    }

    public function removePayment(Receivable $receivable, $payment)
    {
        $payment = $receivable->payments()->findOrFail($payment);
        $receivable->decrement('paid_amount', $payment->amount);
        $payment->delete();

        if ($receivable->paid_amount <= 0) {
            $receivable->update(['status' => 'pending']);
        } elseif ($receivable->paid_amount < $receivable->amount) {
            $receivable->update(['status' => 'partial']);
        }

        return back()->with('success', 'Payment removed successfully.');
    }
}
