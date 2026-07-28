<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\ChartOfAccount;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Payment;
use App\Models\Receivable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AutoJournalPostingService
{
    /**
     * Post GL entry for an Approved Sales Invoice
     * Debit: Accounts Receivable (AR)
     * Credit: Sales / Project Revenue
     */
    public function postInvoice(Invoice $invoice): ?JournalEntry
    {
        if ($invoice->status !== 'approved' && $invoice->status !== 'paid' && $invoice->status !== 'partially_paid') {
            return null;
        }

        $arAccount = ChartOfAccount::where('account_type', 'asset')
            ->where(function ($q) {
                $q->where('name', 'like', '%Receivable%')
                  ->orWhere('account_code', 'like', '1100%');
            })->first() ?? ChartOfAccount::firstOrCreate(
                ['account_code' => '1100'],
                ['name' => 'Accounts Receivable', 'account_type' => 'asset', 'is_active' => true]
            );

        $revenueAccount = ChartOfAccount::where('account_type', 'revenue')
            ->where(function ($q) {
                $q->where('name', 'like', '%Revenue%')
                  ->orWhere('name', 'like', '%Income%')
                  ->orWhere('account_code', 'like', '4000%');
            })->first() ?? ChartOfAccount::firstOrCreate(
                ['account_code' => '4000'],
                ['name' => 'Project Revenue', 'account_type' => 'revenue', 'is_active' => true]
            );

        $existing = JournalEntry::where('description', 'like', "%Invoice #{$invoice->invoice_number}%")->first();
        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($invoice, $arAccount, $revenueAccount) {
            $nextSeq = str_pad(JournalEntry::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
            $entry = JournalEntry::create([
                'journal_number' => 'JV-' . date('Ymd') . '-' . $nextSeq,
                'date'           => $invoice->issue_date ?? now()->toDateString(),
                'description'    => "Auto-posting for Invoice #{$invoice->invoice_number}: {$invoice->title}",
                'type'           => 'sales',
                'status'         => 'posted',
                'created_by'     => auth()->id() ?? $invoice->created_by,
            ]);

            $entry->items()->create([
                'account_id'   => $arAccount->id,
                'debit_amount' => $invoice->total_amount,
                'credit_amount'=> 0,
                'description'  => "Accounts Receivable - Invoice #{$invoice->invoice_number}",
            ]);

            $entry->items()->create([
                'account_id'   => $revenueAccount->id,
                'debit_amount' => 0,
                'credit_amount'=> $invoice->total_amount,
                'description'  => "Project Revenue - Invoice #{$invoice->invoice_number}",
            ]);

            return $entry;
        });
    }

    /**
     * Post GL entry for an Approved Expense / Bill
     * Debit: Expense Account
     * Credit: Accounts Payable (AP) / Cash / Bank
     */
    public function postExpense(Expense $expense): ?JournalEntry
    {
        $expenseAccount = ChartOfAccount::where('account_type', 'expense')
            ->first() ?? ChartOfAccount::firstOrCreate(
                ['account_code' => '5000'],
                ['name' => 'Operational Expenses', 'account_type' => 'expense', 'is_active' => true]
            );

        $apAccount = ChartOfAccount::where('account_type', 'liability')
            ->where(function ($q) {
                $q->where('name', 'like', '%Payable%')
                  ->orWhere('account_code', 'like', '2100%');
            })->first() ?? ChartOfAccount::firstOrCreate(
                ['account_code' => '2100'],
                ['name' => 'Accounts Payable', 'account_type' => 'liability', 'is_active' => true]
            );

        $existing = JournalEntry::where('description', 'like', "%Expense Ref: {$expense->reference_no}%")->first();
        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($expense, $expenseAccount, $apAccount) {
            $nextSeq = str_pad(JournalEntry::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
            $entry = JournalEntry::create([
                'journal_number' => 'JV-' . date('Ymd') . '-' . $nextSeq,
                'date'           => $expense->expense_date ?? now()->toDateString(),
                'description'    => "Auto-posting for Expense Ref: {$expense->reference_no} - {$expense->title}",
                'type'           => 'purchase',
                'status'         => 'posted',
                'created_by'     => auth()->id() ?? $expense->created_by,
            ]);

            $entry->items()->create([
                'account_id'   => $expenseAccount->id,
                'debit_amount' => $expense->amount,
                'credit_amount'=> 0,
                'description'  => "Expense - Ref: {$expense->reference_no}",
            ]);

            $entry->items()->create([
                'account_id'   => $apAccount->id,
                'debit_amount' => 0,
                'credit_amount'=> $expense->amount,
                'description'  => "Accounts Payable - Ref: {$expense->reference_no}",
            ]);

            return $entry;
        });
    }
}
