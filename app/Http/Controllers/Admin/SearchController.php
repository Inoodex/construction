<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        if ($request->has('suggest')) {
            return response()->json($this->getMenuItems());
        }

        $query = strtolower(trim($request->q ?? ''));

        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $items = $this->getMenuItems();
        $results = [];

        foreach ($items as $item) {
            if (str_contains(strtolower($item['label']), $query)) {
                $results[] = $item;
            }
        }

        return response()->json($results);
    }

    private function getMenuItems(): array
    {
        $r = fn($name) => route($name);

        return [
            // Administration
            ['label' => 'Users', 'url' => $r('tyro-dashboard.users.index'), 'section' => 'Administration'],
            ['label' => 'Roles', 'url' => $r('tyro-dashboard.roles.index'), 'section' => 'Administration'],
            ['label' => 'Privileges', 'url' => $r('tyro-dashboard.privileges.index'), 'section' => 'Administration'],
            ['label' => 'Settings', 'url' => $r('admin.settings.index'), 'section' => 'Administration'],
            ['label' => 'Categories', 'url' => $r('admin.categories.index'), 'section' => 'Administration'],

            // Core
            ['label' => 'Projects', 'url' => $r('admin.core.projects.index'), 'section' => 'Core'],
            ['label' => 'Sites', 'url' => $r('admin.core.sites.index'), 'section' => 'Core'],
            ['label' => 'Site Logs', 'url' => $r('admin.core.site-logs.index'), 'section' => 'Core'],
            ['label' => 'Site Photos', 'url' => $r('admin.core.site-photos.index'), 'section' => 'Core'],
            ['label' => 'Tasks', 'url' => $r('admin.core.tasks.index'), 'section' => 'Core'],
            ['label' => 'Phases', 'url' => $r('admin.core.phases.index'), 'section' => 'Core'],
            ['label' => 'Milestones', 'url' => $r('admin.core.milestones.index'), 'section' => 'Core'],
            ['label' => 'Resources', 'url' => $r('admin.core.resources.index'), 'section' => 'Core'],
            ['label' => 'Allocation Chart', 'url' => $r('admin.core.resource-gantt.index'), 'section' => 'Core'],
            ['label' => 'Work Orders', 'url' => $r('admin.core.work-orders.index'), 'section' => 'Core'],
            ['label' => 'Inspections', 'url' => $r('admin.core.inspection-checklists.index'), 'section' => 'Core'],

            // Procurement
            ['label' => 'Vendors', 'url' => $r('admin.procurement.vendors.index'), 'section' => 'Procurement'],
            ['label' => 'Materials', 'url' => $r('admin.procurement.materials.index'), 'section' => 'Procurement'],
            ['label' => 'Material Submittals', 'url' => $r('admin.procurement.material-submittals.index'), 'section' => 'Procurement'],
            ['label' => 'Warehouses', 'url' => $r('admin.procurement.warehouses.index'), 'section' => 'Procurement'],
            ['label' => 'Request for Quotation', 'url' => $r('admin.procurement.rfqs.index'), 'section' => 'Procurement'],
            ['label' => 'Purchase Requisitions', 'url' => $r('admin.procurement.requisitions.index'), 'section' => 'Procurement'],
            ['label' => 'Purchase Orders', 'url' => $r('admin.procurement.purchase-orders.index'), 'section' => 'Procurement'],
            ['label' => 'Goods Received', 'url' => $r('admin.procurement.goods-received-notes.index'), 'section' => 'Procurement'],
            ['label' => 'Stocks', 'url' => $r('admin.procurement.stocks.index'), 'section' => 'Procurement'],
            ['label' => 'Material Transfers', 'url' => $r('admin.procurement.material-transfers.index'), 'section' => 'Procurement'],
            ['label' => 'Issue Slips', 'url' => $r('admin.procurement.material-issue-slips.index'), 'section' => 'Procurement'],
            ['label' => 'Material Wastage', 'url' => $r('admin.procurement.material-wastages.index'), 'section' => 'Procurement'],
            ['label' => 'Material Reconciliation', 'url' => $r('admin.procurement.material-reconciliation.index'), 'section' => 'Procurement'],
            ['label' => 'Subcontractors', 'url' => $r('admin.procurement.subcontractors.index'), 'section' => 'Procurement'],
            ['label' => 'Subcontract Agreements', 'url' => $r('admin.procurement.subcontract-agreements.index'), 'section' => 'Procurement'],
            ['label' => 'Progress Payments', 'url' => $r('admin.procurement.subcontract-progress-payments.index'), 'section' => 'Procurement'],

            // HR
            ['label' => 'Employees', 'url' => $r('admin.hr.employees.index'), 'section' => 'HR'],
            ['label' => 'Daily Register', 'url' => $r('admin.hr.attendance.index'), 'section' => 'HR'],
            ['label' => 'Monthly Summary', 'url' => $r('admin.hr.attendance.summary'), 'section' => 'HR'],
            ['label' => 'Timesheets', 'url' => $r('admin.hr.timesheets.index'), 'section' => 'HR'],
            ['label' => 'Leave Requests', 'url' => $r('admin.hr.leaves.index'), 'section' => 'HR'],
            ['label' => 'Wage Slips', 'url' => $r('admin.hr.wage-slips.index'), 'section' => 'HR'],
            ['label' => 'Equipment', 'url' => $r('admin.hr.equipment.index'), 'section' => 'HR'],
            ['label' => 'Fuel Logs', 'url' => $r('admin.hr.fuel-logs.index'), 'section' => 'HR'],
            ['label' => 'PPE Issuance', 'url' => $r('admin.hr.ppe-issuances.index'), 'section' => 'HR'],
            ['label' => 'Incident Reports', 'url' => $r('admin.hr.incident-reports.index'), 'section' => 'HR'],
            ['label' => 'HSE Checklists', 'url' => $r('admin.hr.hse-checklists.index'), 'section' => 'HR'],
            ['label' => 'Training Records', 'url' => $r('admin.hr.training-records.index'), 'section' => 'HR'],
            ['label' => 'Certifications & Licences', 'url' => $r('admin.hr.certifications.index'), 'section' => 'HR'],

            // Finance
            ['label' => 'Budgets', 'url' => $r('admin.finance.budgets.index'), 'section' => 'Finance'],
            ['label' => 'Budget Forecasting', 'url' => $r('admin.finance.budgets.forecasting'), 'section' => 'Finance'],
            ['label' => 'Cost Alerts', 'url' => $r('admin.finance.cost-overrun-alerts.index'), 'section' => 'Finance'],
            ['label' => 'Labour Cost', 'url' => $r('admin.finance.labour-entries.index'), 'section' => 'Finance'],
            ['label' => 'Chart of Accounts', 'url' => $r('admin.finance.chart-of-accounts.index'), 'section' => 'Finance'],
            ['label' => 'Journal Vouchers', 'url' => $r('admin.finance.journal-entries.index'), 'section' => 'Finance'],
            ['label' => 'General Ledger', 'url' => $r('admin.finance.general-ledger.index'), 'section' => 'Finance'],
            ['label' => 'Trial Balance', 'url' => $r('admin.finance.trial-balance.index'), 'section' => 'Finance'],
            ['label' => 'Accounts Receivable', 'url' => $r('admin.finance.receivables.index'), 'section' => 'Finance'],
            ['label' => 'Balance Sheet', 'url' => $r('admin.finance.balance-sheet.index'), 'section' => 'Finance'],
            ['label' => 'Income Statement', 'url' => $r('admin.finance.income-statement.index'), 'section' => 'Finance'],
            ['label' => 'Bill of Quantities', 'url' => $r('admin.finance.boqs.index'), 'section' => 'Finance'],
            ['label' => 'Rate Analysis', 'url' => $r('admin.finance.rate-analysis.index'), 'section' => 'Finance'],
            ['label' => 'Invoices', 'url' => $r('admin.finance.invoices.index'), 'section' => 'Finance'],
            ['label' => 'Interim Payment Applications', 'url' => $r('admin.finance.ipas.index'), 'section' => 'Finance'],
            ['label' => 'Bills Payable', 'url' => $r('admin.finance.bills.index'), 'section' => 'Finance'],
            ['label' => 'Expenses', 'url' => $r('admin.finance.expenses.index'), 'section' => 'Finance'],

            // CRM
            ['label' => 'Leads', 'url' => $r('admin.crm.leads.index'), 'section' => 'CRM'],
            ['label' => 'Clients', 'url' => $r('admin.crm.clients.index'), 'section' => 'CRM'],
            ['label' => 'Proposals', 'url' => $r('admin.crm.proposals.index'), 'section' => 'CRM'],

            // Reports
            ['label' => 'Budget vs Actual', 'url' => $r('admin.reports.financial.budget-vs-actual'), 'section' => 'Reports'],
            ['label' => 'Project Cost Summary', 'url' => $r('admin.reports.financial.project-cost-summary'), 'section' => 'Reports'],
            ['label' => 'Invoice Status', 'url' => $r('admin.reports.financial.invoice-status'), 'section' => 'Reports'],
            ['label' => 'Cash Flow', 'url' => $r('admin.reports.financial.cash-flow'), 'section' => 'Reports'],
            ['label' => 'Retention Tracker', 'url' => $r('admin.reports.financial.retention-tracker'), 'section' => 'Reports'],
            ['label' => 'Progress S-Curve', 'url' => $r('admin.reports.financial.progress-schedule'), 'section' => 'Reports'],
            ['label' => 'Labour & Equipment Utilisation', 'url' => $r('admin.reports.financial.resource-utilisation'), 'section' => 'Reports'],
            ['label' => 'Procurement Spend', 'url' => $r('admin.reports.financial.procurement-spend'), 'section' => 'Reports'],
            ['label' => 'Amount Receivable Aging', 'url' => $r('admin.finance.aging.ar'), 'section' => 'Reports'],
            ['label' => 'Amount Payable Aging', 'url' => $r('admin.finance.aging.ap'), 'section' => 'Reports'],
        ];
    }
}
