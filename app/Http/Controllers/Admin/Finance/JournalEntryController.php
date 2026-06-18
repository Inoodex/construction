<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = JournalEntry::with('items.account', 'creator');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('journal_number', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $entries = $query->latest()->paginate(15);

        return view('admin.finance.journal-entries.index', compact('entries'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::active()->orderBy('account_code')->get();
        $nextNumber = 'JV-' . date('Ymd') . '-' . str_pad(JournalEntry::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.finance.journal-entries.create', compact('accounts', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'journal_number' => 'required|string|max:50|unique:journal_entries',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:chart_of_accounts,id',
            'items.*.debit_amount' => 'nullable|numeric|min:0',
            'items.*.credit_amount' => 'nullable|numeric|min:0',
            'items.*.description' => 'nullable|string|max:255',
        ]);

        // Validate at least one debit and one credit
        $totalDebit = collect($validated['items'])->sum('debit_amount');
        $totalCredit = collect($validated['items'])->sum('credit_amount');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withErrors(['items' => "Total debit ($totalDebit) must equal total credit ($totalCredit)."])->withInput();
        }

        if ($totalDebit <= 0 || $totalCredit <= 0) {
            return back()->withErrors(['items' => 'Each entry must have at least one debit line and one credit line.'])->withInput();
        }

        DB::transaction(function () use ($validated) {
            $entry = JournalEntry::create([
                'journal_number' => $validated['journal_number'],
                'date' => $validated['date'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $entry->items()->create([
                    'account_id' => $item['account_id'],
                    'debit_amount' => $item['debit_amount'] ?? 0,
                    'credit_amount' => $item['credit_amount'] ?? 0,
                    'description' => $item['description'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.finance.journal-entries.index')
            ->with('success', 'Journal entry posted successfully.');
    }

    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load('items.account', 'creator');
        return view('admin.finance.journal-entries.show', ['entry' => $journalEntry]);
    }

    public function destroy(JournalEntry $journalEntry)
    {
        $journalEntry->delete();

        return redirect()->route('admin.finance.journal-entries.index')
            ->with('success', 'Journal entry deleted successfully.');
    }
}
