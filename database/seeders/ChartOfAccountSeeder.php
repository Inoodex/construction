<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // Assets (Debit)
            ['account_code' => '1-1000', 'name' => 'Current Assets', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => null],
            ['account_code' => '1-1010', 'name' => 'Cash & Bank', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['account_code' => '1-1020', 'name' => 'Accounts Receivable', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['account_code' => '1-1030', 'name' => 'Inventory - Materials', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['account_code' => '1-1040', 'name' => 'Work in Progress', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['account_code' => '1-2000', 'name' => 'Fixed Assets', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => null],

            // Liabilities (Credit)
            ['account_code' => '2-1000', 'name' => 'Current Liabilities', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => null],
            ['account_code' => '2-1010', 'name' => 'Accounts Payable', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2-1000'],
            ['account_code' => '2-1020', 'name' => 'Accrued Expenses', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2-1000'],

            // Equity (Credit)
            ['account_code' => '3-1000', 'name' => "Owner's Equity", 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => null],
            ['account_code' => '3-1010', 'name' => 'Capital', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => '3-1000'],
            ['account_code' => '3-1020', 'name' => 'Retained Earnings', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => '3-1000'],

            // Income (Credit)
            ['account_code' => '4-1000', 'name' => 'Revenue', 'type' => 'income', 'normal_balance' => 'credit', 'parent_code' => null],
            ['account_code' => '4-1010', 'name' => 'Contract Revenue', 'type' => 'income', 'normal_balance' => 'credit', 'parent_code' => '4-1000'],

            // Expenses (Debit)
            ['account_code' => '5-1000', 'name' => 'Direct Costs', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => null],
            ['account_code' => '5-1010', 'name' => 'Material Costs', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],
            ['account_code' => '5-1020', 'name' => 'Labour Costs', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],
            ['account_code' => '5-1030', 'name' => 'Subcontractor Costs', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],
            ['account_code' => '5-1040', 'name' => 'Equipment Costs', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],
            ['account_code' => '5-2000', 'name' => 'Overhead', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => null],
        ];

        // First pass: create parent accounts (no parent_code)
        $created = [];
        foreach ($accounts as $acc) {
            if ($acc['parent_code'] === null) {
                $created[$acc['account_code']] = ChartOfAccount::create([
                    'account_code' => $acc['account_code'],
                    'name' => $acc['name'],
                    'type' => $acc['type'],
                    'normal_balance' => $acc['normal_balance'],
                    'parent_id' => null,
                    'is_active' => true,
                ]);
            }
        }

        // Second pass: create child accounts
        foreach ($accounts as $acc) {
            if ($acc['parent_code'] !== null) {
                $parent = $created[$acc['parent_code']] ?? null;
                if ($parent) {
                    ChartOfAccount::create([
                        'account_code' => $acc['account_code'],
                        'name' => $acc['name'],
                        'type' => $acc['type'],
                        'normal_balance' => $acc['normal_balance'],
                        'parent_id' => $parent->id,
                        'is_active' => true,
                    ]);
                }
            }
        }

        $this->command->info('Chart of Accounts seeded successfully.');
    }
}
