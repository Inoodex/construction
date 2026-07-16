<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\ChartOfAccount;
use App\Models\Invoice;
use App\Models\JournalEntryItem;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;

/**
 * Control-account reconciliation. Compares the general-ledger balance of each
 * control account against the operational subledger it is meant to mirror, so
 * that missed or mis-keyed journal entries surface instead of drifting silently.
 */
class ReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $asOf = $request->date ?: date('Y-m-d');

        $rows = [
            $this->buildRow(
                'Accounts Receivable',
                '1-1020',
                $asOf,
                Invoice::whereNotIn('status', ['draft', 'cancelled'])->sum('due_amount'),
                'Outstanding invoice balances'
            ),
            $this->buildRow(
                'Accounts Payable',
                '2-1010',
                $asOf,
                Bill::whereNotIn('status', ['draft', 'cancelled'])->sum('due_amount'),
                'Outstanding bill balances'
            ),
            $this->buildRow(
                'Cash & Bank',
                '1-1010',
                $asOf,
                PaymentAccount::where('status', 'active')->sum('current_balance'),
                'Sum of active payment account balances'
            ),
        ];

        return view('admin.finance.reconciliation.index', compact('rows', 'asOf'));
    }

    private function buildRow(string $label, string $code, string $asOf, float $subledger, string $note): array
    {
        $account = ChartOfAccount::where('account_code', $code)->first();
        $ledger = $account ? $this->ledgerBalance($account, $asOf) : 0.0;
        $difference = round($ledger - $subledger, 2);

        return [
            'label' => $label,
            'code' => $code,
            'ledger' => round($ledger, 2),
            'subledger' => round($subledger, 2),
            'difference' => $difference,
            'matched' => abs($difference) < 0.01,
            'note' => $note,
        ];
    }

    private function ledgerBalance(ChartOfAccount $account, string $asOf): float
    {
        $query = JournalEntryItem::where('account_id', $account->id)
            ->whereHas('journalEntry', fn ($q) => $q->where('status', 'posted')->where('date', '<=', $asOf));

        $debit = (clone $query)->sum('debit_amount');
        $credit = (clone $query)->sum('credit_amount');

        return $account->normal_balance === 'debit' ? $debit - $credit : $credit - $debit;
    }
}
