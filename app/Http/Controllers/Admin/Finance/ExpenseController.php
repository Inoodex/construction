<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\PaymentAccount;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('category', 'vendor', 'project', 'creator');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('expense_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('expense_date', '<=', $request->to);
        }

        $expenses = $query->latest()->paginate(15);
        $categories = Category::byType('expense_type')->get();
        $projects = Project::all();
        $vendors = Vendor::all();

        return view('admin.finance.expenses.index', compact('expenses', 'categories', 'projects', 'vendors'));
    }

    public function create()
    {
        $categories = Category::byType('expense_type')->get();
        $projects = Project::all();
        $vendors = Vendor::all();
        $accounts = \App\Models\PaymentAccount::where('status', 'active')->get();
        return view('admin.finance.expenses.create', compact('categories', 'projects', 'vendors', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'vendor_id'           => 'nullable|exists:vendors,id',
            'project_id'          => 'nullable|exists:projects,id',
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'amount'              => 'required|numeric|min:0',
            'tax_rate'            => 'required|numeric|min:0|max:100',
            'expense_date'        => 'required|date',
            'payment_method'      => 'nullable|string|max:50',
            'reference_number'    => 'nullable|string|max:100',
            'notes'               => 'nullable|string',
            'receipt'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'payment_account_id'  => 'nullable|exists:payment_accounts,id',
        ]);

        $validated['tax_amount'] = $validated['amount'] * ($validated['tax_rate'] / 100);
        $validated['total_amount'] = $validated['amount'] + $validated['tax_amount'];
        $validated['status'] = 'draft';
        $validated['created_by'] = Auth::id();

        if ($request->hasFile('receipt')) {
            $validated['receipt'] = $request->file('receipt')->store('expenses', 'public');
        }

        Expense::create($validated);

        if (!empty($validated['payment_account_id'])) {
            $account = PaymentAccount::findOrFail($validated['payment_account_id']);
            $newBalance = $account->current_balance - $validated['total_amount'];
            $account->update(['current_balance' => $newBalance]);
            AccountTransaction::create([
                'payment_account_id' => $account->id,
                'type' => 'debit',
                'amount' => $validated['total_amount'],
                'balance_after' => $newBalance,
                'description' => "Expense: {$validated['title']}",
                'transactable_type' => Expense::class,
                'transactable_id' => Expense::latest()->first()->id,
                'reference' => $validated['reference_number'] ?? null,
                'transaction_date' => $validated['expense_date'],
            ]);
        }

        return redirect()->route('admin.finance.expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load('category', 'vendor', 'project', 'creator');
        return view('admin.finance.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = Category::byType('expense_type')->get();
        $projects = Project::all();
        $vendors = Vendor::all();
        return view('admin.finance.expenses.edit', compact('expense', 'categories', 'projects', 'vendors'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'vendor_id'   => 'nullable|exists:vendors,id',
            'project_id'          => 'nullable|exists:projects,id',
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'amount'              => 'required|numeric|min:0',
            'tax_rate'            => 'required|numeric|min:0|max:100',
            'expense_date'        => 'required|date',
            'payment_method'      => 'nullable|string|max:50',
            'reference_number'    => 'nullable|string|max:100',
            'notes'               => 'nullable|string',
            'receipt'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['tax_amount'] = $validated['amount'] * ($validated['tax_rate'] / 100);
        $validated['total_amount'] = $validated['amount'] + $validated['tax_amount'];

        if ($request->hasFile('receipt')) {
            $validated['receipt'] = $request->file('receipt')->store('expenses', 'public');
        }

        $expense->update($validated);

        return redirect()->route('admin.finance.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('admin.finance.expenses.index')
            ->with('success', 'Expense deleted.');
    }

    public function markPaid(Expense $expense)
    {
        $expense->update(['status' => 'paid']);
        return back()->with('success', 'Expense marked as paid.');
    }
}
