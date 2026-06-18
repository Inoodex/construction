<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $asOf = $request->date ?: date('Y-m-d');

        $accounts = ChartOfAccount::active()->orderBy('account_code')->get();

        $rows = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $account) {
            $query = JournalEntryItem::where('account_id', $account->id)
                ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted')->where('date', '<=', $asOf));

            $debit = (clone $query)->sum('debit_amount');
            $credit = (clone $query)->sum('credit_amount');

            $balance = $account->normal_balance === 'debit' ? $debit - $credit : $credit - $debit;

            if (abs($balance) < 0.01) {
                continue;
            }

            $dr = $account->normal_balance === 'debit' && $balance > 0 ? $balance : 0;
            $cr = $account->normal_balance === 'credit' && $balance > 0 ? $balance : 0;

            // For negative balances, flip the side
            if ($balance < 0) {
                $dr = $account->normal_balance === 'credit' ? abs($balance) : 0;
                $cr = $account->normal_balance === 'debit' ? abs($balance) : 0;
            }

            $totalDebit += $dr;
            $totalCredit += $cr;

            $rows[] = [
                'code' => $account->account_code,
                'name' => $account->name,
                'type' => $account->type,
                'debit' => $dr,
                'credit' => $cr,
            ];
        }

        return view('admin.finance.trial-balance.index', compact('rows', 'totalDebit', 'totalCredit', 'asOf'));
    }
}
