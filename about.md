# Construction ERP — Project Overview

**Stack:** Laravel 12 (PHP 8.2+), Blade + Tailwind CSS v4 + Alpine.js, Vite 7
**Admin Panel:** `hasinhayder/tyro-dashboard` v1.7 + `hasinhayder/tyro-login`
**DB:** SQLite (default) / MySQL
**Exports:** PDF (DOMPDF), Excel (Maatwebsite/Laravel-Excel)
**Files:** Spatie MediaLibrary
**Auth:** Sanctum (API), Tyro Login (OTP/2FA/social)

---

## Directory Structure

```
app/
├── Exports/ReportExport.php           # Excel report export
├── Helpers/settings.php               # get_setting() helper
├── Http/Controllers/Admin/           # All controllers by domain
│   ├── Core/          → projects, sites, tasks, phases, milestones, work-orders, inspection-checklists, site-logs, site-photos, project-resources
│   ├── Finance/       → budgets, boqs, tenders, invoices, ipas, bills, rate-analysis, chart-of-accounts, journal-entries, general-ledger, trial-balance, receivables, bank-guarantees, balance-sheet, income-statement, labour-entries, aging, cost-overrun-alerts
│   ├── Procurement/   → vendors, materials, purchase-requisitions, purchase-orders, goods-received-notes, warehouses, stocks, material-transfers, material-issue-slips, material-wastages, subcontractors
│   ├── Hr/            → employees, attendance, leave-requests
│   ├── Reports/       → financial, report-templates, scheduled-reports
│   ├── ApprovalController, CategoryController, DashboardController, RoleController, SettingController
├── Imports/BoqItemsImport.php         # Excel BOQ import with validation
├── Models/           → 65 Eloquent models (see below)
├── Traits/Approvable.php             # Polymorphic approval trait
├── Services/
│   ├── ApprovalService.php            # Multi-level approval workflow engine
│   ├── CostOverrunService.php         # Budget threshold monitoring
│   └── FinancialReportService.php     # Financial reporting logic

```

---

## Models (64 total)

| Domain | Models |
|---|---|
| **Auth/System** | User, Role, Category, Setting |
| **Core** | Project, Site, Task, Phase, Milestone, WorkOrder, InspectionChecklist, InspectionChecklistItem, SiteLog, SitePhoto, ProjectResource, TaskResource |
| **Finance** | Budget, Boq, BoqItem, Invoice, InvoiceItem, Payment, InterimPaymentApplication, IpaItem, Bill, BillItem, BillPayment, RateAnalysis, RateAnalysisItem, CostOverrunAlert, ChartOfAccount, JournalEntry, JournalEntryItem, Receivable, ReceivablePayment, BankGuarantee, LabourEntry |
| **Procurement** | Vendor, Material, PurchaseRequisition, PurchaseRequisitionItem, PurchaseOrder, PurchaseOrderItem, GoodsReceivedNote, GoodsReceivedNoteItem, Warehouse, Stock, MaterialTransfer, MaterialTransferItem, MaterialIssueSlip, MaterialIssueSlipItem, MaterialWastage, Subcontractor |
| **HR** | Employee, Attendance, LeaveRequest |
| **Approvals** | Approval, ApprovalHistory, ApprovalMatrix, ApprovalWorkflow |
| **Reports** | ReportTemplate, ScheduledReport |

---

## Database Schema (key tables & relationships)

```
projects → sites (1:N)
projects → tasks (1:N), task_dependencies (N:M pivot)
project_resources → task_resources → tasks (resource allocation pivot)
projects → phases → milestones (1:N)
projects → budgets (1:N)

boqs → boq_items (1:N)

purchase_requisitions → purchase_requisition_items
purchase_orders → purchase_order_items
goods_received_notes → goods_received_note_items
goods_received_notes.site_id → sites (delivery site tracking)
PR → PO → GRN (procurement flow)

vendors (polymorphic: applies to multiple entities)
warehouses → stocks → material_transfers → material_transfer_items
material_issue_slips → material_issue_slip_items
material_wastages

chart_of_accounts (tree/hierarchy)
journal_entries → journal_entry_items (double-entry)
invoices → invoice_items → payments
bills → bill_items → bill_payments
interim_payment_applications → ipa_items (retention tracking)
receivables → receivable_payments

bank_guarantees
labour_entries (per-project labour cost)

employees → attendance, leave_requests

site_logs, site_photos (field reporting)
inspection_checklists → inspection_checklist_items
work_orders

approval_workflows → approval_matrices
approvals (polymorphic: morphs to approvable models)
approval_history

report_templates, scheduled_reports
```

---

## Architecture Patterns

### Approval Workflow System
- `Approvable` trait on models (PurchaseRequisition, PurchaseOrder, Invoice, Tender, Budget)
- `ApprovalWorkflow` → `ApprovalMatrix` (role + level + amount thresholds)
- `ApprovalService`: submit → approve/reject → level progression
- Full history in `approval_histories`

### Cost Overrun Detection
- `CostOverrunService`: checks budget utilization vs thresholds
- 80% = warning, 100% = danger, 120% = critical
- Creates/updates `CostOverrunAlert` with severity levels

### Super-Admin Gate
- `AuthServiceProvider`: `Gate::before` allows super-admins to bypass all checks

### Settings System
- Key-value `settings` table + global `get_setting()` helper

### BOQ Import
- `BoqItemsImport` (Maatwebsite Excel): chunked reading, validation, error reporting

### Project Progress
- `Project::progress` accessor averages all tasks' `progress_percent`
- Shown as progress bars on project index + show pages (color-coded)

### Site Material Delivery Tracking
- `goods_received_notes.site_id` FK → sites (nullable, tracks delivery destination)
- `delivery_note` / `vehicle_number` fields on GRN for logistics tracking
- GRN create form: site selection filtered by PO's project, vehicle + delivery note fields
- GRN index: site column + site filter
- GRN show: delivery site, vehicle, delivery note displayed
- Site show: "Material Deliveries" table showing all GRNs delivered to that site

### Weather API Integration
- `WeatherService` (Open-Meteo): geocodes site address → lat/lng → current `temperature_2m` + `weather_code`
- "Fetch Weather" button on site log create/edit forms; calls `GET /dashboard/core/weather?location=...`
- Route: `admin.core.weather.fetch`, controller: `SiteLogController::fetchWeather`
- Free, no API key required; uses WMO weather code → human-readable description

### Resource Planning
- `task_resources` pivot table links `ProjectResource` → `Task` with allocated qty + dates
- `TaskResource` model: `task()`, `projectResource()` BelongsTo
- `Task::resources()`, `ProjectResource::taskAllocations()`, `ProjectResource::allocated_quantity` / `pending_quantity` accessors
- Resource allocation UI on Task create/edit (dynamic per-project resource picker)
- Project resource index shows allocation bar + linked tasks
- Resource Allocation Chart: Gantt timeline view at `projects/{id}/resource-gantt` + global picker at `resource-gantt`
- Sidebar/Horizontal: Core → Execution → Allocation Chart

---

## Routes

| File | Description |
|---|---|
| `routes/web.php` (485 lines) | All app routes: `/dashboard`, `/dashboard/settings|categories|roles`, `/dashboard/core/*`, `/dashboard/procurement/*`, `/dashboard/hr/*`, `/dashboard/reports/*`, `/dashboard/finance/*`, `/dashboard/approvals/*` |
| `routes/api.php` | Single Sanctum `/api/user` endpoint |
| `routes/console.php` | Artisan commands |

---

## Views (`resources/views/admin/`)

```
layouts/   → master, header, sidebar, footer, main, scripts
core/      → projects, sites, tasks, phases, milestones, work-orders, inspection-checklists, site-logs, site-photos, project-resources
finance/   → budgets, boqs, tenders, invoices, ipas, bills, rate-analysis, chart-of-accounts, journal-entries, general-ledger, trial-balance, receivables, bank-guarantees, balance-sheet, income-statement, labour-entries, aging, cost-overrun-alerts
procurement/ → vendors, materials, requisitions, purchase-orders, goods-received-notes, warehouses, stocks, material-transfers, material-issue-slips, material-wastages, subcontractors
hr/        → employees, attendance, leaves
reports/   → financial, report-templates, scheduled-reports
approvals/ → index, show, workflows
settings/  → index
dashboard.blade.php, index.blade.php
```

---

## Modules (22 total, 20 Must-Have)

| # | Group | Modules |
|---|---|---|
| 1 | Core | Project Management, Task & Work Orders, Site Management |
| 2 | Finance | Budgeting & Cost Control, Estimating & BOQ, Tender Management, Invoicing & AR, Accounts Payable, General Accounting, Bank Guarantees |
| 3 | Procurement | Vendor Management, Procurement (PR/PO/GRN), Inventory & Warehouse, Subcontractor Management |
| 4 | HR | HR & Payroll, Equipment & Assets, Safety & Compliance |
| 5 | Quality | Quality Control/QA, Risk Management |
| 6 | Client | CRM & Clients, Document Management, Contract Management |
| 7 | System | Settings & Configuration, Reports & Analytics |

---

## Key Dependencies

| Package | Purpose |
|---|---|
| `laravel/framework: ^12.0` | Framework |
| `hasinhayder/tyro-dashboard: ^1.7` | Admin panel, roles, CRUD builder |
| `hasinhayder/tyro-login` | Auth pages (OTP/2FA/social/captcha) |
| `maatwebsite/excel: ^3.1` | Excel import/export |
| `barryvdh/laravel-dompdf` | PDF generation |
| `spatie/laravel-medialibrary: ^11.22` | File/media management |
| `laravel/sanctum: ^4.0` | API tokens |
| `tailwindcss: ^4.0` | CSS framework |
| `alpinejs` | (loaded via CDN/assets) JS interactivity |
