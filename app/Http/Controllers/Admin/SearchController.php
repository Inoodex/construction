<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        if ($request->has('suggest')) {
            return response()->json($this->getMenuItems());
        }

        $query = strtolower(trim($request->q ?? ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $items = $this->getMenuItems();
        $results = [];

        foreach ($items as $item) {
            if (str_contains(strtolower($item['label']), $query) || str_contains(strtolower($item['section']), $query)) {
                $results[] = $item;
            }
        }

        return response()->json($results);
    }

    private function getMenuItems(): array
    {
        $r = function ($name) {
            return Route::has($name) ? route($name) : '#';
        };

        return [
            // Core Operations
            ['label' => 'Projects', 'url' => $r('admin.core.projects.index'), 'section' => 'Core Operations'],
            ['label' => 'Sites & Field Locations', 'url' => $r('admin.core.sites.index'), 'section' => 'Core Operations'],
            ['label' => 'Site Logs', 'url' => $r('admin.core.site-logs.index'), 'section' => 'Core Operations'],
            ['label' => 'Site Photos', 'url' => $r('admin.core.site-photos.index'), 'section' => 'Core Operations'],
            ['label' => 'Tasks & Activities', 'url' => $r('admin.core.tasks.index'), 'section' => 'Core Operations'],
            ['label' => 'Project Phases', 'url' => $r('admin.core.phases.index'), 'section' => 'Core Operations'],
            ['label' => 'Project Milestones', 'url' => $r('admin.core.milestones.index'), 'section' => 'Core Operations'],
            ['label' => 'Resource Directory', 'url' => $r('admin.core.resources.index'), 'section' => 'Core Operations'],
            ['label' => 'Resource Allocation Chart (Gantt)', 'url' => $r('admin.core.resource-gantt.index'), 'section' => 'Core Operations'],
            ['label' => 'Work Orders', 'url' => $r('admin.core.work-orders.index'), 'section' => 'Core Operations'],
            ['label' => 'Execution Inspections', 'url' => $r('admin.core.inspection-checklists.index'), 'section' => 'Core Operations'],

            // Engineering & Estimating
            ['label' => 'Bill of Quantities (BOQ)', 'url' => $r('admin.finance.boqs.index'), 'section' => 'Engineering & Estimating'],
            ['label' => 'Rate Analysis', 'url' => $r('admin.finance.rate-analysis.index'), 'section' => 'Engineering & Estimating'],
            ['label' => 'Tenders & Bids', 'url' => $r('admin.finance.tenders.index'), 'section' => 'Engineering & Estimating'],
            ['label' => 'Rod Calculations (BBS)', 'url' => $r('admin.finance.rod-calculations.index'), 'section' => 'Engineering & Estimating'],
            ['label' => 'Concrete Ratios (Mix Design)', 'url' => $r('admin.finance.concrete-ratios.index'), 'section' => 'Engineering & Estimating'],

            // Documents & Contracts
            ['label' => 'Drawings & Blueprints', 'url' => $r('admin.core.documents.drawings.index'), 'section' => 'Documents & Contracts'],
            ['label' => 'Request for Information (RFI)', 'url' => $r('admin.core.documents.rfis.index'), 'section' => 'Documents & Contracts'],
            ['label' => 'Change Orders', 'url' => $r('admin.core.documents.change-orders.index'), 'section' => 'Documents & Contracts'],
            ['label' => 'Document Transmittals', 'url' => $r('admin.core.documents.transmittals.index'), 'section' => 'Documents & Contracts'],
            ['label' => 'Contracts', 'url' => $r('admin.core.contracts.index'), 'section' => 'Documents & Contracts'],
            ['label' => 'Contract Amendments', 'url' => $r('admin.core.contract-amendments.index'), 'section' => 'Documents & Contracts'],
            ['label' => 'Contract Claims', 'url' => $r('admin.core.contract-claims.index'), 'section' => 'Documents & Contracts'],
            ['label' => 'Contract Closeout Checklists', 'url' => $r('admin.core.contract-closeout.index'), 'section' => 'Documents & Contracts'],

            // Procurement & Inventory
            ['label' => 'Vendors & Suppliers', 'url' => $r('admin.procurement.vendors.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Materials Directory', 'url' => $r('admin.procurement.materials.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Material Submittals', 'url' => $r('admin.procurement.material-submittals.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Warehouses & Stores', 'url' => $r('admin.procurement.warehouses.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Request for Quotations (RFQ)', 'url' => $r('admin.procurement.rfqs.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Purchase Requisitions', 'url' => $r('admin.procurement.requisitions.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Purchase Orders (PO)', 'url' => $r('admin.procurement.purchase-orders.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Goods Received Notes (GRN)', 'url' => $r('admin.procurement.goods-received-notes.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Inventory Stocks', 'url' => $r('admin.procurement.stocks.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Material Transfers', 'url' => $r('admin.procurement.material-transfers.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Material Issue Slips', 'url' => $r('admin.procurement.material-issue-slips.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Material Wastage Logs', 'url' => $r('admin.procurement.material-wastages.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Material Reconciliation', 'url' => $r('admin.procurement.material-reconciliation.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Subcontractors List', 'url' => $r('admin.procurement.subcontractors.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Subcontract Agreements', 'url' => $r('admin.procurement.subcontract-agreements.index'), 'section' => 'Procurement & Inventory'],
            ['label' => 'Subcontract Progress Payments', 'url' => $r('admin.procurement.subcontract-progress-payments.index'), 'section' => 'Procurement & Inventory'],

            // HR & Safety
            ['label' => 'Employees & Staff', 'url' => $r('admin.hr.employees.index'), 'section' => 'HR & Safety'],
            ['label' => 'Daily Attendance Register', 'url' => $r('admin.hr.attendance.index'), 'section' => 'HR & Safety'],
            ['label' => 'Monthly Attendance Summary', 'url' => $r('admin.hr.attendance.summary'), 'section' => 'HR & Safety'],
            ['label' => 'Employee Timesheets', 'url' => $r('admin.hr.timesheets.index'), 'section' => 'HR & Safety'],
            ['label' => 'Leave Requests', 'url' => $r('admin.hr.leaves.index'), 'section' => 'HR & Safety'],
            ['label' => 'Payroll Wage Slips', 'url' => $r('admin.hr.wage-slips.index'), 'section' => 'HR & Safety'],
            ['label' => 'Equipment Fleet', 'url' => $r('admin.hr.equipment.index'), 'section' => 'HR & Safety'],
            ['label' => 'Equipment Fuel Logs', 'url' => $r('admin.hr.fuel-logs.index'), 'section' => 'HR & Safety'],
            ['label' => 'PPE Issuance Logs', 'url' => $r('admin.hr.ppe-issuances.index'), 'section' => 'HR & Safety'],
            ['label' => 'Safety Incident Reports', 'url' => $r('admin.hr.incident-reports.index'), 'section' => 'HR & Safety'],
            ['label' => 'HSE Checklists', 'url' => $r('admin.hr.hse-checklists.index'), 'section' => 'HR & Safety'],
            ['label' => 'Toolbox Talks', 'url' => $r('admin.hr.toolbox-talks.index'), 'section' => 'HR & Safety'],
            ['label' => 'Permits to Work (PTW)', 'url' => $r('admin.hr.permits-to-work.index'), 'section' => 'HR & Safety'],
            ['label' => 'Safety Audits', 'url' => $r('admin.hr.safety-audits.index'), 'section' => 'HR & Safety'],
            ['label' => 'Training Records', 'url' => $r('admin.hr.training-records.index'), 'section' => 'HR & Safety'],
            ['label' => 'Certifications & Licences', 'url' => $r('admin.hr.certifications.index'), 'section' => 'HR & Safety'],

            // CRM
            ['label' => 'CRM Leads', 'url' => $r('admin.crm.leads.index'), 'section' => 'CRM'],
            ['label' => 'Clients & Customers', 'url' => $r('admin.crm.clients.index'), 'section' => 'CRM'],
            ['label' => 'Commercial Proposals', 'url' => $r('admin.crm.proposals.index'), 'section' => 'CRM'],

            // Quality Control
            ['label' => 'Non-Conformance Reports (NCR)', 'url' => $r('admin.quality.ncrs.index'), 'section' => 'Quality Control'],
            ['label' => 'Corrective Action Requests (CAR)', 'url' => $r('admin.quality.corrective-actions.index'), 'section' => 'Quality Control'],
            ['label' => 'Inspection & Test Plans (ITP)', 'url' => $r('admin.quality.itps.index'), 'section' => 'Quality Control'],
            ['label' => 'Punch Lists & Snagging', 'url' => $r('admin.quality.punch-lists.index'), 'section' => 'Quality Control'],
            ['label' => 'Material Test Certificates', 'url' => $r('admin.quality.material-test-certificates.index'), 'section' => 'Quality Control'],
            ['label' => 'Project Risk Register', 'url' => $r('admin.quality.risks.index'), 'section' => 'Quality Control'],

            // Finance & Accounting
            ['label' => 'Sales Invoices', 'url' => $r('admin.finance.invoices.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Interim Payment Applications (IPA)', 'url' => $r('admin.finance.ipas.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Bills Payable (AP)', 'url' => $r('admin.finance.bills.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Expense Vouchers', 'url' => $r('admin.finance.expenses.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Project Budgets', 'url' => $r('admin.finance.budgets.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Budget Forecasting', 'url' => $r('admin.finance.budgets.forecasting'), 'section' => 'Finance & Accounting'],
            ['label' => 'Cost Overrun Alerts', 'url' => $r('admin.finance.cost-overrun-alerts.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Labour Cost Entries', 'url' => $r('admin.finance.labour-entries.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Material Takeoffs (MTO)', 'url' => $r('admin.finance.material-takeoffs.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Chart of Accounts (COA)', 'url' => $r('admin.finance.chart-of-accounts.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Journal Vouchers', 'url' => $r('admin.finance.journal-entries.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'General Ledger', 'url' => $r('admin.finance.general-ledger.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Trial Balance', 'url' => $r('admin.finance.trial-balance.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Bank Reconciliation', 'url' => $r('admin.finance.reconciliation.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Accounts Receivable (AR)', 'url' => $r('admin.finance.receivables.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Bank Guarantees', 'url' => $r('admin.finance.bank-guarantees.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Balance Sheet', 'url' => $r('admin.finance.balance-sheet.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Income Statement (P&L)', 'url' => $r('admin.finance.income-statement.index'), 'section' => 'Finance & Accounting'],
            ['label' => 'Payment Accounts', 'url' => $r('admin.finance.payment-accounts.index'), 'section' => 'Finance & Accounting'],

            // Reports & Analytics
            ['label' => 'Budget vs Actual Report', 'url' => $r('admin.reports.financial.budget-vs-actual'), 'section' => 'Reports & Analytics'],
            ['label' => 'Project Cost Summary Report', 'url' => $r('admin.reports.financial.project-cost-summary'), 'section' => 'Reports & Analytics'],
            ['label' => 'Invoice Status Report', 'url' => $r('admin.reports.financial.invoice-status'), 'section' => 'Reports & Analytics'],
            ['label' => 'Cash Flow Statement', 'url' => $r('admin.reports.financial.cash-flow'), 'section' => 'Reports & Analytics'],
            ['label' => 'Retention Tracker Report', 'url' => $r('admin.reports.financial.retention-tracker'), 'section' => 'Reports & Analytics'],
            ['label' => 'Progress S-Curve Report', 'url' => $r('admin.reports.financial.progress-schedule'), 'section' => 'Reports & Analytics'],
            ['label' => 'Labour & Equipment Utilisation', 'url' => $r('admin.reports.financial.resource-utilisation'), 'section' => 'Reports & Analytics'],
            ['label' => 'Procurement Spend Analysis', 'url' => $r('admin.reports.financial.procurement-spend'), 'section' => 'Reports & Analytics'],
            ['label' => 'Accounts Receivable (AR) Aging', 'url' => $r('admin.finance.aging.ar'), 'section' => 'Reports & Analytics'],
            ['label' => 'Accounts Payable (AP) Aging', 'url' => $r('admin.finance.aging.ap'), 'section' => 'Reports & Analytics'],

            // System Administration
            ['label' => 'User Management', 'url' => $r('tyro-dashboard.users.index'), 'section' => 'System Administration'],
            ['label' => 'Role Management', 'url' => $r('tyro-dashboard.roles.index'), 'section' => 'System Administration'],
            ['label' => 'Privilege Management', 'url' => $r('tyro-dashboard.privileges.index'), 'section' => 'System Administration'],
            ['label' => 'System Settings', 'url' => $r('admin.settings.index'), 'section' => 'System Administration'],
            ['label' => 'Categories & Taxonomies', 'url' => $r('admin.categories.index'), 'section' => 'System Administration'],
        ];
    }
}
