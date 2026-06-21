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
│   ├── Finance/       → budgets, boqs, tenders, invoices, ipas, bills, rate-analysis, chart-of-accounts, journal-entries, general-ledger, trial-balance, receivables, bank-guarantees, balance-sheet, income-statement, labour-entries, aging, cost-overrun-alerts, material-takeoffs
│   ├── Procurement/   → vendors, materials, purchase-requisitions, purchase-orders, goods-received-notes, warehouses, stocks, material-transfers, material-issue-slips, material-wastages, subcontractors
│   ├── Hr/            → employees, attendance, timesheets, wage-slips, equipment, leave-requests, training-records, ppe-issuances, incident-reports, certifications, hse-checklists, fuel-logs, toolbox-talks
│   ├── Reports/       → financial, report-templates, scheduled-reports
│   ├── ApprovalController, CategoryController, DashboardController, RoleController, SettingController
├── Imports/BoqItemsImport.php         # Excel BOQ import with validation
├── Models/           → 86 Eloquent models (see below)
├── Traits/Approvable.php             # Polymorphic approval trait
├── Services/
│   ├── ApprovalService.php            # Multi-level approval workflow engine
│   ├── CostOverrunService.php         # Budget threshold monitoring
│   └── FinancialReportService.php     # Financial reporting logic

```

---

## Models (86 total)

| Domain | Models |
|---|---|
| **Auth/System** | User, Role, Category, Setting |
| **Core** | Project, Site, Task, Phase, Milestone, WorkOrder, InspectionChecklist, InspectionChecklistItem, SiteLog, SitePhoto, ProjectResource, TaskResource |
| **Finance** | Budget, Boq, BoqItem, Invoice, InvoiceItem, Payment, InterimPaymentApplication, IpaItem, Bill, BillItem, BillPayment, RateAnalysis, RateAnalysisItem, CostOverrunAlert, ChartOfAccount, JournalEntry, JournalEntryItem, Receivable, ReceivablePayment, BankGuarantee, LabourEntry, MaterialTakeoff |
| **Procurement** | Vendor, Material, PurchaseRequisition, PurchaseRequisitionItem, PurchaseOrder, PurchaseOrderItem, GoodsReceivedNote, GoodsReceivedNoteItem, Warehouse, Stock, MaterialTransfer, MaterialTransferItem, MaterialIssueSlip, MaterialIssueSlipItem, MaterialWastage, Subcontractor |
| **HR** | Employee, Attendance, Timesheet, WageSlip, Equipment, EquipmentMaintenance, LeaveRequest, TrainingRecord, PpeIssuance, IncidentReport, Certification, HseChecklist, HseChecklistItem, FuelLog, ToolboxTalk |
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

boqs → boq_items (1:N), material_takeoffs (per-project material quantities)

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
employees → timesheets (per-project hours logged)
employees → wage_slips (monthly pay calculation from attendance)

equipment → equipment_maintenance (preventive/corrective/inspection tracking)

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

### Earned Value Management (EVM)
- Budget model has `planned_value` (PV), `earned_value` (EV), `actual_cost` (AC)
- Computed accessors: `spi` (EV/PV), `cpi` (EV/AC), `etc` ((BAC-EV)/CPI), `eac` (AC+ETC), `variance` (BAC-AC)
- Dedicated forecasting view with summary cards and per-budget SPI/CPI/ETC/EAC table

### Super-Admin Gate
- `AuthServiceProvider`: `Gate::before` allows super-admins to bypass all checks

### Settings System
- Key-value `settings` table + global `get_setting()` helper

### BOQ Import
- `BoqItemsImport` (Maatwebsite Excel): chunked reading, validation, error reporting

### Material Takeoff Sheets
- `MaterialTakeoff` model: per-project material quantities linked to BOQ items
- Fields: description, unit, quantity, unit_price, total_price, source_drawing
- Full CRUD with project filter, linked to BOQ items for estimating takeoffs

### Project Progress
- `Project::progress` accessor averages all tasks' `progress_percent`
- Shown as progress bars on project index + show pages (color-coded)

### Task Dependencies
- `task_dependencies` pivot table links Task → Task (N:M)
- `Task::dependencies()` — tasks this task depends on
- `Task::dependentTasks()` — tasks that depend on this task
- Multi-select UI on create/edit forms; shown as link lists on show page

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

### HR — Attendance & Timesheets
- `Attendance` with `clock_in`/`clock_out` timestamps + status (present/absent/late/half-day/holiday)
- Bulk daily register via create form with select-all status buttons + time inputs
- Monthly summary per employee: counts by status + total worked hours
- `Timesheet` entries: employee logs hours against a project with start/end time and description
- Filterable timesheet list by employee, project, and date range

### HR — Wage Slips
- `WageSlip` auto-generated from attendance data per month
- Daily-rate calculation: `basic_salary / 30` × worked days (present + half-day*0.5 + late*0.75)
- Overtime from timesheets: hours > 8/day × 1.5× hourly rate
- 10% allowances, 5% deductions
- Print-optimized view with `window.print()`

### HR — Equipment & Assets
- `Equipment` registry: code, make/model, serial, acquisition type (owned/hired), purchase cost, depreciation tracking
- **Hire rates & period**: hire_rate, rate_period (daily/weekly/monthly), hire_start/end_date, hire_vendor; shown in Financial panel for hired equipment
- **Allocation to projects/sites**: FK columns + allocation/deallocation dates, filterable in list, selectable on create/edit, shown on show page
- Meter/hour tracking with maintenance interval and next-due alert
- `EquipmentMaintenance` records: preventive/corrective/inspection with cost, vendor, and next due date
- Inline maintenance history on equipment show page with quick meter update

### HR — Training & Certifications
- `TrainingRecord`: tracks employee training with provider, dates, certificate no, expiry, and cost
- Status workflow: planned → in-progress → completed → expired
- Filterable list by employee and status

### HR — PPE Issuance
- `PpeIssuance`: issue/return tracking for personal protective equipment per employee
- Fields: item name, category, quantity, size, condition on issue/return, return date
- Filterable by employee, category, and returned status

### HR — Safety & Compliance
- `IncidentReport`: accident/incident tracking with type, severity, location, description
- `Certification`: employee certification & licence tracking with issuing authority, certificate no, expiry, renewal reminders
- Categories: certification, license, permit; statuses: active, expired, suspended, revoked
- `HseChecklist` / `HseChecklistItem`: HSE-specific checklists with compliance items per type (general, fire, electrical, scaffolding, PPE, excavation)
- Checklist items tracked as compliant/non-compliant, with findings and corrective actions
- Types: accident, near-miss, injury, property-damage, fire, other
- Severity: minor → moderate → serious → critical → fatal
- Status workflow: open → under-investigation → closed
- Rich detail view with root cause, corrective actions, investigation notes

### HR — Fuel Consumption Logs
- `FuelLog`: tracks equipment fuel consumption with fuel type (diesel/petrol/gas/other), quantity, unit (liters/gallons), unit cost, auto-calculated total cost
- Fields: equipment, date, meter hours, vendor, receipt no, notes
- Filterable by equipment and fuel type

### HR — Toolbox Talk Records
- `ToolboxTalk`: safety briefing records with conductor (employee), topic, date, duration, location
- Attendees stored as free-text; includes discussion points and action items
- Filterable by conductor and date range

---

## Routes

| File | Description |
|---|---|
| `routes/web.php` (644 lines) | All app routes: `/dashboard`, `/dashboard/settings|categories|roles`, `/dashboard/core/*`, `/dashboard/procurement/*`, `/dashboard/hr/*`, `/dashboard/reports/*`, `/dashboard/finance/*`, `/dashboard/approvals/*` |
| `routes/api.php` | Single Sanctum `/api/user` endpoint |
| `routes/console.php` | Artisan commands |

---

## Views (`resources/views/admin/`)

```
layouts/   → master, header, sidebar, footer, main, scripts
core/      → projects, sites, tasks, phases, milestones, work-orders, inspection-checklists, site-logs, site-photos, project-resources
finance/   → budgets, boqs, tenders, invoices, ipas, bills, rate-analysis, chart-of-accounts, journal-entries, general-ledger, trial-balance, receivables, bank-guarantees, balance-sheet, income-statement, labour-entries, aging, cost-overrun-alerts
procurement/ → vendors, materials, requisitions, purchase-orders, goods-received-notes, warehouses, stocks, material-transfers, material-issue-slips, material-wastages, subcontractors
hr/        → employees, attendance, timesheets, wage-slips, equipment, leaves, training-records, ppe-issuances, incident-reports, certifications, hse-checklists, fuel-logs, toolbox-talks
            (sidebar categorized: People, Payroll, Equipment & Assets, Safety & Compliance, Training)
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
| 4 | HR | HR & Payroll (People, Payroll, Equipment & Assets, Safety & Compliance, Training) |
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
