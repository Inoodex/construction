<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?: date('Y-m-01');
        $endDate = $request->end_date ?: date('Y-m-d');

        $accounts = ChartOfAccount::active()->whereIn('type', ['income', 'expense'])
            ->orderBy('account_code')->get();

        $sections = ['income' => [], 'expense' => []];
        $totals = ['income' => 0, 'expense' => 0];

        foreach ($accounts as $account) {
            $query = JournalEntryItem::where('account_id', $account->id)
                ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted')
                    ->whereBetween('date', [$startDate, $endDate]));

            $debit = (clone $query)->sum('debit_amount');
            $credit = (clone $query)->sum('credit_amount');

            $balance = $account->normal_balance === 'debit' ? $debit - $credit : $credit - $debit;

            if (abs($balance) < 0.01) {
                continue;
            }

            $sections[$account->type][] = [
                'code' => $account->account_code,
                'name' => $account->name,
                'balance' => abs($balance),
            ];
            $totals[$account->type] += abs($balance);
        }

        $totalIncome = $totals['income'];
        $totalExpenses = $totals['expense'];
        $netIncome = $totalIncome - $totalExpenses;

        return view('admin.finance.income-statement.index', compact('sections', 'totals', 'startDate', 'endDate', 'totalIncome', 'totalExpenses', 'netIncome'));
    }
}
