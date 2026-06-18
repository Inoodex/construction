<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = ChartOfAccount::with('parent');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('account_code', 'like', "%{$s}%");
            });
        }

        $accounts = $query->orderBy('account_code')->paginate(25);

        $types = [
            'asset' => 'Asset',
            'liability' => 'Liability',
            'equity' => 'Equity',
            'income' => 'Income',
            'expense' => 'Expense',
        ];

        return view('admin.finance.chart-of-accounts.index', compact('accounts', 'types'));
    }

    public function create()
    {
        $parentAccounts = ChartOfAccount::orderBy('account_code')->get();
        return view('admin.finance.chart-of-accounts.create', compact('parentAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_code' => 'required|string|max:20|unique:chart_of_accounts',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'normal_balance' => 'required|in:debit,credit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        // Auto-set normal_balance based on type if not explicitly provided
        if (!$request->filled('normal_balance')) {
            $validated['normal_balance'] = match ($validated['type']) {
                'asset', 'expense' => 'debit',
                'liability', 'equity', 'income' => 'credit',
            };
        }

        ChartOfAccount::create($validated);

        return redirect()->route('admin.finance.chart-of-accounts.index')
            ->with('success', 'Account created successfully.');
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        $parentAccounts = ChartOfAccount::where('id', '!=', $chartOfAccount->id)
            ->orderBy('account_code')->get();
        return view('admin.finance.chart-of-accounts.edit', [
            'account' => $chartOfAccount,
            'parentAccounts' => $parentAccounts,
        ]);
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'account_code' => 'required|string|max:20|unique:chart_of_accounts,account_code,' . $chartOfAccount->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'normal_balance' => 'required|in:debit,credit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $chartOfAccount->update($validated);

        return redirect()->route('admin.finance.chart-of-accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        if ($chartOfAccount->children()->count() > 0) {
            return redirect()->route('admin.finance.chart-of-accounts.index')
                ->with('error', 'Cannot delete account with sub-accounts.');
        }

        $chartOfAccount->delete();

        return redirect()->route('admin.finance.chart-of-accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}
