<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Turns operational documents (invoices, bills, expenses, project completion)
 * into balanced, posted double-entry journal entries.
 *
 * Posting is idempotent: a given source document maps to at most one active
 * auto entry per posting "type". Reversing an entry creates a mirror entry
 * and marks the original voided, preserving an audit trail.
 *
 * Account codes referenced (see ChartOfAccountSeeder):
 *   1-1010 Cash & Bank          1-1020 Accounts Receivable
 *   1-1040 Work in Progress     1-1050 Retention Receivable
 *   2-1010 Accounts Payable     2-1030 Tax Payable
 *   4-1010 Contract Revenue     5-1000 Direct Costs   5-2000 Overhead
 */
class LedgerPostingService
{
    private array $accountCache = [];

    /**
     * Resolve a chart-of-accounts id by its account_code.
     */
    private function accountId(string $code): int
    {
        if (!isset($this->accountCache[$code])) {
            $account = ChartOfAccount::where('account_code', $code)->first();
            if (!$account) {
                throw new \RuntimeException("Chart of account '{$code}' not found. Run the ChartOfAccountSeeder.");
            }
            $this->accountCache[$code] = $account->id;
        }

        return $this->accountCache[$code];
    }

    /**
     * Create a balanced, posted journal entry for a source document.
     *
     * @param  Model  $source  The originating document.
     * @param  string $type    Posting category, e.g. 'invoice', 'invoice-payment'.
     * @param  string $date    Y-m-d.
     * @param  string $description
     * @param  array  $lines   [['code' => '1-1020', 'debit' => 100, 'credit' => 0, 'description' => '...'], ...]
     * @return JournalEntry|null Null when the entry has no non-zero lines.
     */
    public function post(Model $source, string $type, string $date, string $description, array $lines): ?JournalEntry
    {
        // Idempotency: skip if an active auto entry already exists for this source + type.
        $exists = JournalEntry::where('source_type', get_class($source))
            ->where('source_id', $source->getKey())
            ->where('type', $type)
            ->where('status', 'posted')
            ->exists();
        if ($exists) {
            return null;
        }

        $clean = [];
        $totalDebit = 0;
        $totalCredit = 0;
        foreach ($lines as $line) {
            $debit = round((float) ($line['debit'] ?? 0), 2);
            $credit = round((float) ($line['credit'] ?? 0), 2);
            if ($debit <= 0 && $credit <= 0) {
                continue;
            }
            $clean[] = [
                'account_id' => $this->accountId($line['code']),
                'debit_amount' => $debit,
                'credit_amount' => $credit,
                'description' => $line['description'] ?? $description,
            ];
            $totalDebit += $debit;
            $totalCredit += $credit;
        }

        if (empty($clean)) {
            return null;
        }

        if (abs($totalDebit - $totalCredit) > 0.01) {
            throw new \RuntimeException(
                "Auto journal for {$type} is unbalanced (debit {$totalDebit} != credit {$totalCredit})."
            );
        }

        return DB::transaction(function () use ($source, $type, $date, $description, $clean) {
            $entry = JournalEntry::create([
                'journal_number' => $this->nextNumber(),
                'date' => $date,
                'description' => $description,
                'type' => $type,
                'status' => 'posted',
                'is_auto' => true,
                'source_type' => get_class($source),
                'source_id' => $source->getKey(),
                'created_by' => auth()->id(),
            ]);

            foreach ($clean as $line) {
                $entry->items()->create($line);
            }

            return $entry;
        });
    }

    /**
     * Reverse the auto entries for a source document (optionally a single type).
     * Marks the matching posted auto entries as voided. The voided rows and their
     * items remain in the table as an audit trail; the GL, trial balance and
     * financial statements all filter on status = 'posted', so voiding cleanly
     * removes their effect without needing a contra entry.
     */
    public function reverse(Model $source, ?string $type = null): void
    {
        $query = JournalEntry::where('source_type', get_class($source))
            ->where('source_id', $source->getKey())
            ->where('status', 'posted')
            ->where('is_auto', true);

        if ($type !== null) {
            $query->where('type', $type);
        }

        $query->update(['status' => 'voided']);
    }

    // ---- Document-specific posting rules -------------------------------

    /**
     * Invoice moved out of draft. Recognises revenue, tax and retention.
     * Dr AR (net owed) + Dr Retention Receivable, Cr Revenue + Cr Tax Payable.
     */
    public function postInvoice(\App\Models\Invoice $invoice): ?JournalEntry
    {
        $lines = [
            ['code' => '1-1020', 'debit' => $invoice->total_amount, 'credit' => 0, 'description' => "AR - Invoice #{$invoice->invoice_number}"],
        ];
        if ($invoice->retention_amount > 0) {
            $lines[] = ['code' => '1-1050', 'debit' => $invoice->retention_amount, 'credit' => 0, 'description' => "Retention held - #{$invoice->invoice_number}"];
        }
        $lines[] = ['code' => '4-1010', 'debit' => 0, 'credit' => $invoice->subtotal, 'description' => "Contract revenue - #{$invoice->invoice_number}"];
        if ($invoice->tax_amount > 0) {
            $lines[] = ['code' => '2-1030', 'debit' => 0, 'credit' => $invoice->tax_amount, 'description' => "Output tax - #{$invoice->invoice_number}"];
        }

        return $this->post($invoice, 'invoice', (string) $invoice->issue_date, "Invoice #{$invoice->invoice_number}", $lines);
    }

    /**
     * Payment received against an invoice. Dr Cash, Cr AR.
     */
    public function postInvoicePayment(\App\Models\Payment $payment, \App\Models\Invoice $invoice): ?JournalEntry
    {
        return $this->post(
            $payment,
            'invoice-payment',
            (string) $payment->payment_date,
            "Payment received - Invoice #{$invoice->invoice_number}",
            [
                ['code' => '1-1010', 'debit' => $payment->amount, 'credit' => 0, 'description' => 'Cash received'],
                ['code' => '1-1020', 'debit' => 0, 'credit' => $payment->amount, 'description' => 'Settle AR'],
            ]
        );
    }

    /**
     * Vendor bill approved. Project costs capitalise to WIP; overheads expense.
     * Dr WIP/Overhead + Dr Tax Payable (input), Cr Accounts Payable.
     */
    public function postBill(\App\Models\Bill $bill): ?JournalEntry
    {
        $costCode = $bill->project_id ? '1-1040' : '5-2000';
        $lines = [
            ['code' => $costCode, 'debit' => $bill->subtotal, 'credit' => 0, 'description' => "Bill #{$bill->bill_number}"],
        ];
        if ($bill->tax_amount > 0) {
            $lines[] = ['code' => '2-1030', 'debit' => $bill->tax_amount, 'credit' => 0, 'description' => "Input tax - #{$bill->bill_number}"];
        }
        $lines[] = ['code' => '2-1010', 'debit' => 0, 'credit' => $bill->total_amount, 'description' => "Payable - #{$bill->bill_number}"];

        return $this->post($bill, 'bill', (string) $bill->bill_date, "Bill #{$bill->bill_number}", $lines);
    }

    /**
     * Payment made against a bill. Dr Accounts Payable, Cr Cash.
     */
    public function postBillPayment(\App\Models\BillPayment $payment, \App\Models\Bill $bill): ?JournalEntry
    {
        return $this->post(
            $payment,
            'bill-payment',
            (string) $payment->payment_date,
            "Payment made - Bill #{$bill->bill_number}",
            [
                ['code' => '2-1010', 'debit' => $payment->amount, 'credit' => 0, 'description' => 'Settle payable'],
                ['code' => '1-1010', 'debit' => 0, 'credit' => $payment->amount, 'description' => 'Cash paid'],
            ]
        );
    }

    /**
     * Expense recorded. Project costs capitalise to WIP; overheads expense.
     * Credit side is Cash when paid from a payment account, else Accounts Payable.
     */
    public function postExpense(\App\Models\Expense $expense): ?JournalEntry
    {
        $costCode = $expense->project_id ? '1-1040' : '5-2000';
        $creditCode = $expense->payment_account_id ? '1-1010' : '2-1010';

        $lines = [
            ['code' => $costCode, 'debit' => $expense->amount, 'credit' => 0, 'description' => "Expense: {$expense->title}"],
        ];
        if ($expense->tax_amount > 0) {
            $lines[] = ['code' => '2-1030', 'debit' => $expense->tax_amount, 'credit' => 0, 'description' => "Input tax: {$expense->title}"];
        }
        $lines[] = ['code' => $creditCode, 'debit' => 0, 'credit' => $expense->total_amount, 'description' => "Expense: {$expense->title}"];

        return $this->post($expense, 'expense', (string) $expense->expense_date, "Expense: {$expense->title}", $lines);
    }

    /**
     * Project completed. Transfer accumulated project WIP into Direct Costs.
     * Dr Direct Costs, Cr WIP for the net WIP attributable to the project.
     */
    public function transferProjectWipToCost(\App\Models\Project $project): ?JournalEntry
    {
        $wipAccountId = $this->accountId('1-1040');
        $netWip = $this->projectWipBalance($project, $wipAccountId);

        if ($netWip <= 0.01) {
            return null;
        }

        return $this->post(
            $project,
            'wip-transfer',
            now()->format('Y-m-d'),
            "WIP to cost on completion - {$project->name}",
            [
                ['code' => '5-1000', 'debit' => $netWip, 'credit' => 0, 'description' => "Cost recognised - {$project->name}"],
                ['code' => '1-1040', 'debit' => 0, 'credit' => $netWip, 'description' => "Release WIP - {$project->name}"],
            ]
        );
    }

    /**
     * Net WIP posted to the ledger from this project's bills and expenses.
     */
    private function projectWipBalance(\App\Models\Project $project, int $wipAccountId): float
    {
        $billIds = \App\Models\Bill::where('project_id', $project->id)->pluck('id');
        $expenseIds = \App\Models\Expense::where('project_id', $project->id)->pluck('id');

        $sum = DB::table('journal_entry_items as jei')
            ->join('journal_entries as je', 'je.id', '=', 'jei.journal_entry_id')
            ->where('je.status', 'posted')
            ->where('jei.account_id', $wipAccountId)
            ->where(function ($q) use ($billIds, $expenseIds) {
                $q->where(function ($q2) use ($billIds) {
                    $q2->where('je.source_type', \App\Models\Bill::class)
                        ->whereIn('je.source_id', $billIds);
                })->orWhere(function ($q2) use ($expenseIds) {
                    $q2->where('je.source_type', \App\Models\Expense::class)
                        ->whereIn('je.source_id', $expenseIds);
                });
            })
            ->selectRaw('COALESCE(SUM(jei.debit_amount - jei.credit_amount), 0) as net')
            ->value('net');

        return round((float) $sum, 2);
    }

    private function nextNumber(): string
    {
        return 'JV-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
    }
}
