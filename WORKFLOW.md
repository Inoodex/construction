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
  └── Status: active → released / expired
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
| **Number generation** | Auto-generated: DRW-{PRJ}-{SEQ}, RFI-{PRJ}-{SEQ}, CO-{PRJ}-{SEQ}, TRM-{PRJ}-{SEQ} |
| **Drawing revision control** | Only one revision marked as `is_current` at a time |
| **File uploads** | Spatie MediaLibrary for drawings, RFIs, change orders, vendor docs, client docs |

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
  ├── Safety & Compliance (Incident Reports, HSE Checklists)
  └── Training (Training Records, Certifications & Licences)

CRM
  ├── Leads, Clients, Proposals

Quality Control
  ├── Non-Conformance (NCRs, Corrective Actions)
  ├── Inspections (ITPs, Punch Lists)
  └── Material Test Certificates

Finance
  ├── Cost Control (Budgets, Forecasting, Cost Alerts, Labour Cost)
  ├── Accounting (Chart of Accounts, Journal Vouchers, General Ledger, Trial Balance, AR, Balance Sheet, Income Statement)
  ├── Estimating Analysis (BOQs, Rate Analysis)
  └── Billing & Payables (Invoices, Interim Payment, Bills Payable, Expenses)

Reports
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
| Controllers | 76 |
| Models | 107 |
| Migrations | 111 |
| Blade View Directories | 85 |
| Route Groups | 9 major groups |
| Sidebar Sections | 8 (Admin, Core, Documents, Procurement, HR, CRM, Quality, Finance, Reports) |

---

_Last updated: 2026-07-11 — after Document Management module completion._
