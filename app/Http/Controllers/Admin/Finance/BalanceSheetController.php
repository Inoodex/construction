<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $asOf = $request->date ?: date('Y-m-d');

        $accounts = ChartOfAccount::active()->whereIn('type', ['asset', 'liability', 'equity'])
            ->orderBy('account_code')->get();

        $sections = ['asset' => [], 'liability' => [], 'equity' => []];
        $totals = ['asset' => 0, 'liability' => 0, 'equity' => 0];

        foreach ($accounts as $account) {
            $query = JournalEntryItem::where('account_id', $account->id)
                ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted')->where('date', '<=', $asOf));

            $debit = (clone $query)->sum('debit_amount');
            $credit = (clone $query)->sum('credit_amount');

            $balance = $account->normal_balance === 'debit' ? $debit - $credit : $credit - $debit;

            if (abs($balance) < 0.01) {
                continue;
            }

            $sections[$account->type][] = [
                'code' => $account->account_code,
                'name' => $account->name,
                'balance' => $balance,
            ];
            $totals[$account->type] += $balance;
        }

        // Calculate net income (income - expenses) for the period
        $incomeExpenseAccounts = ChartOfAccount::active()->whereIn('type', ['income', 'expense'])->get();
        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($incomeExpenseAccounts as $account) {
            $query = JournalEntryItem::where('account_id', $account->id)
                ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted')->where('date', '<=', $asOf));

            $debit = (clone $query)->sum('debit_amount');
            $credit = (clone $query)->sum('credit_amount');
            $balance = $account->normal_balance === 'debit' ? $debit - $credit : $credit - $debit;

            if ($account->type === 'income') {
                $totalIncome += $balance;
            } else {
                $totalExpense += $balance;
            }
        }

        $netIncome = $totalIncome - $totalExpense;

        if (abs($netIncome) > 0.01) {
            $label = $netIncome > 0 ? 'Current Year Earnings (Net Income)' : 'Current Year Loss (Net Loss)';
            $sections['equity'][] = [
                'code' => '--',
                'name' => $label,
                'balance' => $netIncome,
            ];
            $totals['equity'] += $netIncome;
        }

        $totalAssets = $totals['asset'];
        $totalLiabilitiesEquity = $totals['liability'] + $totals['equity'];
        $difference = $totalAssets - $totalLiabilitiesEquity;

        return view('admin.finance.balance-sheet.index', compact('sections', 'totals', 'asOf', 'totalAssets', 'totalLiabilitiesEquity', 'difference'));
    }
}
