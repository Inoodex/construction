<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentAccount::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $accounts = $query->latest()->get();
        $totalBalance = $accounts->where('status', 'active')->sum('current_balance');

        return view('admin.finance.payment-accounts.index', compact('accounts', 'totalBalance'));
    }

    public function create()
    {
        return view('admin.finance.payment-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,mfs,cash',
            'account_number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'opening_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['current_balance'] = $validated['opening_balance'];
        $validated['status'] = 'active';
        $validated['created_by'] = Auth::id();

        $account = PaymentAccount::create($validated);

        // Record opening balance as initial transaction if > 0
        if ($validated['opening_balance'] > 0) {
            AccountTransaction::create([
                'payment_account_id' => $account->id,
                'type' => 'credit',
                'amount' => $validated['opening_balance'],
                'balance_after' => $validated['opening_balance'],
                'description' => 'Opening balance',
                'transaction_date' => now(),
            ]);
        }

        return redirect()->route('admin.finance.payment-accounts.index')
            ->with('success', 'Payment account created successfully.');
    }

    public function show(PaymentAccount $paymentAccount)
    {
        $paymentAccount->load('creator');

        $transactions = $paymentAccount->transactions()
            ->latest('transaction_date')
            ->paginate(25);

        return view('admin.finance.payment-accounts.show', compact('paymentAccount', 'transactions'));
    }

    public function edit(PaymentAccount $paymentAccount)
    {
        return view('admin.finance.payment-accounts.edit', compact('paymentAccount'));
    }

    public function update(Request $request, PaymentAccount $paymentAccount)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,mfs,cash',
            'account_number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $paymentAccount->update($validated);

        return redirect()->route('admin.finance.payment-accounts.index')
            ->with('success', 'Payment account updated successfully.');
    }

    public function destroy(PaymentAccount $paymentAccount)
    {
        if ($paymentAccount->transactions()->exists()) {
            return back()->with('error', 'Cannot delete an account with transaction history.');
        }

        $paymentAccount->delete();
        return redirect()->route('admin.finance.payment-accounts.index')
            ->with('success', 'Payment account deleted.');
    }

    public function printPdf(PaymentAccount $paymentAccount)
    {
        $paymentAccount->load('creator');
        $transactions = $paymentAccount->transactions()->latest('transaction_date')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.finance.payment-accounts.pdf.ledger', compact('paymentAccount', 'transactions'));
        return $pdf->stream();
    }
}
