<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Trade Categories
            ['type' => 'trade_category', 'value' => 'Electrical', 'label' => 'Electrical', 'sort_order' => 1],
            ['type' => 'trade_category', 'value' => 'Plumbing', 'label' => 'Plumbing', 'sort_order' => 2],
            ['type' => 'trade_category', 'value' => 'Structural', 'label' => 'Structural', 'sort_order' => 3],
            ['type' => 'trade_category', 'value' => 'Finishing', 'label' => 'Finishing', 'sort_order' => 4],
            ['type' => 'trade_category', 'value' => 'HVAC', 'label' => 'HVAC', 'sort_order' => 5],
            ['type' => 'trade_category', 'value' => 'General', 'label' => 'General', 'sort_order' => 6],
            ['type' => 'trade_category', 'value' => 'Steel', 'label' => 'Steel', 'sort_order' => 7],
            ['type' => 'trade_category', 'value' => 'Cement', 'label' => 'Cement', 'sort_order' => 8],
            ['type' => 'trade_category', 'value' => 'Bricks', 'label' => 'Bricks', 'sort_order' => 9],
            ['type' => 'trade_category', 'value' => 'Sand', 'label' => 'Sand', 'sort_order' => 10],

            // Resource Types
            ['type' => 'resource_type', 'value' => 'labor', 'label' => 'Labor', 'sort_order' => 1],
            ['type' => 'resource_type', 'value' => 'labour', 'label' => 'Labour', 'sort_order' => 2],
            ['type' => 'resource_type', 'value' => 'equipment', 'label' => 'Equipment', 'sort_order' => 3],
            ['type' => 'resource_type', 'value' => 'material', 'label' => 'Material', 'sort_order' => 4],
            ['type' => 'resource_type', 'value' => 'subcontract', 'label' => 'Subcontract', 'sort_order' => 5],
            ['type' => 'resource_type', 'value' => 'overhead', 'label' => 'Overhead', 'sort_order' => 6],
            // Expense Types
            ['type' => 'expense_type', 'value' => 'office_rent', 'label' => 'Office Rent', 'sort_order' => 1],
            ['type' => 'expense_type', 'value' => 'utilities', 'label' => 'Utilities', 'sort_order' => 2],
            ['type' => 'expense_type', 'value' => 'office_supplies', 'label' => 'Office Supplies', 'sort_order' => 3],
            ['type' => 'expense_type', 'value' => 'travel', 'label' => 'Travel & Transport', 'sort_order' => 4],
            ['type' => 'expense_type', 'value' => 'maintenance', 'label' => 'Maintenance & Repairs', 'sort_order' => 5],
            ['type' => 'expense_type', 'value' => 'communication', 'label' => 'Communication', 'sort_order' => 6],
            ['type' => 'expense_type', 'value' => 'marketing', 'label' => 'Marketing & Advertising', 'sort_order' => 7],
            ['type' => 'expense_type', 'value' => 'it_software', 'label' => 'IT & Software', 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['type' => $cat['type'], 'value' => $cat['value']],
                $cat
            );
        }
    }
}
