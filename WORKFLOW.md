# Construction ERP — System Workflow

> Describes how the system works: architecture, data flows, module interactions, and current build status.

---

## 1. Authentication & Access Control

```
User → Login (email/password or OTP/2FA) → Role-based authorization
                                              ↓
                                     Super-admin gate bypasses all permission checks
```

- **Sanctum** for API auth (token-based)
- **Tyro Login** for web auth (OTP/2FA/social/captcha/magic links)
- **Roles & Permissions** via Tyro Dashboard — each route/menu item gated by permission
- **Super-admin** (`Gate::before` in `AppServiceProvider`) — auto-allowed for everything
- **Client role** — scoped to own projects, invoices, and documents only; create/edit/delete blocked via `abort(403)`

### Roles

| Role | Description |
|------|-------------|
| `super-admin` | Bypasses ALL permission checks. Configures approval workflows, settings, roles. |
| `admin` | Full admin access (user/role/privilege/settings management) |
| `user` | Default role assigned to new users |
| `client` | Limited view — own projects, invoices, drawings, RFIs, transmittals only |

### Permission Check Flow

```
User requests protected resource
        |
   Gate::before() in AppServiceProvider
        |
   +--- super-admin? -----> GRANTED (bypass all)
   +--- hasPrivilege()? --> GRANTED (Tyro privilege match)
   +--- hasRole()? -------> GRANTED (Tyro role name match)
   +--- null -------------> falls through to other gates/policies
```

---

## 2. Core Project Module — Hierarchy

```
Project
  ├── Client (optional — links to CRM client for portal access)
  ├── Phases (ordered, e.g. Foundation → Superstructure → Finishing)
  │     └── Milestones (key deliverables with target dates)
  ├── Sites (physical locations — 1:N, each with address)
  ├── Tasks (individual work items)
  │     ├── Dependencies (N:M pivot — task blocks another task)
  │     ├── Resources (allocated via task_resources pivot)
  │     └── Progress % (manually updated, averaged into Project::progress)
  ├── Project Resources (labour/equipment allocation with dates)
  └── Budgets (cost control per project)
```

**Data flow:** Project created → Sites added → Phases/Milestones defined → Tasks created with dependencies → Resources allocated → Progress updated → Project progress bar auto-calculates.

### Work Orders & Inspections

```
Work Order (created against project/site)
  ├── Status: draft → issued → in_progress → completed
  └── Print-friendly view

Inspection Checklist (created against project/site)
  ├── Items (checklist items with pass/fail status)
  └── Status: pending → in_progress → completed
```

### 2.1 Project Resource Planning & Task Allocations

```
Project
  └── Project Resource Pool (labour, equipment, specialized manpower)
        ├── resource_type (labour | equipment | material_handling | specialized)
        ├── name, unit (person / unit / machine), total_qty, cost_per_unit
        └── Task Allocations (via task_resources pivot)
              ├── Task ID
              ├── allocated_qty
              ├── start_date, end_date
              └── Status: allocated → active → released
```

**Data Flow & Timeline Visualization:**
1. **Resource Registration:** Register project resources under `Core → Execution → Resources` (`/dashboard/core/project-resources`).
2. **Task Allocation:** Allocate registered resources to specific tasks during task creation/edit (`task_resources` pivot).
3. **Allocation Chart (Resource Gantt):** View project-wide or task-level resource utilization timeline at `Core → Execution → Allocation Chart` (`/dashboard/core/resource-gantt`). Tracks allocated vs available quantities over time to prevent over-allocation.

---

## 3. Document Management Module

### 3.1 Drawing Register & Revision Control

```
Drawing (created per project)
  ├── drawing_number (auto-generated: DRW-{PROJECT}-{SEQ})
  ├── title, type (architectural/structural/mep/shop/as_built/other)
  ├── status: draft → issued → superseded → obsolete
  └── Revisions (1:N)
        ├── revision number (P, I, or numeric)
        ├── revision_date, description
        ├── File upload (Spatie MediaLibrary — 'drawing_file' collection)
        └── is_current flag (only one revision marked current)
```

**Client access:** Can view drawings for their own projects (read-only). Cannot create/edit/delete.

### 3.2 RFIs (Request for Information)

```
RFI (created per project)
  ├── rfi_number (auto-generated: RFI-{PROJECT}-{SEQ})
  ├── subject, question, priority (low/medium/high)
  ├── Linked drawing (optional)
  ├── assigned_to, due_date
  └── Answer workflow:
        ├── status: open → answered → closed
        ├── answer text + answered_by + answered_date
        └── File attachment (Spatie MediaLibrary — 'attachment' collection)
```

**Client access:** Can view RFIs for their own projects. Cannot create/edit.

### 3.3 Change Orders / Variations

```
Change Order (created per project)
  ├── change_order_number (auto-generated: CO-{PROJECT}-{SEQ})
  ├── type: variation | change_order | extension
  ├── status: draft → submitted → under_review → approved/rejected → implemented
  ├── cost_impact, time_impact_days
  ├── Linked RFI (optional)
  └── Approve / Reject workflow (admin only)
        ├── approve: sets approved_by + approved_date
        └── reject: clears approved_by + approved_date
```

**Client access:** Can view change orders for their own projects. Cannot create/edit/approve/reject.

### 3.4 Drawing Transmittals

```
Transmittal (created per project)
  ├── transmittal_number (auto-generated: TRM-{PROJECT}-{SEQ})
  ├── to_party, from_user, sent_date
  ├── purpose: for_approval | for_information | for_construction | as_built
  ├── status: draft → sent → acknowledged
  └── Items (1:N) — dynamic form (add/remove rows)
        ├── drawing (required)
        ├── drawing revision (optional)
        └── copies (default: 1)
```

**Client access:** Can view transmittals for their own projects. Cannot create/edit.

---

## 4. Procurement Flow

### 4.1 PR → PO → GRN

```
PR (Purchase Requisition)
  Site identifies need → PR created (draft)
           ↓
  Approval Workflow (if configured)
           ↓
  PR approved → status = approved
           ↓
PO (Purchase Order)
  PR approved → PO created against vendor (auto-populates items from PR)
           ↓
  Approval Workflow (if configured)
           ↓
  PO approved → status = ordered
           ↓
GRN (Goods Received Note)
  Goods arrive at site/warehouse → GRN created against PO
  Tracks: quantity received, delivery note, vehicle, site
           ↓
  Stock Updated — Warehouse inventory increases automatically
```

**Key rules:**
- PR status must be `approved` before it can be used in a PO
- PO can be created from PR (auto-populates items) or as direct order
- GRN can be partial (receives less than PO quantity)
- Material transfers move stock between warehouses/sites
- Material issue slips record consumption against a project
- Material wastage tracks losses (cut-off scraps, damage, theft)

### 4.2 RFQ & Quotation Flow

```
RFQ (Request for Quotation)
  ├── Select vendors → Send RFQ
  ├── Vendors submit quotations (price per item)
  ├── Compare quotations → Award to best vendor
  └── Award generates Purchase Requisition automatically
```

### 4.3 Material Submittals

```
Material Submittal
  ├── Submit material specification for approval
  ├── Status: draft → submitted → under_review → approved/rejected/resubmitted
  ├── Resubmit with corrections if rejected
  └── Linked to project
```

### 4.4 Inventory & Warehouse

```
Stock management per warehouse:
  ├── Stock levels tracked (min_stock, reorder_level)
  ├── Material Transfers (site-to-site, with status workflow)
  ├── Material Issue Slips (warehouse → project consumption)
  ├── Material Wastage (loss tracking with reason)
  └── Material Reconciliation (expected vs actual stock comparison)
```

### 4.5 Subcontractor Management

```
Subcontractor (vendor profile for subcontractors)
  ├── Subcontract Agreement (scope, contract value, dates)
  │     ├── Status: draft → active → completed / terminated
  │     └── Linked to project + vendor
  └── Progress Payments
        ├── Against agreement, with retention
        └── Status: draft → submitted → approved → paid
```

---

## 5. Finance Flow

### 5.1 Invoicing & Payments

```
Invoice (draft)
  ├── Items added (description, qty, unit_price → total_price)
  ├── Tax & retention auto-calculated
  └── Status: draft → sent → partially_paid → paid
       ↓
  Payment received → paid_amount updated → due_amount recalculated
```

### 5.2 Interim Payment Application (IPA)

```
IPA (draft)
  ├── Links to BOQ items (tracks previous vs current quantities)
  ├── Applied amount → Certified amount (engineer may adjust)
  ├── Retention deducted → Net amount calculated
  └── Status: draft → submitted → certified → approved → invoice_generated
       ↓
  Generate Invoice button (creates invoice from IPA data)
```

### 5.3 Budgeting & Cost Control

```
Budget (per project)
  ├── Allocated amount per cost code
  ├── EVM fields: PV, EV, AC → SPI, CPI, ETC, EAC
  ├── Forecasting view (projected costs over time)
  └── Cost Overrun Alerts
        ├── 80% budget used → warning
        ├── 100% → danger
        ├── 120% → critical alert
        └── Acknowledge / resolve workflow
```

### 5.4 BOQ & Rate Analysis

```
BOQ (Bill of Quantities)
  ├── Per project, with line items (description, qty, unit, rate, amount)
  ├── Excel import supported
  └── Used by IPA for progress tracking

Rate Analysis
  ├── Unit rate breakdown (material + labour + equipment + overhead)
  └── Used for estimating and BOQ pricing
```

### 5.5 Accounting

```
Chart of Accounts (tree hierarchy, e.g. 1-1010 Cash & Bank)
         ↓
Journal Entry (date, description, reference)
  ├── Journal Entry Items (account_id, debit/credit amounts)
  └── Auto-balancing: debits must equal credits
         ↓
General Ledger (all journal entries grouped by account)
         ↓
Trial Balance (verifies debits = credits)
         ↓
Financial Reports:
  ├── Balance Sheet (assets = liabilities + equity)
  ├── Income Statement (revenue − expenses = net income)
  └── Cash Flow (journal entries to Cash & Bank accounts)
```

### 5.6 Bills & Expenses

```
Bill (vendor payable)
  ├── Items (material/service, qty, unit_price)
  ├── Tax, retention, TDS calculated
  └── Bill Payment recorded → paid_amount updated → due recalculated

Expense (operational expenses)
  ├── Category, amount, date, project
  └── Mark as paid workflow
```

### 5.7 Receivables & Bank Guarantees

```
Receivable
  ├── Client, project, amount, due date
  └── Payment tracking (partial payments)

Bank Guarantee
  ├── Type, amount, issuing bank, validity
  ├── Optional link to Contract (contract_id FK)
  └── Status: active → released / expired
```

---

## 5A. Contract Management

### 5A.1 Main Contract Repository

```
Contract (main/prime contract between company and client)
  ├── contract_number (auto-generated: CTR-{Ymd}-{0001})
  ├── project_id, client_name, contract_type (main/subcontract/supply/consultancy)
  ├── contract_value, currency, signing/commencement/completion dates
  ├── retention_percentage, liquidated_damages_rate, advance_payment_percentage
  ├── extended_completion_date (updated via amendments)
  └── Status: draft → active → suspended → completed / terminated
```

**Computed values:**
- `revisedContractValue()` = original + approved amendments
- `totalAmendmentsValue()` = sum of approved amendment cost impacts
- `closeoutChecklistComplete()` = all closeout items checked

### 5A.2 Contract Amendments / Variations

```
ContractAmendment (formal change to a contract)
  ├── amendment_number (auto-generated: AMD-{Ymd}-{0001})
  ├── contract_id FK, title, description
  ├── type: scope_change | time_extension | value_change | other
  ├── cost_impact (+/-), time_impact_days (+/-)
  └── Status: draft → submitted → approved / rejected
```

### 5A.3 Claims Management

```
ContractClaim (time extension / cost compensation claim)
  ├── claim_number (auto-generated: CLM-{Ymd}-{0001})
  ├── contract_id FK, title, description
  ├── type: time_extension | cost_compensation | both
  ├── claimed_amount, claimed_days
  ├── granted_amount, granted_days (set during review)
  └── Status: draft → submitted → under_review → granted / partially_granted / rejected
```

### 5A.4 Contract Closeout Checklist

```
ContractCloseoutItem (per contract)
  ├── contract_id FK, item, description
  ├── is_completed, completed_date, completed_by
  └── order_index (for sorting)
```

**All items checked → contract eligible for completion status.**

### 5A.5 Linked Entities

```
Bank Guarantees ──→ optional contract_id FK
Change Orders ───→ optional contract_id FK
```

---

## 6. HR & Equipment Flow

### 6.1 People Management

```
Employee created (profile, salary, department, designation)
         ↓
Attendance recorded daily (bulk register: clock_in/clock_out, status)
         ↓
Timesheets logged (employee × project × hours)
         ↓
Monthly Wage Slip generated:
  ├── Basic salary / 30 × worked days
  ├── Overtime (× 1.5 hourly rate for hours > 8/day)
  ├── 10% allowance, 5% deduction
  └── Net pay calculated
```

### 6.2 Leave Management

```
Leave Request (draft)
  ├── Type: Annual / Sick / Emergency
  ├── Date range + reason
  └── Status: pending → approved / rejected
```

### 6.3 Equipment & Assets

```
Equipment Registry (owned or hired)
  ├── Owned: purchase cost, depreciation tracking
  ├── Hired: hire rate, period, vendor, start/end dates
  ├── Allocation to project/site
  ├── Fuel consumption logs (equipment × date × qty × cost)
  └── Maintenance records (preventive/corrective, cost, next due)
```

### 6.4 Safety & Compliance

```
HSE Checklists (per project/site)
  ├── Checklist items (pass/fail observations)
  └── Linked to project + site

Incident Reports
  ├── Severity, location, description, root cause
  └── Corrective actions tracked

Toolbox Talks (daily safety briefings)
  ├── Topic, attendees, date
  └── Record of who attended

Permit to Work (PTW)
  ├── Types: hot work, confined space, working at height, electrical, excavation, lifting, radiography
  ├── Auto-generated permit numbers (PTW-YYYYMMDD-XXXX)
  ├── Validity period (valid_from → valid_until)
  ├── Status: draft → pending_approval → approved → active → completed/cancelled
  ├── Required: hazards identified, safety measures, work description
  └── Linked to project + site

Safety Audits
  ├── Types: internal, external, regulatory, client
  ├── Auto-generated audit numbers (SA-YYYYMMDD-XXXX)
  ├── Score (0-100)
  ├── Findings, non-conformances, recommendations
  ├── Status: scheduled → in_progress → completed → follow_up
  └── Linked to project + site

PPE Issuances
  ├── Employee, PPE type, issue date, condition
  └── Return tracking

Fuel Logs
  ├── Equipment, date, litres, cost, odometer
  └── Linked to equipment registry

Training Records & Certifications
  ├── Training type, date, provider
  └── Certification expiry tracking with alerts
```

---

## 7. Quality Control Flow

```
ITP (Inspection & Test Plan)
  ├── Per project, with checklist items
  ├── Each item: description, acceptance criteria, status
  └── Status: draft → active → completed

NCR (Non-Conformance Report)
  ├── Per project, with description, severity
  ├── Status: open → under_review → resolved → closed
  └── Linked to ITP item (optional)

Corrective Action (CAR)
  ├── Linked to NCR
  ├── Root cause, action plan, responsible person
  └── Status: open → in_progress → completed → verified

Punch List (Snagging)
  ├── Per project, with items
  ├── Each item: description, location, severity, status
  └── Status: open → in_progress → completed

Material Test Certificates
  ├── Material, test type, result, certificate number
  └── Expiry tracking
```

---

## 8. CRM & Client Portal Flow

### 8.1 Lead Management

```
Lead (potential client)
  ├── Company, contact, estimated value, source
  ├── Status: new → contacted → proposal_sent → negotiation → won / lost
  └── Convert to Client (creates Client record + links user)
```

### 8.2 Client Management

```
Client (linked to user account for portal access)
  ├── Contacts (multiple per client)
  ├── Documents (uploaded via Spatie MediaLibrary)
  ├── Communication Logs (calls, emails, meetings)
  └── Proposals
        ├── Items with amounts
        └── Status: draft → sent → accepted / rejected
```

### 8.3 Client Portal

Clients log in and see a limited sidebar:
- **My Projects** — projects linked to their client_id
- **My Invoices** — invoices for their projects
- **Document Management** — drawings, RFIs, transmittals for their projects (read-only)

---

## 9. Approval Workflow

```
Approvable Model (PR / PO / Invoice / Tender / Budget)
         ↓
ApprovalService::submitForApproval(model, module_type, amount, user)
         ↓
Matches ApprovalWorkflow → ApprovalMatrix (role × level × amount threshold)
         ↓
Approval created (pending, current_level = 1)
         ↓
Approvers at Level 1 notified → Approve / Reject
         ↓
If approved → Level 2 → ... → final level → model status = approved
If rejected → model status updated, workflow stops
Any level can withdraw
```

**Configurable in:** Settings → Approval Workflows (module type, number of levels, role-based matrix with amount thresholds)

### Sidebar Visibility

- **Pending Approvals** — visible to all authenticated users
- **Approval Workflows** — visible only to users with `super-admin` role

---

## 10. Reporting Flow

```
Data sources (all modules)
         ↓
FinancialReportService
  ├── Budget vs Actual (budget amounts vs journal entry totals by cost code)
  ├── Cash Flow (journal entries to Cash & Bank — debits = in, credits = out)
  ├── Invoice Status (all invoices grouped by status)
  ├── Progress Schedule / S-Curve (task progress % over time per project)
  ├── Procurement Spend (PO totals by date range)
  ├── Project Cost Summary (cost breakdown by project)
  ├── Resource Utilisation (labour/equipment usage)
  └── Retention Tracker (retention withheld vs released)
         ↓
Export: PDF (DomPDF) / Excel (Maatwebsite)

```

---

## 11. Module Interaction Diagram

```
                    ┌─────────────┐
                    │   Projects  │◄─────── Core
                    └──────┬──────┘
                           │
        ┌──────────────────┼──────────────────┐
        ▼                  ▼                  ▼
  ┌──────────┐     ┌──────────────┐     ┌──────────┐
  │ Budgets  │     │    Tasks     │     │  Sites   │
  │ (Finance)│     │  (Core)      │     │  (Core)  │
  └────┬─────┘     └──────┬───────┘     └────┬─────┘
       │                  │                  │
       ▼                  ▼                  ▼
  ┌──────────┐     ┌──────────────┐     ┌──────────┐
  │ Invoices │     │  PR → PO     │     │   GRN    │
  │(Finance) │     │ → GRN        │     │  → Stock │
  └────┬─────┘     │ (Procurement)│     └──────────┘
       │           └──────────────┘
       ▼                  │
  ┌──────────┐            ▼
  │Payments │     ┌──────────────┐
  │(Finance)│     │   Vendors    │
  └──────────┘     │(Procurement)│
                   └──────────────┘

  ┌──────────┐     ┌──────────────┐
  │Employees │────►│  Attendance  │
  │   (HR)  │     │  Timesheets  │
  └──────────┘     │  Wage Slips  │
                   └──────────────┘

  ┌──────────┐     ┌──────────────┐     ┌──────────────┐
  │ Drawings │────►│  Revisions   │     │     RFIs     │
  │  (Docs)  │     │   (Docs)     │     │   (Docs)     │
  └──────────┘     └──────────────┘     └──────┬───────┘
                                               │
                                         ┌─────▼──────┐
                                         │Change Orders│
                                         │   (Docs)    │
                                         └─────────────┘

  ┌──────────┐     ┌──────────────┐
  │ Journal  │────►│    Trial     │────► Financial
  │ Entries  │     │   Balance    │     Reports
  └──────────┘     └──────────────┘
```

---

## 12. Key Business Rules

| Rule | Description |
|------|-------------|
| **Double-entry** | Every journal entry must have debits = credits |
| **Approval gate** | PR/PO cannot proceed to next step without approval if workflow configured |
| **Partial receipt** | GRN can receive less than PO quantity; remaining balance tracked |
| **Auto-status** | Invoice status auto-updates based on paid_amount vs total_amount |
| **Retention** | % withheld from each IPA payment, tracked until project closeout |
| **Wage calc** | Daily rate = basic / 30; overtime = 1.5× hourly for hours > 8/day |
| **Cost overrun** | 80% budget used → warning, 100% → danger, 120% → critical alert |
| **Progress rollup** | Task progress averaged → Phase progress averaged → Project progress |
| **Super-admin bypass** | Super-admin role bypasses all permission checks via Gate::before |
| **EVM** | SPI = EV/PV, CPI = EV/AC, ETC = (BAC-EV)/CPI, EAC = AC+ETC |
| **Client scoping** | Client users only see their own projects/invoices/documents |
| **Number generation** | Auto-generated: DRW-{PRJ}-{SEQ}, RFI-{PRJ}-{SEQ}, CO-{PRJ}-{SEQ}, TRM-{PRJ}-{SEQ}, CTR-{Ymd}-{0001}, AMD-{Ymd}-{0001}, CLM-{Ymd}-{0001} |
| **Drawing revision control** | Only one revision marked as `is_current` at a time |
| **File uploads** | Spatie MediaLibrary for drawings, RFIs, change orders, vendor docs, client docs |
| **Contract value** | Revised contract value = original + approved amendments |
| **Contract closeout** | Contract status → completed only if all closeout checklist items are checked |
| **Claims workflow** | Claims go through draft → submitted → under_review → granted/rejected; granted amounts count toward total claims value |

---

## 13. Sidebar Structure

```
Client Portal (client role only)
  ├── My Projects
  └── My Invoices

Administration (super-admin only)
  ├── Users, Roles, Privileges, Settings, Categories

Core
  ├── Projects
  ├── Sites (All Sites, Site Logs, Site Photos)
  ├── Planning (Tasks, Phases, Milestones)
  └── Execution (Resources, Allocation Chart, Work Orders, Inspections)

Document Management
  ├── Drawings
  ├── RFIs
  ├── Change Orders
  └── Transmittals

Procurement
  ├── Reference Data (Vendors, Materials, Material Submittals, Warehouses)
  ├── Procurement (RFQs, Purchase Requisitions, Purchase Orders, Goods Received)
  ├── Inventory (Stocks, Material Transfers, Issue Slips, Wastage, Reconciliation)
  └── Subcontractors (All Subcontractors, Agreements, Progress Payments)

HR & Payroll
  ├── People (All Employees, Daily Register, Monthly Summary, Timesheets, Leave Requests)
  ├── Payroll (Wage Slips)
  ├── Equipment & Assets (Equipment, Fuel Logs, PPE Issuance)
  ├── Safety & Compliance (Incident Reports, HSE Checklists, Toolbox Talks, Permits to Work, Safety Audits)
  └── Training (Training Records, Certifications & Licences)

CRM
  ├── Leads, Clients, Proposals

Quality Control
  ├── Non-Conformance (NCRs, Corrective Actions)
  ├── Inspections (ITPs, Punch Lists)
  ├── Material Test Certificates
  └── Risk Register

Contract Management
  ├── Contracts (main repository)
  ├── Amendments (contract variations)
  ├── Claims (time/cost compensation)
  └── Closeout Checklists

Finance
  ├── Cost Control (Budgets, Forecasting, Cost Alerts, Labour Cost)
  ├── Accounting (Chart of Accounts, Journal Vouchers, General Ledger, Trial Balance, AR, Balance Sheet, Income Statement)
  ├── Estimating Analysis (BOQs, Rate Analysis, Tenders)
  └── Billing & Payables (Invoices, Interim Payment, Bills Payable, Expenses)

Reports
  ├── Custom Reports (Report Templates, Scheduled Reports)
  ├── Cost & Budgeting (Budget vs Actual, Project Cost Summary)
  ├── Financial Status (Invoice Status, Cash Flow, Retention Tracker)
  ├── Progress Reports (S-Curve, Labour & Equipment, Procurement Spend)
  └── Aging Reports (AR Aging, AP Aging)
```

---

## 14. Technology Stack

| Layer | Technology |
|-------|------------|
| Framework | Laravel 12 (PHP 8.2+) |
| Frontend | Blade + Tailwind CSS v4 + Alpine.js |
| Build | Vite 7 |
| Admin Panel | Tyro Dashboard v1.7 + Tyro Login |
| Database | MySQL |
| PDF | barryvdh/laravel-dompdf |
| Excel | Maatwebsite/Laravel-Excel |
| Files | Spatie MediaLibrary |
| Auth | Sanctum (API), Tyro Login (web) |

---

## 15. Statistics

| Category | Count |
|----------|-------|
| Controllers | 82 |
| Models | 114 |
| Migrations | 119 |
| Blade View Directories | 93 |
| Route Groups | 9 major groups |
| Sidebar Sections | 9 (Admin, Core, Documents, Procurement, HR, CRM, Quality, Contract Management, Finance, Reports) |

---

_Last updated: 2026-07-28 — after Tender Management, Safety & Compliance, and Reports & Analytics completion. All 22 modules from MODULES.md now complete._

---

## 16. Manual Testing Guide — Real-World Test Data & Validation

> This section provides a structured manual testing workflow with production-grade test data for every module. Testers can input these values into the system forms to validate all core features end-to-end.

### 16.1 Testing Workflow Overview

```
For each module:
0. **First configure System Settings (Section 16.16)** — tax rates, retention, currency, and company info must be set before entering financial data to ensure correct auto-calculations.
1. Navigate to the module's Create/New form via the sidebar
2. Enter the test data provided below into the form fields
3. Submit and verify the record appears in the Index/List view
4. Click into the Show/Detail view to verify all data displays correctly
5. Edit the record and change a few fields → submit → verify updates
6. Delete the record or test status transitions (if applicable)
   > ⚠️ **Cascade note:** Records with dependent child data may fail to delete due to foreign key constraints. Delete child records first (e.g., delete tasks before project), or test status changes (e.g., "Cancelled"/"Closed") instead of deletion for parent records.
7. Check PDF/Excel export (if available) for formatting accuracy
8. Verify any cross-module links (e.g., PR → PO → GRN chain)
```

**General Validation Criteria:**
- ✓ Record appears in index list after creation
- ✓ All field values display correctly in Show view
- ✓ Edit form pre-fills with existing data
- ✓ Updates persist after edit submission
- ✓ Delete/status-change actions work without errors
- ✓ Numeric calculations are accurate (totals, subtotals, tax)
- ✓ Date fields display and format correctly
- ✓ Dropdown/select options contain expected values
- ✓ Search/filter functionality returns relevant results
- ✓ PDF/Excel exports (if applicable) render correctly

---

### 16.2 Core — Project, Site, Task & Work Order Modules

#### Test Data Set: City Centre Commercial Tower (Project #1)

**1. Project (Create)
**
| Field | Value |
|---|---|
| Name | City Centre Commercial Tower |
| Description | 25-storey mixed-use commercial tower with 5-level basement parking, retail podium, and office tower. Located at 42 Gulshan Avenue, Dhaka. |
| Budget | 850,000,000.00 |
| Start Date | 2026-01-15 |
| End Date | 2028-06-30 |
| Status | Active |

**Validation:** ✓ Project appears on dashboard KPI cards. ✓ Progress bar shows 0%. ✓ Edit/Delete buttons functional.

**2. Phases (Create under Project #1 — ordered sequence)**

| Name | Start Date | End Date | Status | Order |
|---|---|---|---|---|
| Foundation & Piling | 2026-01-15 | 2026-06-30 | Completed | 1 |
| Basement & Substructure | 2026-04-01 | 2026-09-30 | In Progress | 2 |
| Superstructure (Floors 1-12) | 2026-07-01 | 2027-06-30 | Planning | 3 |
| Superstructure (Floors 13-25) | 2027-01-01 | 2027-12-31 | Planning | 4 |
| Finishing & MEP | 2027-06-01 | 2028-04-30 | Planning | 5 |
| Handover & Closeout | 2028-01-01 | 2028-06-30 | Planning | 6 |

**Validation:** ✓ Phases list ordered correctly. ✓ Nested under Project show page.

**3. Milestones (Create under Project #1)**

| Name | Phase | Target Date | Status |
|---|---|---|---|
| Piling Complete — 150 Bored Cast-in-Situ Piles | Foundation & Piling | 2026-04-15 | Achieved (2026-04-10) |
| Basement Excavation Complete | Basement & Substructure | 2026-07-30 | In Progress |
| Ground Floor Slab Cast | Basement & Substructure | 2026-09-15 | Pending |
| Structural Topping Out (Floor 25) | Superstructure (Floors 13-25) | 2027-09-30 | Pending |
| All MEP Commissioning Complete | Finishing & MEP | 2028-03-30 | Pending |
| Project Handover | Handover & Closeout | 2028-06-15 | Pending |

**Validation:** ✓ Milestones filterable by phase. ✓ Achieved date displayed when status = Achieved.

**4. Sites (Create under Project #1)**

| Name | Location Address | Status |
|---|---|---|
| Main Tower Site | 42 Gulshan Avenue, Gulshan-1, Dhaka-1212 | Active |
| Material Storage Yard | Plot 15, Badda Link Road, Dhaka-1212 | Active |
| Labour Camp Site | 7/A Pragati Sarani, Baridhara, Dhaka-1212 | Active |

**Validation:** ✓ Site appears under Project show page. ✓ Address displayed correctly.

**5. Tasks (Create under Project #1)**

| Name | Site | Phase | Priority | Status | Start | End | Assigned To | Progress % |
|---|---|---|---|---|---|---|---|---|
| Bored Pile Installation (P1-P50) | Main Tower Site | Foundation & Piling | High | In Progress | 2026-01-20 | 2026-03-30 | (select user) | 75 |
| Pile Cap Construction | Main Tower Site | Foundation & Piling | High | Open | 2026-04-01 | 2026-05-15 | (select user) | 0 |
| Basement Earthwork — 25,000 m³ Excavation | Main Tower Site | Basement & Substructure | High | Open | 2026-05-01 | 2026-07-30 | (select user) | 0 |
| Column & Wall Reinforcement — B1 Level | Main Tower Site | Basement & Substructure | Medium | Open | 2026-06-15 | 2026-08-15 | (select user) | 0 |
| Concrete Pour — B1 Level Slab | Main Tower Site | Basement & Substructure | Critical | On Hold | 2026-08-01 | 2026-09-10 | (select user) | 0 |

**Task Dependencies:** Task "Pile Cap Construction" depends on "Bored Pile Installation (P1-P50)" being complete.

**Validation:** ✓ Tasks listed on project Gantt chart. ✓ Dependencies shown on task show page. ✓ Progress rolls up to project level.

**6. Work Orders (Create under Project #1)**

| Title | Site | Description | Status |
|---|---|---|---|
| WO-001: Pile Load Testing | Main Tower Site | Conduct PDA test on 5% of piles (8 piles) per specification | Issued |
| WO-002: Soil Compaction Test | Main Tower Site | Perform proctor test and field density test for basement backfill | Draft |

**Validation:** ✓ Print/PDF view shows professional layout. ✓ Status transitions work.

**7. Inspection Checklists (Create under Project #1)**

| Title | Site | Status |
|---|---|---|
| Steel Reinforcement Inspection — B1 Columns | Main Tower Site | Pending |
| Formwork & Shuttering Check — B1 Slab | Main Tower Site | In Progress |

**Validation:** ✓ Checklist items can be added. ✓ Pass/Fail marking works.

**8. Project Resources (Create under Project #1)**

| Resource Name | Resource Type | Quantity | Unit | Cost / Unit (BDT) | Remarks |
|---|---|---|---|---|---|
| Senior Site Engineer | Labour | 4 | Person | 2,200.00 | Day rate |
| Certified Piling Rig Operator | Labour | 2 | Person | 1,800.00 | Specialized skilled labour |
| Hydraulic Piling Rig (120 kN) | Equipment | 2 | Unit | 35,000.00 | Heavy equipment daily rental rate |
| Excavator (0.9 m³ Bucket) | Equipment | 3 | Unit | 18,000.00 | Earthwork machinery |
| Steel Fixer Team | Labour | 20 | Person | 950.00 | Day rate per worker |

**Task Resource Allocations:**
- Allocate 2x **Hydraulic Piling Rig** and 2x **Certified Piling Rig Operator** to Task *"Bored Pile Installation (P1-P50)"* (2026-01-20 to 2026-03-30).
- Allocate 2x **Excavator** to Task *"Basement Earthwork — 25,000 m³ Excavation"* (2026-05-01 to 2026-07-30).
- Allocate 15x **Steel Fixer Team** to Task *"Column & Wall Reinforcement — B1 Level"* (2026-06-15 to 2026-08-15).

**Validation:** ✓ Resources listed under Project Resources index (`/dashboard/core/project-resources`). ✓ Resource Allocation Chart (`/dashboard/core/resource-gantt`) displays timeline gantt bars accurately. ✓ Task detail page displays allocated resources with start/end dates.

---

### 16.3 Document Management — Drawings, RFIs, Change Orders, Transmittals

#### Test Data Set: City Centre Tower Documents

**1. Drawings (Create under Project #1)**

| Drawing Number | Title | Type | Discipline | Status |
|---|---|---|---|---|
| DRW-001 | Architectural Floor Plan — Ground Floor | Architectural | Architectural | Issued |
| DRW-002 | Structural Layout — Typical Floor (F1-F12) | Structural | Structural | Issued |
| DRW-003 | MEP Riser Diagram — All Floors | MEP | MEP | Draft |
| DRW-004 | Elevation — North & South Facades | Architectural | Architectural | Issued |
| DRW-005 | Reinforcement Details — B1 Retaining Wall | Shop | Structural | Draft |

**Revisions (add to DRW-001):** Revision A (P relim), dated 2026-01-10, with file upload. Mark as current.

**Validation:** ✓ Revision history shown. ✓ Only one revision marked current. ✓ File upload works via MediaLibrary.

**2. RFIs (Request for Information) — Create under Project #1**

| RFI Number | Subject | Question | Priority | Assigned To | Status |
|---|---|---|---|---|---|
| RFI-001 | Column Grid Alignment Discrepancy | Drawing DRW-002 shows column C-12 at grid intersection 4-C, but as-built survey shows 150mm offset. Please advise on correction. | High | (select user) | Answered |
| RFI-002 | MEP Sleeve Locations in Shear Walls | MEP drawing DRW-003 shows 200mm dia sleeves at grid B-5. Confirm sleeve locations do not interfere with structural rebar as per DRW-005. | Medium | (select user) | Open |

**Answer RFI-001:** "Offset is within tolerance. Proceed with as-built location. Formal variation to follow." — Answered by (select user), 2026-02-15.

**Validation:** ✓ RFI status transitions: open → answered → closed. ✓ PDF export shows professional format.

**3. Change Orders (Create under Project #1)**

| Number | Title | Type | Status | Cost Impact (BDT) | Time Impact (Days) |
|---|---|---|---|---|---|
| VO-001 | Additional Pile Cap Reinforcement | Variation | Approved | 450,000.00 | 5 |
| VO-002 | Basement Waterproofing Upgrade — Membrane Type | Change Order | Under Review | 1,200,000.00 | 10 |
| VO-003 | Façade Design Revision — Aluminium Composite Panel | Variation | Draft | 3,500,000.00 | 21 |

**Validation:** ✓ Approval/Rejection workflow works. ✓ Cost and time impact displayed on project cost summary.

**4. Drawing Transmittals (Create under Project #1)**

| Number | To Party | Purpose | Status |
|---|---|---|---|
| TRN-001 | ABC Engineering Consultants | For Approval | Sent |
| TRN-002 | Site Supervisor — Main Tower | For Construction | Draft |

**Validation:** ✓ Transmittal items (drawing + revision + copies) can be added dynamically.

---

### 16.4 Procurement Module — Full PR → PO → GRN Chain

#### Test Data Set: Procurement for City Centre Tower

**Prerequisite:** Create Vendors and Materials first.

**1. Vendors (Create)**

| Name | Contact Person | Email | Phone | Trade Category | Status | Credit Limit | Payment Terms |
|---|---|---|---|---|---|---|---|
| Bashundhara Steel Mills Ltd. | Mr. Kamal Hossain | kamal@bsm.com.bd | 01711-223344 | Steel Reinforcement | Approved | 15,000,000.00 | 30 Days |
| HeidelbergCement Bangladesh Ltd. | Ms. Farzana Akhter | farzana@heidelberg.com.bd | 01722-334455 | Cement & Concrete | Approved | 12,000,000.00 | 45 Days |
| Mirpur Ceramics & Tiles | Mr. Abdul Karim | karim@mirceramics.com | 01933-445566 | Finishing Materials | Pending | 3,000,000.00 | 30 Days |
| Rahimafrooz Electricals | Ms. Shahida Rahman | shahida@rahimafrooz.com | 01844-556677 | Electrical | Approved | 8,000,000.00 | 60 Days |

**Vendor Qualification:** Set first two vendors to "Qualified" status with performance rating 85.

**Validation:** ✓ Vendor documents upload works. ✓ Qualification status toggle works. ✓ Credit limit enforced in PO.

**2. Materials (Create)**

| Name | SKU | Unit | Reorder Level | Description |
|---|---|---|---|---|
| 60mm MS Rod (Grade 60) | ROD-60-G60 | Ton | 25.0000 | 60mm diameter mild steel rod, Grade 60, standard 12m length |
| 20mm MS Rod (Grade 60) | ROD-20-G60 | Ton | 30.0000 | 20mm diameter mild steel rod, Grade 60 |
| 12mm MS Rod (Grade 60) | ROD-12-G60 | Ton | 40.0000 | 12mm diameter mild steel rod, Grade 60 |
| Ordinary Portland Cement (OPC) | CMT-OPC-53 | Bag | 500.0000 | OPC Type I, Grade 53, 50kg bags |
| 20mm Crushed Stone Aggregate | AGG-20-CS | m³ | 150.0000 | 20mm graded crushed stone aggregate for concrete |
| Sylhet Sand (FMD) | SND-SYL-FMD | m³ | 100.0000 | Sylhet sand, Fineness Modulus 2.6 |
| 10mm CRS (Chipping) | AGG-10-CRS | m³ | 80.0000 | 10mm crushed stone chipping |
| MS Bricks (1st Class) | BRK-MS-1ST | Piece | 10,000.0000 | Machine-made 1st class bricks, size 250x120x70mm |
| PVC Conduit Pipe 20mm | PVC-20-ELEC | Meter | 500.0000 | 20mm diameter PVC electrical conduit pipe |
| GI Binding Wire 1.2mm | GI-WIRE-1.2 | Kg | 200.0000 | 1.2mm galvanized iron binding wire |

**Validation:** ✓ Material list shows all fields. ✓ Search by name/SKU works.

**3. Warehouses (Create)**

| Name | Location Address | Status |
|---|---|---|
| Main Yard — Gulshan | 42 Gulshan Avenue, Dhaka | Active |
| Badda Storage Depot | Plot 15, Badda Link Road, Dhaka | Active |

**4. Stock (Create — link to Warehouse and Material)**

| Warehouse | Material | Quantity | Min Stock |
|---|---|---|---|
| Main Yard | 20mm MS Rod (Grade 60) | 45.0000 | 30.0000 |
| Main Yard | Ordinary Portland Cement (OPC) | 800.0000 | 500.0000 |
| Main Yard | Sylhet Sand (FMD) | 200.0000 | 100.0000 |
| Main Yard | 20mm Crushed Stone Aggregate | 180.0000 | 150.0000 |
| Badda Depot | 12mm MS Rod (Grade 60) | 60.0000 | 40.0000 |
| Badda Depot | MS Bricks (1st Class) | 25,000.0000 | 10,000.0000 |

**Validation:** ✓ Stock levels visible on warehouse show page. ✓ Min stock alerts shown.

**5. Purchase Requisition (Create under Project #1)**

| Required Date | Status |
|---|---|
| 2026-08-15 | Draft → Submitted for Approval → Approved |

**PR Items:**

| Material | Quantity |
|---|---|
| 20mm MS Rod (Grade 60) | 35.0000 |
| 12mm MS Rod (Grade 60) | 25.0000 |
| GI Binding Wire 1.2mm | 150.0000 Kg |

**Validation:** ✓ PR number auto-generated. ✓ Submit for Approval triggers workflow (if configured). ✓ PR PDF export works.

**6. Purchase Order (Create from approved PR)**

| Vendor | Order Date | Status |
|---|---|---|
| Bashundhara Steel Mills Ltd. | 2026-07-20 | Draft → Ordered |

**PO Items (auto-populated from PR):** Same items as PR, with unit prices.
| Material | Qty | Unit Price | Total |
|---|---|---|---|
| 20mm MS Rod (Grade 60) | 35.0000 Ton | 78,000.00 | 2,730,000.00 |
| 12mm MS Rod (Grade 60) | 25.0000 Ton | 82,000.00 | 2,050,000.00 |
| GI Binding Wire 1.2mm | 150.0000 Kg | 145.00 | 21,750.00 |

**PO Total: 4,801,750.00 BDT**

**Validation:** ✓ Items auto-populate from PR. ✓ PO PDF shows professional layout with vendor details. ✓ Status transitions work.

**7. Goods Received Note (Create from approved PO)**

| PO | Site | Received Date | Delivery Note | Vehicle | Status |
|---|---|---|---|---|---|
| (select PO) | Main Tower Site | 2026-07-25 | DN-7821 | Dhaka-Metro-12-3456 | Verified |

**GRN Items (receive partial):**
| Material | Ordered Qty | Received Qty |
|---|---|---|
| 20mm MS Rod (Grade 60) | 35.0000 | 20.0000 (partial) |
| GI Binding Wire 1.2mm | 150.0000 Kg | 150.0000 Kg (full) |

**Validation:** ✓ GRN number auto-generated. ✓ Stock updated automatically in Main Yard. ✓ Remaining PO balance tracked (15 ton rod still pending).

---

### 16.5 Procurement — RFQ, Material Submittals & Subcontractors

#### RFQ Test Data

**1. RFQ (Create under Project #1)**

| Title | Description | Issue Date | Closing Date | Status |
|---|---|---|---|---|
| RFQ-001: Finishing Materials — Floor & Wall Tiles | Supply & deliver ceramic and vitrified tiles for lobby, corridors, and washrooms | 2026-07-01 | 2026-07-20 | Closed |

**RFQ Items:**
| Item | Qty | Estimated Unit Price |
|---|---|---|
| Vitrified Tile 600x600mm (Lobby) | 500 m² | 850.00 |
| Ceramic Wall Tile 300x450mm (Washroom) | 1,200 m² | 420.00 |

**Vendors Invited:** Mirpur Ceramics & Tiles

**Quotation (from Mirpur Ceramics):**
| Item | Unit Price | Total |
|---|---|---|
| Vitrified Tile 600x600mm | 820.00 | 410,000.00 |
| Ceramic Wall Tile 300x450mm | 395.00 | 474,000.00 |
| **Total** | | **884,000.00** |

**Award:** Award to Mirpur Ceramics → Auto-generates Purchase Requisition.

**Validation:** ✓ Quotation comparison matrix shows side-by-side. ✓ Award generates PR. ✓ RFQ status updates to Awarded.

**2. Material Submittals (Create under Project #1)**

| Title | Status |
|---|---|
| Vitrified Tile 600x600mm Sample — Mirpur Ceramics | Submitted → Under Review |
| PVC Conduit 20mm — Rahimafrooz Electricals | Approved |

**Review:** Approve the tile submittal.

**Validation:** ✓ Status workflow: draft → submitted → under_review → approved/rejected → resubmitted. ✓ Resubmit feature works.

**3. Subcontractors (Create)**

| Name | Contact | Phone | Trade | License No | Status | Rating |
|---|---|---|---|---|---|---|
| RMG Construction Ltd. | Engr. Rafiq Mahmud | 01733-556677 | Structural Works | LC-2024-0789 | Active | 88 |
| EcoTech MEP Solutions | Mr. Shahin Alam | 01944-667788 | MEP | LC-2025-0123 | Active | 82 |
| Premium Finishing Works | Mr. Hasan Ali | 01855-778899 | Finishing | LC-2025-0456 | Pending | 0 |

**4. Subcontract Agreement (Create for RMG Construction)**

| Title | Subcontractor | Project | Scope | Start | End | Value | Retention | Status |
|---|---|---|---|---|---|---|---|---|
| Structural Steel & Reinforcement Works — Floors 1-12 | RMG Construction Ltd. | City Centre Tower | Provide labor for rebar cutting, bending, fixing, and concrete pouring for floors 1-12 | 2026-07-01 | 2027-06-30 | 28,500,000.00 | 10% | Active |

**5. Subcontract Progress Payment (for Agreement above)**

| Work Period | Work Completed Value | Retention Deducted | Net Amount | Status |
|---|---|---|---|---|
| July 2026 | 2,850,000.00 | 285,000.00 | 2,565,000.00 | Paid |

**Validation:** ✓ Agreement PDF/Print works. ✓ Progress Payment retention calculated automatically.

---

### 16.6 Finance — Budget, BOQ, Tender, Rate Analysis

#### Test Data Set: City Centre Tower Financials

**1. Budgets (Create under Project #1 — multiple cost codes)**

| Cost Code | Description | Budgeted Amount | Planned Value | Earned Value | Actual Cost |
|---|---|---|---|---|---|
| 01-100 | Substructure — Foundation & Piling | 85,000,000.00 | 85,000,000.00 | 85,000,000.00 | 82,500,000.00 |
| 01-200 | Substructure — Basement | 65,000,000.00 | 45,000,000.00 | 30,000,000.00 | 28,000,000.00 |
| 02-100 | Superstructure — Columns & Beams (F1-12) | 120,000,000.00 | 20,000,000.00 | 5,000,000.00 | 4,200,000.00 |
| 02-200 | Superstructure — Slabs (F1-12) | 95,000,000.00 | 15,000,000.00 | 0.00 | 0.00 |
| 03-100 | Finishing — Internal Plaster & Paint | 45,000,000.00 | 0.00 | 0.00 | 0.00 |
| 04-100 | MEP — Electrical Installation | 35,000,000.00 | 0.00 | 0.00 | 0.00 |
| 05-100 | External Works — Landscaping | 15,000,000.00 | 0.00 | 0.00 | 0.00 |

**Validation #1:** Budget index shows all cost codes with computed EVM metrics.

**Manual EVM Verification — Budget code 01-100:**
- SPI = 85,000,000 / 85,000,000 = **1.00** (on schedule)
- CPI = 85,000,000 / 82,500,000 = **1.03** (under budget)
- ETC = (85,000,000 - 85,000,000) / 1.03 = **0.00**
- EAC = 82,500,000 + 0 = **82,500,000**
- Variance = 85,000,000 - 82,500,000 = **2,500,000 (favorable)**

**Manual EVM Verification — Budget code 01-200:**
- SPI = 30,000,000 / 45,000,000 = **0.67** (behind schedule)
- CPI = 30,000,000 / 28,000,000 = **1.07** (under budget)

**Validation:** ✓ Forecasting view calculates correctly. ✓ Cost Overrun Alerts page shows thresholds.

**2. Cost Overrun Alert (should auto-generate if 80% threshold passed)**
Create additional budget entry to trigger alert:
| Cost Code | Description | Budgeted | Actual |
|---|---|---|---|
| 01-150 | Pile Testing & QA | 2,000,000.00 | 1,750,000.00 |

**Add a budget to test overrun boundaries:**
| Cost Code | Description | Budgeted | Actual | Earned | Planned |
|---|---|---|---|---|---|
| 01-160 | Temporary Works (Shoring & Dewatering) | 8,000,000.00 | 7,200,000.00 | 5,600,000.00 | 7,000,000.00 |

**EVM verification for 01-160:**
- SPI = 5,600,000 / 7,000,000 = **0.80** (behind schedule — warning)
- CPI = 5,600,000 / 7,200,000 = **0.78** (over budget — danger)
- Budget utilization: 7,200,000/8,000,000 = **90%** (danger zone, >80%)

**Validation:** ✓ 80% usage = warning generated. ✓ 100%+ usage = critical alert. ✓ CPI < 0.8 triggers overrun indicators. ✓ Acknowledge/Resolve workflow functions.

**3. BOQ (Create under Project #1)**

| Title | Description | Total Amount | Status |
|---|---|---|---|
| Main Structure — Concrete & Reinforcement | Bill of quantities for all structural concrete and steel reinforcement works | 218,000,000.00 | Approved |

**BOQ Items:**
| Item No | Description | Unit | Qty | Unit Price | Total |
|---|---|---|---|---|---|
| 01 | 30 MPa Ready-Mix Concrete (Pile Caps & Ground Beams) | m³ | 2,500.00 | 8,500.00 | 21,250,000.00 |
| 02 | 35 MPa Ready-Mix Concrete (Columns — F1-F12) | m³ | 4,800.00 | 9,200.00 | 44,160,000.00 |
| 03 | 35 MPa Ready-Mix Concrete (Slabs — F1-F12) | m³ | 5,200.00 | 9,200.00 | 47,840,000.00 |
| 04 | Reinforcement Steel — 20mm Grade 60 (Columns) | Ton | 480.00 | 82,000.00 | 39,360,000.00 |
| 05 | Reinforcement Steel — 20mm Grade 60 (Slabs) | Ton | 420.00 | 82,000.00 | 34,440,000.00 |
| 06 | Reinforcement Steel — 12mm Grade 60 (Slabs) | Ton | 350.00 | 85,000.00 | 29,750,000.00 |
| 07 | Formwork — Column Shuttering (F1-F12) | m² | 28,000.00 | 380.00 | 10,640,000.00 |
| 08 | Formwork — Slab Formwork (F1-F12) | m² | 42,000.00 | 420.00 | 17,640,000.00 |
| **Total** | | | | | **218,080,000.00** |

**Validation:** ✓ BOQ item totals sum to grand total. ✓ Excel import works (download sample, populate, upload).

**4. Rate Analysis (Create under Project #1)**

| Title | Description | Total Rate | Status |
|---|---|---|---|
| RA-001: 35 MPa Concrete Rate Analysis | Unit rate breakdown for 35 MPa ready-mix concrete per m³ | 9,200.00 | Approved |

**Rate Analysis Items (cost components):**
| Item | Type | Qty | Unit | Unit Rate | Total |
|---|---|---|---|---|---|
| Cement OPC (500 kg/m³) | Material | 500.0000 | Kg | 11.00 | 5,500.00 |
| Sylhet Sand (680 kg/m³) | Material | 0.4500 | m³ | 1,200.00 | 540.00 |
| 20mm Aggregate (1,050 kg/m³) | Material | 0.7000 | m³ | 1,800.00 | 1,260.00 |
| Mixer & Vibrator | Equipment | 1.0000 | Hr | 500.00 | 500.00 |
| Skilled Labour (4 persons) | Labour | 4.0000 | Hr | 150.00 | 600.00 |
| Unskilled Labour (8 persons) | Labour | 8.0000 | Hr | 100.00 | 800.00 |
| **Total per m³** | | | | | **9,200.00** |

**Validation:** ✓ Rate analysis items sum to total rate. ✓ Reusable for BOQ pricing.

**5. Material Takeoff (Create under Project #1)**

| Description | Source Drawing | Unit | Quantity | Unit Price | Total Price |
|---|---|---|---|---|---|
| 20mm Rod — Column C1-C24 (F1-F12) per DRW-002 | DRW-002 | Ton | 280.0000 | 82,000.00 | 22,960,000.00 |
| 12mm Rod — Slab S1-S16 (F1-F12) per DRW-002 | DRW-002 | Ton | 350.0000 | 85,000.00 | 29,750,000.00 |

**Validation:** ✓ Takeoffs linked to BOQ items. ✓ Quantities reflect source drawing references.

---

### 16.7 Finance — Tenders, Invoicing, Bills & Accounting

**1. Tender (Create under Project #1)**

| Title | Issue Date | Close Date | Status |
|---|---|---|---|
| Tender for MEP Works — City Centre Tower | 2026-06-01 | 2026-07-15 | Closed |

**Tender Bids:**
| Vendor | Bid Amount | Status | Score |
|---|---|---|---|
| EcoTech MEP Solutions | 32,500,000.00 | Submitted | 88 |
| DESCO Engineering Ltd. | 35,200,000.00 | Submitted | 82 |

**Evaluation Matrix:** Compare both bids with scoring on Price (40%), Experience (30%), Timeline (20%), Safety (10%).

**Award:** Award to EcoTech MEP Solutions (lowest bid + highest score). Generate award letter.

**Validation:** ✓ Bid comparison matrix works. ✓ Tender package documents can be uploaded. ✓ Award letter PDF prints correctly.

**2. Invoice (Create under Project #1 — for client billing)**

| Title | Issue Date | Due Date | Subtotal | Tax Rate | Status |
|---|---|---|---|---|---|
| Invoice #1 — Foundation Works (Progress Billing) | 2026-04-30 | 2026-05-30 | 42,500,000.00 | 5.00% | Paid |

**Invoice Items:**
| Description | Qty | Unit Price | Total |
|---|---|---|---|
| Foundation Piling — 150 piles completed | 150.0000 | 250,000.00 | 37,500,000.00 |
| Pile Cap Concrete — 350 m³ @ 8,500/m³ | 1.0000 | 2,975,000.00 | 2,975,000.00 |
| Pile Load Testing — 8 piles @ 250,000 each | 1.0000 | 2,000,000.00 | 2,000,000.00 |

**Auto-computed:** Tax (5%) = 2,125,000.00. Total = 44,625,000.00.

**Record Payment:** Paid amount = 44,625,000.00 on 2026-05-15.

**Validation:** ✓ Tax auto-calculated. ✓ Payment updates invoice status to Paid. ✓ Due amount = 0.00 after full payment.

**Create a Second Invoice (partially paid):**
| Title | Issue Date | Subtotal | Tax (5%) | Total | Status |
|---|---|---|---|---|---|
| Invoice #2 — Basement Works (Progress Billing) | 2026-07-31 | 18,750,000.00 | 937,500.00 | 19,687,500.00 | Partially Paid |

**Record Partial Payment:** Paid = 10,000,000.00 on 2026-08-15. Due = 9,687,500.00.

**Validation:** ✓ Status auto-updates to Partially Paid. ✓ Outstanding amount reflects correctly.

**3. Interim Payment Application (IPA — Create under Project #1)**

| Title | Period Start | Period End | Applied Amount | Retention Rate | Status |
|---|---|---|---|---|---|
| IPA-001: Foundation Works (Jan-Apr 2026) | 2026-01-15 | 2026-04-30 | 42,500,000.00 | 5.00% | Invoice Generated |

**Validation:** ✓ Retention (5%) = 2,125,000.00 auto-deducted. ✓ Net Amount = 40,375,000.00. ✓ "Generate Invoice" button creates linked invoice.

**4. Bill (Create — vendor payable)**

| Title | Vendor | Bill Date | Due Date | Subtotal | Tax (2.5%) | Total | Status |
|---|---|---|---|---|---|---|---|
| Steel Supply — July 2026 | Bashundhara Steel Mills | 2026-07-28 | 2026-08-27 | 4,675,000.00 | 116,875.00 | 4,791,875.00 | Draft → Approved |

**Bill Items:**
| Description | Qty | Unit Price | Total |
|---|---|---|---|
| 20mm MS Rod Grade 60 | 35.0000 Ton | 78,000.00 | 2,730,000.00 |
| 12mm MS Rod Grade 60 | 25.0000 Ton | 82,000.00 | 2,050,000.00 |

**Bill Payment:** Record payment of 4,791,875.00 on 2026-08-15.

**Validation:** ✓ Bill status updates when paid. ✓ Bill Payment reduces due amount.

**5. Chart of Accounts (Create)**

Create standard COA hierarchy:

| Code | Name | Type | Normal Balance | Parent |
|---|---|---|---|---|
| 1 | Assets | Asset | Debit | — |
| 1-1010 | Cash & Bank | Asset | Debit | 1 |
| 1-1020 | Accounts Receivable | Asset | Debit | 1 |
| 1-1030 | Inventory — Materials | Asset | Debit | 1 |
| 1-1040 | Equipment & Machinery | Asset | Debit | 1 |
| 2 | Liabilities | Liability | Credit | — |
| 2-2010 | Accounts Payable | Liability | Credit | 2 |
| 2-2020 | Retention Payable | Liability | Credit | 2 |
| 3 | Equity | Equity | Credit | — |
| 3-3010 | Share Capital | Equity | Credit | 3 |
| 3-3020 | Retained Earnings | Equity | Credit | 3 |
| 4 | Revenue | Revenue | Credit | — |
| 4-4010 | Contract Revenue | Revenue | Credit | 4 |
| 5 | Expenses | Expense | Debit | — |
| 5-5010 | Material Costs | Expense | Debit | 5 |
| 5-5020 | Labour Costs | Expense | Debit | 5 |
| 5-5030 | Subcontractor Costs | Expense | Debit | 5 |
| 5-5040 | Equipment Costs | Expense | Debit | 5 |
| 5-5050 | Overhead & Administration | Expense | Debit | 5 |

**Validation:** ✓ Tree hierarchy displays correctly. ✓ Account codes sort properly.

**6. Journal Entry (Create — record foundation payment)**

| Journal No | Date | Description | Type | Status |
|---|---|---|---|---|
| JV-001 | 2026-05-15 | Payment received — Invoice #1 Foundation Works | Receipt | Posted |

**Journal Items (double-entry):**
| Account | Debit | Credit | Narration |
|---|---|---|---|
| 1-1010 Cash & Bank | 44,625,000.00 | — | Payment received via bank transfer |
| 4-4010 Contract Revenue | — | 42,500,000.00 | Revenue recognition — foundation works |
| 2-2020 Retention Payable | — | 2,125,000.00 | Retention withheld @ 5% |

**Validation (must balance: Debits = Credits):**
- Debits: 44,625,000.00
- Credits: 42,500,000.00 + 2,125,000.00 = 44,625,000.00 ✓ **Balanced**

**Create Second Journal (record material purchase):**

| Journal No | Date | Description | Status |
|---|---|---|---|
| JV-002 | 2026-07-28 | Steel purchase — Bashundhara Steel Mills — Bill #1 | Posted |

**Items:**
| Account | Debit | Credit |
|---|---|---|
| 1-1030 Inventory — Materials | 4,675,000.00 | — |
| 5-5040 Overhead & Admin (Input VAT) | 116,875.00 | — |
| 2-2010 Accounts Payable | — | 4,791,875.00 |

**Validation:** ✓ Debits (4,791,875.00) = Credits (4,791,875.00). ✓ Balance check shows OK.

**7. General Ledger (View)**

**Validation:** ✓ All posted journal entries grouped by account. ✓ Running balance per account. ✓ Date range filter works.

**8. Trial Balance (View)**

**Validation:** ✓ Total Debits = Total Credits. ✓ Accounts sorted by code/type. ✓ **Fix any unbalanced entries.**

**9. Balance Sheet (View)**

**Validation:** ✓ Total Assets = Total Liabilities + Total Equity. ✓ Tree format shows parent-child accounts.

**10. Income Statement (View)**

**Validation:** ✓ Net Income = Total Revenue - Total Expenses. ✓ Period filter works correctly.

**11. Receivables (Create)**

| Client | Project | Amount | Due Date | Status |
|---|---|---|---|---|
| (select Client) | City Centre Tower | 9,687,500.00 | 2026-09-15 | Outstanding |

**Record Payment:** 9,687,500.00 on 2026-08-30.

**Validation:** ✓ Receivable status updates to Paid. ✓ Payment history tracked.

**12. Bank Guarantee (Create)**

| Ref No | Type | Issuing Bank | Beneficiary | Project | Amount | Issue Date | Expiry | Status |
|---|---|---|---|---|---|---|---|---|
| BG-001 | Performance Guarantee | Sonali Bank Ltd. | Dhaka City Corporation | City Centre Tower | 42,500,000.00 (5% of contract) | 2026-01-10 | 2028-09-30 | Active |
| BG-002 | Advance Payment Guarantee | Dutch-Bangla Bank | Dhaka City Corporation | City Centre Tower | 21,250,000.00 (2.5% of contract) | 2026-01-10 | 2028-06-30 | Active |

**Validation:** ✓ BG linked to contract. ✓ Status transitions work (active → released/expired).

---

### 16.8 Finance — Labour Entries, Expenses

#### Test Data

**1. Labour Entry (Create under Project #1)**

| Date | Trade | No of Workers | Hours Worked | Rate/Hour | Total Cost |
|---|---|---|---|---|---|
| 2026-07-24 | Steel Fixer | 15 | 8 | 175.00 | 21,000.00 |
| 2026-07-24 | Carpenter | 12 | 8 | 165.00 | 15,840.00 |
| 2026-07-24 | General Labour | 25 | 8 | 120.00 | 24,000.00 |
| 2026-07-25 | Steel Fixer | 18 | 10 (overtime) | 262.50 (1.5×) | 31,500.00 |

**Validation:** ✓ Total cost auto-calculated. ✓ Filterable by project and date range.

**2. Expense (Create under Project #1)**

| Category | Amount | Date | Description | Status |
|---|---|---|---|---|
| Transportation | 15,000.00 | 2026-07-24 | Material delivery — Badda to Main Tower site (3 trips) | Paid |
| Office Supplies | 5,200.00 | 2026-07-25 | Stationery & printing — site office | Paid |
| Utilities | 18,500.00 | 2026-07-28 | Generator diesel & site electricity bill — July | Unpaid |
| Equipment Rental | 45,000.00 | 2026-07-28 | Tower crane rental — additional week (week 30) | Unpaid |

**Validation:** ✓ Expense total reflects on project cost summary. ✓ Mark as paid status toggle works.

---

### 16.9 HR — Employee, Attendance, Timesheets & Payroll

#### Test Data Set: Site Workforce

**1. Employees (Create)**

| Code | Full Name | Designation | Department | Phone | Basic Salary | Joining Date | Status |
|---|---|---|---|---|---|---|---|
| EMP-001 | Md. Rahim Uddin | Site Engineer | Construction | 01711-111111 | 55,000.00 | 2025-12-01 | Active |
| EMP-002 | Md. Karim Hossain | Senior Steel Fixer | Construction | 01722-222222 | 28,000.00 | 2026-01-15 | Active |
| EMP-003 | Md. Jabbar Ali | Carpenter Foreman | Construction | 01733-333333 | 25,000.00 | 2026-01-15 | Active |
| EMP-004 | Abdul Malek | General Labourer | Construction | 01744-444444 | 15,000.00 | 2026-01-20 | Active |
| EMP-005 | Farida Begum | Office Assistant | Administration | 01755-555555 | 12,000.00 | 2026-02-01 | Active |
| EMP-006 | Engr. Salahuddin Mahmud | Project Manager | Management | 01766-666666 | 120,000.00 | 2025-10-01 | Active |
| EMP-007 | Md. Shahin | Safety Officer | HSE | 01777-777777 | 40,000.00 | 2026-01-10 | Active |
| EMP-008 | Ratan Sarker | Heavy Equipment Operator | Equipment | 01788-888888 | 22,000.00 | 2026-02-15 | Active |

**Validation:** ✓ Employee list filters by department/status. ✓ Show page displays all profile fields.

**2. Attendance (Bulk Register — 1 week for all employees)**

Date: 2026-07-24 (Sample Day)
| Employee | Clock In | Clock Out | Status |
|---|---|---|---|
| Md. Rahim Uddin | 08:00 | 18:00 | Present |
| Md. Karim Hossain | 08:15 | 17:30 | Present |
| Md. Jabbar Ali | 08:05 | 18:15 | Present |
| Abdul Malek | 08:30 | 17:00 | Present |
| Farida Begum | 09:00 | 17:00 | Present |
| Engr. Salahuddin Mahmud | 07:45 | 18:30 | Present |
| Md. Shahin | 10:00 | 17:00 | Late |
| Ratan Sarker | — | — | Absent |

**Repeat for multiple days to create a month's data** (e.g., 22 working days for July 2026).

**Validation:** ✓ Monthly summary shows present/absent/late/half-day/holiday counts. ✓ Worked hours calculated from clock_in/clock_out.

**3. Timesheet (Create for July 2026 — sample entries)**

| Employee | Project | Date | Start | End | Hours | Description |
|---|---|---|---|---|---|---|
| Md. Rahim Uddin | City Centre Tower | 2026-07-24 | 08:00 | 18:00 | 9.00 | Column reinforcement inspection & QA |
| Md. Karim Hossain | City Centre Tower | 2026-07-24 | 08:15 | 17:30 | 8.25 | Rebar cutting & bending for B1 columns |
| Ratan Sarker | City Centre Tower | 2026-07-25 | 08:00 | 17:45 | 8.75 | Tower crane operation — concrete pour |

**Validation:** ✓ Timesheet filterable by employee, project, date range. ✓ Overtime (hours > 8/day) identified for wage slip calc.

**4. Wage Slip (Generate for July 2026 — sample)**

Generating wage slip for Md. Karim Hossain (July 2026):

| Component | Calculation | Amount |
|---|---|---|
| Basic Salary | 28,000.00 | |
| Daily Rate | 28,000 / 30 | 933.33 |
| Present Days | (18 present + 2 late×0.75 + 1 half-day×0.5) | 19.50 |
| Basic Pay | 933.33 × 19.50 | 18,200.00 |
| Overtime Pay | 8 hours extra × (933.33/8) × 1.5 | 1,400.00 |
| Allowances | 10% of basic | 1,820.00 |
| Deductions | 5% of basic | 910.00 |
| **Net Pay** | | **20,510.00** |

**Validation:** ✓ Wage slip PDF shows professional payroll format. ✓ Print view works.

**5. Leave Requests (Create)**

| Employee | Type | Start | End | Reason | Status |
|---|---|---|---|---|---|
| Md. Shahin | Annual | 2026-08-10 | 2026-08-12 | Personal leave (3 days) | Pending → Approved |
| Abdul Malek | Sick | 2026-07-28 | 2026-07-29 | Fever | Pending |

**Validation:** ✓ Leave approval workflow (pending → approved/rejected). ✓ Attendance auto-adjusted.

---

### 16.10 HR — Equipment, Safety & Training Modules

**1. Equipment (Create)**

| Code | Name | Category | Make/Model | Acquisition | Purchase Cost | Hire Rate | Status |
|---|---|---|---|---|---|---|---|
| EQ-001 | Tower Crane — Liebherr 125 HC-L | Crane | Liebherr / 125 HC-L | Hired | — | 150,000.00/week | Active |
| EQ-002 | Concrete Pump — Putzmeister 36M | Concrete Pump | Putzmeister / 36M | Hired | — | 85,000.00/day | Active |
| EQ-003 | Excavator — Komatsu PC200 | Excavator | Komatsu / PC200-8 | Owned | 15,500,000.00 | — | Active |
| EQ-004 | Diesel Generator 250 KVA | Generator | Cummins / C250D5 | Owned | 3,200,000.00 | — | Active |
| EQ-005 | Plate Compactor — Mikasa MVC-90 | Compaction | Mikasa / MVC-90 | Owned | 350,000.00 | — | Under Repair |

**Allocation (allocate EQ-003 to Main Tower Site):**
- Project: City Centre Tower, Site: Main Tower Site, Allocated: 2026-01-15

**Hire Details (for EQ-001):**
- Vendor: Abdullah Brothers Ltd., Start: 2026-01-15, End: 2027-06-30, Period: Monthly

**Validation:** ✓ Equipment filterable by status/category. ✓ Owned vs Hired distinction clear. ✓ Allocation shown on project/site pages.

**2. Equipment Maintenance (Create for EQ-003 Komatsu Excavator)**

| Type | Date | Description | Cost | Vendor | Next Due (meter) | Status |
|---|---|---|---|---|---|---|
| Preventive | 2026-06-15 | Engine oil & filter change (250-hour service) | 35,000.00 | CAT Bangladesh | 1,250 hours | Completed |
| Corrective | 2026-07-20 | Hydraulic hose replacement — boom cylinder | 18,500.00 | Hydraulic Services Ltd. | — | In Progress |

**Validation:** ✓ Maintenance history shown inline on equipment show page. ✓ Next due meter alert works.

**3. Fuel Log (Create for EQ-003 Excavator)**

| Date | Fuel Type | Quantity | Unit | Unit Cost | Total Cost | Meter Hours | Vendor | Receipt No |
|---|---|---|---|---|---|---|---|---|
| 2026-07-22 | Diesel | 120.00 | Liters | 105.00 | 12,600.00 | 985 | Eastern Fuel Station | REC-7821 |
| 2026-07-25 | Diesel | 100.00 | Liters | 105.00 | 10,500.00 | 1,050 | Eastern Fuel Station | REC-7890 |
| 2026-07-28 | Diesel | 150.00 | Liters | 106.00 | 15,900.00 | 1,120 | Jamuna Fuel | REC-7955 |

**Validation:** ✓ Total cost auto-calculated (qty × unit cost). ✓ Filterable by equipment and fuel type.

**4. PPE Issuance (Create for employees)**

| Employee | Item | Category | Issue Date | Qty | Size | Condition | Return Date |
|---|---|---|---|---|---|---|---|
| Abdul Malek | Safety Helmet | Head Protection | 2026-01-20 | 1 | Standard | New | — |
| Abdul Malek | Steel-Toe Boots | Foot Protection | 2026-01-20 | 1 | Size 42 | Good | — |
| Md. Karim Hossain | Safety Harness | Fall Protection | 2026-02-01 | 1 | Medium | New | — |
| Md. Karim Hossain | Welding Shield | Eye Protection | 2026-02-01 | 1 | Standard | Good | 2026-07-15 (returned, worn) |

**Validation:** ✓ Employee PPE history visible. ✓ Return tracking works.

**5. Training Records (Create)**

| Employee | Training | Provider | Start | End | Status | Certificate No | Cost |
|---|---|---|---|---|---|---|---|---|
| Md. Shahin | Advanced Fire Safety & First Aid | Bangladesh Fire Service Academy | 2026-03-10 | 2026-03-12 | Completed | FSC-2026-0421 | 5,500.00 |
| Ratan Sarker | Tower Crane Operation & Safety | Institute of Crane Operators | 2026-04-01 | 2026-04-15 | Completed | TCO-2026-0112 | 12,000.00 |
| Md. Karim Hossain | Scaffolding Safety Awareness | In-house | 2026-07-20 | 2026-07-20 | Completed | — | 0.00 |

**Validation:** ✓ Training status workflow (planned → in-progress → completed → expired). ✓ Filterable by employee.

**6. Certifications & Licences (Create)**

| Employee | Certification | Issuing Authority | Certificate No | Issue Date | Expiry | Category | Status |
|---|---|---|---|---|---|---|---|---|
| Ratan Sarker | Heavy Equipment Operator License | Bangladesh Road Transport Authority | BRTA-OP-24578 | 2024-03-15 | 2027-03-14 | License | Active |
| Md. Shahin | Fire Safety Officer Certification | Bangladesh Fire Service | FSC-2025-1122 | 2025-06-01 | 2027-05-31 | Certification | Active |
| Engr. Salahuddin Mahmud | PMP Certification | PMI | PMP-4891256 | 2024-01-15 | 2027-01-14 | Certification | Active |

**Validation:** ✓ Expiry date tracking works. ✓ Renewal reminder date configurable. ✓ Status auto-updates (active → expired).

**7. Incident Reports (Create)**

| Date | Type | Severity | Location | Description | Status |
|---|---|---|---|---|---|
| 2026-06-15 | Near Miss | Moderate | Basement B2 — Shoring Area | A section of temporary shoring wall showed 15mm deflection during excavation. Workers evacuated. Area secured immediately. | Under Investigation |
| 2026-07-20 | Injury | Minor | Main Tower — F1 Column | Worker sustained minor cut on forearm while cutting rebar with angle grinder. First aid administered on site. | Open |

**Root Cause (for Near Miss):** Inadequate bracing of shoring system at corner intersection.
**Corrective Action:** Additional diagonal bracing installed. All shoring points re-inspected.

**Validation:** ✓ Incident severity color-coded. ✓ Status workflow: open → under_investigation → closed.

**8. HSE Checklists (Create)**

| Title | Type | Project | Site | Date | Status |
|---|---|---|---|---|---|
| Weekly HSE Inspection — B1 Level | General | City Centre Tower | Main Tower Site | 2026-07-24 | In Progress |

**Checklist Items:**
| Item | Status |
|---|---|
| Fire extinguishers available & tagged — 15 inspected, all pass | Compliant |
| Emergency exit routes clearly marked | Compliant |
| All workers wearing PPE (helmets, vests, boots) | Non-Compliant — 3 workers without vests |
| Electrical cables properly routed & protected | Compliant |
| Scaffolding — base plates, guard rails, toe boards | Compliant |
| First aid kit stocked & accessible | Compliant |

**Validation:** ✓ Item-level pass/fail tracking. ✓ Corrective actions recorded for non-compliant items.

**9. Toolbox Talks (Create)**

| Topic | Date | Location | Duration (min) | Conductor | Attendees |
|---|---|---|---|---|---|
| Safe Use of Angle Grinders & Cutting Tools | 2026-07-24 | Main Tower Site Office | 15 | Md. Shahin | All steel fixers & general workers (22 persons) |
| Working at Height — Fall Prevention | 2026-07-25 | Main Tower — Ground Floor | 20 | Md. Shahin | All scaffolders & steel fixers (18 persons) |

**Discussion Points:** Always inspect disc before use, wear safety goggles, ensure guard is in place, never use without side handle.

**Validation:** ✓ Talk record shows topic + attendees. ✓ Filterable by conductor and date.

**10. Permit to Work (Create)**

| Permit Type | Work Location | Description | Hazards | Valid From | Valid Until | Status |
|---|---|---|---|---|---|---|
| Hot Work | B1 Level — Column B-12 | Welding of steel brackets for MEP supports | Fire, fumes, burns | 2026-07-24 08:00 | 2026-07-24 17:00 | Active |
| Working at Height | Main Tower — F5 Edge | Edge protection installation at floor 5 perimeter | Fall from height, dropped tools | 2026-07-25 09:00 | 2026-07-25 16:00 | Pending Approval |
| Confined Space | Basement B2 — Water Tank | Inspection & cleaning of underground water reservoir | Oxygen deficiency, toxic gases | 2026-07-28 08:00 | 2026-07-28 12:00 | Draft |

**Safety Measures (for Hot Work):** Fire extinguisher on site, fire watch person assigned, work area cleared of combustibles, welding screen in place.

**Validation:** ✓ PTW number auto-generated. ✓ Status workflow: draft → pending_approval → approved → active → completed/cancelled.

**11. Safety Audit (Create)**

| Type | Project | Site | Audit Date | Auditor | Score | Status |
|---|---|---|---|---|---|---|
| Internal | City Centre Tower | Main Tower Site | 2026-07-26 | (select user) | 78 | Completed |
| Client | City Centre Tower | Main Tower Site | 2026-07-28 | Client Representative | — | Scheduled |

**Findings (Internal Audit, Score 78/100):**
- ✅ Good: PPE compliance improved 15% since last audit
- ⚠️ Notice: 2 fire extinguishers past monthly inspection date
- ❌ Non-Conformance: No daily pre-task safety briefing records for this week
- ✅ Good: All permits to work are properly displayed at work locations

**Recommendations:** Implement mandatory daily safety briefing, assign fire extinguisher inspection to dedicated person.

**Validation:** ✓ Audit number auto-generated. ✓ Score tracking 0-100. ✓ Status: scheduled → in_progress → completed → follow_up.

---

### 16.11 Quality Control — NCR, ITP, Punch List, Risk, Corrective Actions

**1. ITP — Inspection & Test Plan (Create under Project #1)**

| Title | Phase | Description | Status |
|---|---|---|---|
| ITP-001: Column Reinforcement — B1 Level | Basement & Substructure | Inspection and test plan for steel reinforcement of 24 columns at B1 level | Active |

**ITP Items:**
| Order | Description | Acceptance Criteria | Status |
|---|---|---|---|
| 1 | Rebar grade & diameter verification | As per DRW-005 & BOQ specification | Passed |
| 2 | Rebar spacing & alignment check | ±5mm tolerance per ACI 318 | Passed |
| 3 | Cover block placement (40mm cover) | 40mm ±3mm, min 4 blocks/m² | Passed |
| 4 | Rebar lap length verification (35d for 20mm = 700mm) | 700mm ±20mm | Passed |
| 5 | Binding wire tightness & spacing (max 600mm) | All joints secure, spacing ≤600mm | Passed |
| 6 | Column verticality check before concrete | ±5mm per floor height | Passed |

**Validation:** ✓ ITP number auto-generated. ✓ Completion % = 100% when all items passed. ✓ Status transitions: draft → active → completed.

**2. NCR — Non-Conformance Report (Create under Project #1)**

| Title | Category | Severity | Location | Identified Date | Due Date | Status |
|---|---|---|---|---|---|---|
| NCR-001: Basement B2 — Waterproofing Membrane Delamination | Material/Workmanship | Major | B2 Level — East Retaining Wall | 2026-07-20 | 2026-08-05 | Open |

**Description:** During inspection of B2 basement wall, 12m² section of applied waterproofing membrane (bituminous sheet type) showed signs of delamination at the lap joints. This affects water tightness of the basement structure.

**Root Cause:** Surface not properly cleaned and primed before membrane application. Moisture content of substrate exceeded 4%.

**Corrective Action:** Remove delaminated section (14m²), re-prepare surface with shot blasting, re-apply primer, install new membrane with 100mm overlap, test with flood test for 48 hours.

**Validation:** ✓ NCR number auto-generated. ✓ Severity color-coded. ✓ Status: open → under_review → resolved → closed.

**3. Corrective Action (CAR — linked to NCR-001)**

| CAR Number | Title | Responsible Person | Target Date | Status | Effectiveness Check |
|---|---|---|---|---|---|
| CAR-001 | Repair B2 Waterproofing Membrane — East Wall | Site Engineer — Md. Rahim Uddin | 2026-08-02 | Verified | ✓ Flood test passed — zero leakage after 72 hours |

**Validation:** ✓ CAR number auto-generated. ✓ Status: open → in_progress → completed → verified.

**4. Punch List (Create under Project #1)**

| Title | Inspection Date | Due Date | Status |
|---|---|---|---|
| PL-001: B1 Level — Post-Concrete Snagging | 2026-07-25 | 2026-08-15 | Open |

**Punch List Items:**
| Description | Location | Severity | Status |
|---|---|---|---|
| Honeycombing in Column C-8 — 300cm² area | B1, Grid 3-C | Major | Open |
| Formwork line offset at slab edge — 5mm step | B1, Grid B-4/Wall | Minor | In Progress |
| Exposed rebar at beam-column joint — B3/6 | B1, Grid B-6 | Major | Open |
| Unfinished concrete surface — rough patch 2m² | B1, Column C-12 | Minor | Completed |
| Tie wire ends not tucked — 12 locations | B1, Various columns | Minor | Open |

**Validation:** ✓ Items ordered by severity. ✓ Completion % = 20% (1 of 5 completed). ✓ Status: open → in_progress → completed.

**5. Material Test Certificate (Create)**

| Material | Test Type | Result | Certificate No | Test Date | Expiry Date |
|---|---|---|---|---|---|
| 20mm MS Rod (Grade 60) — Batch L-2407 | Tensile Strength Test | Yield: 415 MPa, Ultimate: 580 MPa — Pass | MTC-2026-0789 | 2026-07-15 | — |
| OPC Cement — Batch C-0726 | Compressive Strength Test | 3-day: 28.5 MPa, 7-day: 39.2 MPa, 28-day: 54.1 MPa — Pass | MTC-2026-0790 | 2026-07-10 | 2027-01-10 |
| 20mm Crushed Stone Aggregate | Sieve Analysis & Gradation | Conforms to ASTM C33 — Pass | MTC-2026-0791 | 2026-07-12 | — |

**Validation:** ✓ Test certificate linked to material. ✓ File upload for certificate document.

**6. Risk Register (Create under Project #1)**

| Title | Category | Probability | Impact | Score | Status | Owner | Mitigation Plan |
|---|---|---|---|---|---|---|---|
| Geotechnical Risk — Unexpected Soil Condition at B3 Level | Technical | Medium | High | 12 | Open | Engr. Salahuddin Mahmud | Conduct additional soil boring at 4 corners before B3 excavation. Engage geotechnical consultant for review. |
| Material Price Escalation — Steel Reinforcement | Commercial | High | High | 16 | Open | Project Manager | Lock prices via advance PO with suppliers. 10% material contingency in budget. |
| Weather Delay — Monsoon Season Excavation | External | High | Medium | 12 | In Progress | Site Engineer — Rahim | Ensure adequate dewatering pumps. Schedule critical excavation in dry months. Tarpaulin cover for open excavations. |
| Labour Shortage — Skilled Steel Fixers | Resource | Medium | High | 12 | Mitigated | HR Manager | Engage RMG Construction as backup. Cross-train 5 general workers. |
| Design Change — Façade System Revision | Technical | Low | Very High | 10 | Open | Design Manager | Early engagement with architect. Monthly design review meetings. |

**Risk Scoring Reference:**
| | Very Low (1) | Low (2) | Medium (3) | High (4) | Very High (5) |
|---|---|---|---|---|---|
| Very Low (1) | 1 | 2 | 3 | 4 | 5 |
| Low (2) | 2 | 4 | 6 | 8 | 10 |
| Medium (3) | 3 | 6 | 9 | 12 | 15 |
| High (4) | 4 | 8 | 12 | 16 | 20 |
| Very High (5) | 5 | 10 | 15 | 20 | 25 |

Score ≤ 5 = Low, 6-12 = Medium, 15-25 = High.

**Validation:** ✓ Risk score = Probability × Impact (auto-calculated). ✓ Risk score color-coded (green/yellow/red). ✓ Status workflow: open → in_progress → mitigated → closed.

---

### 16.12 CRM — Leads, Clients, Proposals

**1. Leads (Create)**

| Company | Contact Person | Phone | Email | Source | Est. Value | Status | Notes |
|---|---|---|---|---|---|---|---|
| Bashundhara Group (New Project) | Mr. Minhaz Ahmed | 01711-111222 | minhaz@bashundhara.com | Industry Referral | 350,000,000.00 | Negotiation | Proposed for 15-storey residential tower at Purbachal. Initial meeting completed. BOQ under preparation. |
| Miya Construction — Dhaka Office | Engr. Sirajul Islam | 01722-222333 | sirajul@miyaconstruction.com | Tender Portal | 125,000,000.00 | Proposal Sent | 8-storey commercial building at Uttara. Proposal submitted 2026-07-20. Awaiting response. |
| Green Delta Properties | Ms. Nusrat Jahan | 01933-333444 | nusrat@gdeltaproperties.com | Company Website | 75,000,000.00 | New | Inquired via website contact form. Follow up call scheduled. |

**Validation:** ✓ Lead status workflow: New → Contacted → Proposal Sent → Negotiation → Won/Lost. ✓ Communication log entries visible.

**2. Clients (Create)**

| Company | Contact Person | Email | Phone | Address | Tax ID | Trade License | Status |
|---|---|---|---|---|---|---|---|
| City Development Authority (CDA) | Mr. Abul Kalam Azad | akazad@cda.gov.bd | 01711-444555 | CDA Bhaban, 12 Agargaon, Dhaka-1207 | TAX-45678-0101 | TL-2024-08901 | Active |
| ABC Holdings Ltd. | Ms. Tahmina Begum | tahmina@abcholdings.com | 01722-555666 | ABC Tower, 54 Motijheel C/A, Dhaka-1000 | TAX-78901-0202 | TL-2025-01234 | Active |

**Link Project #1 (City Centre Commercial Tower) to ABC Holdings Ltd. as the client.**

**Validation:** ✓ Client portal login (associate a user with client role). ✓ Client sees only their projects/invoices.

**3. Proposals (Create)**

| Title | Lead | Client | Valid Until | Subtotal | Tax | Total | Status |
|---|---|---|---|---|---|---|---|
| Proposal for 8-Storey Commercial Building — Uttara | Miya Construction | (select Client) | 2026-09-15 | 125,000,000.00 | 5% | 131,250,000.00 | Sent |

**Proposal Items:**
| Description | Qty | Unit | Unit Price | Total |
|---|---|---|---|---|
| Structural Works (RCC Frame) | 1.00 | Lump Sum | 72,000,000.00 | 72,000,000.00 |
| Architectural Finishing | 1.00 | Lump Sum | 28,000,000.00 | 28,000,000.00 |
| MEP Installation | 1.00 | Lump Sum | 15,000,000.00 | 15,000,000.00 |
| External Development | 1.00 | Lump Sum | 10,000,000.00 | 10,000,000.00 |

**Validation:** ✓ Proposal number auto-generated. ✓ Status: draft → sent → accepted/rejected. ✓ Total matches items.

---

### 16.13 Contract Management — Contracts, Amendments, Claims, Closeout

**1. Contract (Create — main contract for City Centre Tower)**

| Field | Value |
|---|---|
| Contract Number | CTR-20260110-0001 |
| Title | Main Construction Contract — City Centre Commercial Tower |
| Client Name | ABC Holdings Ltd. |
| Project | City Centre Commercial Tower |
| Contract Type | Main Contract |
| Contract Value | 850,000,000.00 |
| Currency | BDT |
| Signing Date | 2026-01-10 |
| Commencement Date | 2026-01-15 |
| Completion Date | 2028-06-30 |
| Retention Percentage | 5.00% |
| Liquidated Damages Rate | 0.05% per day of contract value (max 10%) |
| Advance Payment Percentage | 10.00% |
| Status | Active |

**Validation:** ✓ Contract number auto-generated. ✓ PDF export shows professional format. ✓ Status transitions work.

**2. Contract Amendment (Create)**

| Title | Type | Description | Cost Impact | Time Impact | Status |
|---|---|---|---|---|---|
| AMD-001: Additional Floor (F26) — Shell & Core | Scope Change | Client requested additional floor (F26) as per revised architectural plan. Shell & core only: structure, external cladding, basic MEP stub. | 32,000,000.00 | +45 days | Approved |

**Validation:** ✓ Amendment number auto-generated. ✓ Revised contract value = 850,000,000 + 32,000,000 = 882,000,000.00.

**3. Contract Claim (Create)**

| Title | Type | Claimed Amount | Claimed Days | Granted Amount | Granted Days | Status |
|---|---|---|---|---|---|---|
| CLM-001: Delay due to Utility Relocation — 21 days | Time Extension | 0.00 | 21 | 14 | 14 | Granted |

**Validation:** ✓ Claim number auto-generated. ✓ Status: draft → submitted → under_review → granted/partially_granted/rejected.

**4. Contract Closeout Checklist (Create items)**

For City Centre Contract (will be used when project nears completion):

| Order | Item | Description |
|---|---|---|
| 1 | Final Inspection Certificate | Obtain final inspection sign-off from client/consultant |
| 2 | As-Built Drawings Submission | Submit complete set of as-built drawings (architectural, structural, MEP) |
| 3 | Operations & Maintenance Manuals | Submit O&M manuals for all installed MEP equipment |
| 4 | Final Account & Reconciliation | Submit final cost statement and resolve all variations |
| 5 | Retention Release Application | Apply for release of retention amount (5% = 42,500,000.00 initially) |
| 6 | Defect Liability Period Commencement | Start 12-month defect liability period from handover |
| 7 | Final Payment Certificate | Obtain final payment certificate from engineer/consultant |
| 8 | All Bank Guarantees Returned | Ensure performance & advance payment guarantees returned by client |
| 9 | Staff & Equipment Demobilized | Demobilize all project resources from site |
| 10 | Insurance Policies Expired/Closed | Close all project-specific insurance policies |

**Validation:** ✓ Checklist items ordered by index. ✓ All items must be checked before contract status can be "Completed".

---

### 16.14 Engineering Calculations — Concrete Ratio & Rod Calculation

> [!NOTE]
> Architectural Update: Rod Calculation (Bar Bending Schedule) and Concrete Ratio (Mix Design) have been relocated to dedicated Engineering folders (`App\Http\Controllers\Admin\Engineering\` and `resources/views/admin/engineering/`) for optimal ERP modularity.

**1. Concrete Ratio (Create under Project #1)**

| Reference No | Title | Grade | Waste % | Status |
|---|---|---|---|---|
| CR-001 | B1 Level Column Concrete — 35 MPa | 35 MPa | 2.00% | Approved |

**Members (mix design per m³ — 35 MPa using 420 kg/m³ cement):**
| Description | Volume (m³) | Cement (bags) | Sand (m³) | Aggregate (m³) | Water (L) | Admixture (L) |
|---|---|---|---|---|---|---|
| Column C1-C12 (24 columns, 0.35m³ each) | 8.4000 | 70.00 | 4.2000 | 7.1400 | 1,176.00 | 16.80 |
| Column C13-C24 (24 columns, 0.30m³ each) | 7.2000 | 60.00 | 3.6000 | 6.1200 | 1,008.00 | 14.40 |

**Totals:** Volume = 15.6000 m³, Cement = 130 bags, Sand = 7.8000 m³, Aggregate = 13.2600 m³, Water = 2,184.00 L

*Note: 35 MPa mix ratio ≈ 1:1.5:3 by volume. Cement content: 420 kg/m³ ÷ 50 kg/bag = 8.4 bags/m³. For 15.6 m³: 15.6 × 8.4 = 131 bags (rounded to 130).*

**Validation:** ✓ Ratio calculations correct (cement: 15.6 × 7.14 bags/m³ ≈ 111 bags — adjust per actual mix design). ✓ Summary totals computed.

**2. Rod Calculation (Create under Project #1)**

| Reference No | Title | Steel Grade | Waste % | Status |
|---|---|---|---|---|
| ROD-001 | B1 Level Column Reinforcement — 24 Columns | Grade 60 | 3.00% | Approved |

**Members (reinforcement groups):**
| Name | Qty of Same | Bar | Ø (mm) | No of Bars | Length (m) | Unit Wt (kg/m) | Total Wt (kg) |
|---|---|---|---|---|---|---|---|
| Column C1-C12 — Main Vertical | 12 | Long | 20 | 8 | 4.50 | 2.466 | 1,065.31 |
| Column C1-C12 — Stirrups | 12 | Stirrup | 10 | 28 | 1.40 | 0.617 | 290.24 |
| Column C13-C24 — Main Vertical | 12 | Long | 20 | 6 | 4.50 | 2.466 | 798.98 |
| Column C13-C24 — Stirrups | 12 | Stirrup | 10 | 24 | 1.20 | 0.617 | 213.26 |

**Calculation Verification (first row):**
- Unit Wt = (20²/162) = **2.469 kg/m** (standard formula: d²/162)
- Total = 12 columns × 8 bars × 4.50m × 2.466 kg/m = **1,065.31 kg**

**Validation:** ✓ Total weight = sum of all member weights. ✓ Bar unit weight calculated as d²/162. ✓ With waste (3%): total +3%.

---

### 16.15 Approval Workflow Configuration (System Settings)

**1. Create an Approval Workflow**

Navigate to: Settings → Approval Workflows → Create New

| Field | Value |
|---|---|
| Name | Purchase Requisition Approval (> 1,000,000 BDT) |
| Document Type | purchase_requisition |
| Description | Two-level approval for PRs exceeding 1,000,000 BDT |
| Status | Active |

**Approval Matrix Levels:**
| Level | Role | Min Amount | Max Amount |
|---|---|---|---|
| 1 | Admin | 1,000,000.00 | 5,000,000.00 |
| 2 | Super-Admin | 5,000,000.00 | 999,999,999.99 |

**Test the Workflow:**
1. Create a PR for 4,000,000.00 BDT
2. Submit for Approval → Level 1 (Admin) must approve
3. Create another PR for 8,000,000.00 BDT
4. Submit for Approval → Level 1 (Admin) must approve → Level 2 (Super-Admin) must approve

**Validation:** ✓ Workflow correctly routes based on amount threshold. ✓ Level progression works. ✓ Approval history recorded. ✓ Rejection stops the workflow.

---

### 16.16 System Settings Configuration

Navigate to: Settings → System Settings

| Setting Key | Value |
|---|---|
| company_name | Inoodex Construction Ltd. |
| company_address | 42 Gulshan Avenue, Gulshan-1, Dhaka-1212, Bangladesh |
| company_phone | +880-2-9876543 |
| company_email | info@inoodex.com.bd |
| company_website | www.inoodex.com.bd |
| company_logo | (upload company logo image) |
| financial_year_start | 2026-07-01 |
| financial_year_end | 2027-06-30 |
| currency_symbol | ৳ |
| currency_code | BDT |
| default_tax_rate | 5.00 |
| default_retention_rate | 5.00 |

**Validation:** ✓ Settings persist and load globally. ✓ Company info appears on PDF exports. ✓ Currency symbol displayed throughout.

---

### 16.17 Reports & Analytics — Verification

Navigate to Reports section and verify each report type:

| Report | Validation Criteria |
|---|---|
| **Dashboard KPIs** | Total projects (1 active), total budgets (850M), total invoices (2), pending approvals |
| **Budget vs Actual** | Shows all 7 budget codes with variance. 01-100: 2.5M favorable. |
| **Invoice Status** | Invoice #1: Fully Paid. Invoice #2: Partially Paid (10M of 19.69M). |
| **Cash Flow** | Inflow (44.625M from payment), Outflow (4.79M from bill) = Net positive |
| **Procurement Spend** | PO total (4.80M) + GRN total (partial 3.07M) with dual ApexCharts (Vendor & Project) |
| **Project Cost Summary** | Costs broken down: Budget, Actual, PO spend, Resource, Utilization % & ApexChart bar comparison |
| **S-Curve / Progress** | Shows project progress % over time based on task data |
| **Retention Tracker** | IPA retention & Invoice retention held/released/pending with horizontal ApexChart |
| **Labour & Equipment Utilisation** | Manpower, Machinery & Material spend with Donut chart & Stacked project allocation bar |
| **Aging Report (AR)** | Invoice outstanding by 0-30, 31-60, 61-90, 90+ day brackets & ApexChart Donut distribution |
| **Aging Report (AP)** | Bill outstanding by 0-30, 31-60, 61-90, 90+ day brackets, Vendor filter & ApexChart Donut |

---

### 16.18 End-to-End Testing Scenarios

These cross-module scenarios validate the system's integration points:

**Scenario 1: Procurement-to-Payment Cycle**
```
Materials low in Stock → PR created → PR Approved → PO Created → PO Approved
→ GRN Created → Stock Updated → Bill Received from Vendor → Payment Recorded
→ Journal Entry Auto-posted → GL Updated → Cash decreased, Inventory increased
```

**Scenario 2: Project Progress Billing**
```
Task progress updated → IPA Created (applied amount) → IPA Certified → IPA Approved
→ Invoice Generated from IPA → Invoice Sent to Client → Payment Received →
Journal Entry Posted → Receivable Cleared → Cash increased, Revenue recognized
```

**Scenario 3: Quality Issue Resolution**
```
NCR Raised (defect identified) → Root Cause Analysis → Corrective Action (CAR)
→ CAR Completed → NCR Closed → Punch List Updated → Lesson Learned Documented
```

**Scenario 4: Contract Change Management**
```
Client requests scope change → RFI Raised → RFI Answered → Change Order Created
→ Change Order Approved → Contract Amendment Created → Contract Value Revised
→ Revised Contract Value reflected on reports
```

### 16.18 Global Header Search Audit & Module Coverage

> [!NOTE]
> Global header search (`SearchController.php`) has been audited and updated to capture 100% of all 10 ERP categories and 50+ submodules matching the active sidebar hierarchy.

| Category | Modules Captured in Global Search |
|---|---|
| **Core Operations** | Projects, Sites & Field Locations, Site Logs, Site Photos, Tasks, Phases, Milestones, Resources, Allocation Chart (Gantt), Work Orders, Execution Inspections |
| **Engineering & Estimating** | Bill of Quantities (BOQ), Rate Analysis, Tenders & Bids, Rod Calculations (BBS), Concrete Ratios (Mix Design) |
| **Documents & Contracts** | Drawings & Blueprints, RFIs, Change Orders, Transmittals, Contracts, Amendments, Claims, Closeout Checklists |
| **Procurement & Inventory** | Vendors & Suppliers, Materials, Material Submittals, Warehouses, RFQs, Purchase Requisitions, Purchase Orders (PO), Goods Received Notes (GRN), Stocks, Material Transfers, Issue Slips, Material Wastage, Material Reconciliation, Subcontractors, Subcontract Agreements, Progress Payments |
| **HR & Safety** | Employees, Daily Attendance Register, Monthly Summary, Timesheets, Leave Requests, Payroll Wage Slips, Equipment Fleet, Fuel Logs, PPE Issuance, Safety Incidents, HSE Checklists, Toolbox Talks, Permits to Work (PTW), Safety Audits, Training Records, Certifications |
| **CRM** | Leads, Clients & Customers, Commercial Proposals |
| **Quality Control** | Non-Conformance Reports (NCR), Corrective Actions (CAR), Inspection & Test Plans (ITP), Punch Lists (Snagging), Material Test Certificates, Risk Register |
| **Finance & Accounting** | Sales Invoices, Interim Payment Applications (IPA), Bills Payable (AP), Expense Vouchers, Budgets, Budget Forecasting, Cost Alerts, Labour Cost, Material Takeoffs (MTO), Chart of Accounts (COA), Journal Vouchers, General Ledger, Trial Balance, Bank Reconciliation, Accounts Receivable (AR), Bank Guarantees, Balance Sheet, Income Statement (P&L), Payment Accounts |
| **Reports & Analytics** | Budget vs Actual, Project Cost Summary, Invoice Status, Cash Flow, Retention Tracker, Progress S-Curve, Labour & Equipment Utilisation, Procurement Spend, AR Aging, AP Aging |
| **Administration** | Users, Roles, Privileges, System Settings, Categories & Taxonomies |

---

### 16.19 Test Completion Checklist

After completing all manual tests, verify:

| # | Check | Status (✓/✗) |
|---|---|---|
| 1 | All 7 module groups have been tested with real data | ⬜ |
| 2 | All CRUD operations work (Create, Read, Update, Delete) for each entity | ⬜ |
| 3 | Numeric calculations are accurate (no rounding errors) | ⬜ |
| 4 | Date fields display and save correctly across time zones | ⬜ |
| 5 | Status workflows transition correctly (e.g., Draft → Submitted → Approved) | ⬜ |
| 6 | Cross-module links work (e.g., PR → PO → GRN → Stock) | ⬜ |
| 7 | PDF/Excel exports render with correct data and formatting | ⬜ |
| 8 | Dashboard KPIs reflect the test data entered | ⬜ |
| 9 | Global search returns relevant module records | ⬜ |
| 10 | Filters and search work on all index pages | ⬜ |
| 11 | Double-entry accounting balances (debits = credits) | ⬜ |
| 12 | Chart of Accounts hierarchy displays correctly | ⬜ |
| 13 | EVM calculations (SPI, CPI, ETC, EAC) match manual computation | ⬜ |
| 14 | Approval workflow routes correctly based on amount thresholds | ⬜ |
| 15 | All sidebar navigation items link to correct modules | ⬜ |

---

*End of Manual Testing Guide. Total test data sets cover all 22 modules with production-grade, contextually relevant values for a mid-sized commercial construction project in Bangladesh (BDT currency).*
