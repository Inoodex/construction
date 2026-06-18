<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {
        $query = JournalEntryItem::with(['account', 'journalEntry']);

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('from')) {
            $query->whereHas('journalEntry', fn($q) => $q->where('date', '>=', $request->from));
        }

        if ($request->filled('to')) {
            $query->whereHas('journalEntry', fn($q) => $q->where('date', '<=', $request->to));
        }

        $query->whereHas('journalEntry', fn($q) => $q->where('status', 'posted'))
            ->orderBy('account_id')
            ->orderBy('journal_entry_id');

        $items = $query->get()->groupBy('account_id');

        $accounts = ChartOfAccount::active()->orderBy('account_code')->get();
        $selectedAccount = $request->account_id ? ChartOfAccount::find($request->account_id) : null;

        // Calculate opening balances
        $openingDate = $request->from;
        $ledger = [];
        foreach ($items as $accountId => $entries) {
            $account = $entries->first()->account;
            $running = 0;

            // Opening balance
            if ($openingDate) {
                $openingQuery = JournalEntryItem::where('account_id', $accountId)
                    ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted')->where('date', '<', $openingDate));
                $opDebit = (clone $openingQuery)->sum('debit_amount');
                $opCredit = (clone $openingQuery)->sum('credit_amount');
                $running = $account->normal_balance === 'debit' ? $opDebit - $opCredit : $opCredit - $opDebit;
            }

            $lines = [];
            foreach ($entries as $item) {
                if ($account->normal_balance === 'debit') {
                    $running += $item->debit_amount - $item->credit_amount;
                } else {
                    $running += $item->credit_amount - $item->debit_amount;
                }
                $lines[] = [
                    'date' => $item->journalEntry->date,
                    'journal_number' => $item->journalEntry->journal_number,
                    'description' => $item->description ?? $item->journalEntry->description,
                    'debit' => $item->debit_amount,
                    'credit' => $item->credit_amount,
                    'balance' => $running,
                ];
            }

            $ledger[] = [
                'account' => $account,
                'lines' => $lines,
                'total_debit' => $entries->sum('debit_amount'),
                'total_credit' => $entries->sum('credit_amount'),
                'closing_balance' => $running,
            ];
        }

        return view('admin.finance.general-ledger.index', compact('ledger', 'accounts', 'selectedAccount'));
    }
}
