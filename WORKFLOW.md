# Construction ERP — System Workflow

> Describes how the system works: architecture, data flows, and module interactions.

---

## 1. Authentication & Access Control

```
User → Login (email/password or OTP/2FA) → Role-based authorization
                                              ↓
                                     Super-admin gate bypasses all permission checks
```

- **Sanctum** for API auth (token-based)
- **Tyro Login** for web auth (OTP/2FA/social/captcha)
- **Roles & Permissions** via Tyro Dashboard — each route/menu item gated by permission
- **Super-admin** (`Gate::before` in `AuthServiceProvider`) — auto-allowed for everything

---

## 2. Core Project Module — Hierarchy

```
Project
  ├── Phases (ordered, e.g. Foundation → Superstructure → Finishing)
  │     └── Milestones (key deliverables with target dates)
  ├── Sites (physical locations — 1:N, each with address)
  ├── Tasks (individual work items)
  │     ├── Dependencies (N:M pivot — task blocks another task)
  │     ├── Resources (allocated via task_resources pivot)
  │     └── Progress % (manually updated, averaged into Project::progress)
  └── Budgets (cost control per project)
```

**Data flow:** Project created → Sites added → Phases/Milestones defined → Tasks created with dependencies → Resources allocated → Progress updated → Project progress bar auto-calculates.

---

## 3. Procurement Flow

```
         PR (Purchase Requisition)
         Site identifies need → PR created (draft)
                  ↓
         Approval Workflow (if configured)
                  ↓
         PO (Purchase Order)
         PR approved → PO created against vendor
                  ↓
         Approval Workflow (if configured)
                  ↓
         GRN (Goods Received Note)
         Goods arrive at site/warehouse → GRN created against PO
         Tracks: quantity received, delivery note, vehicle, site
                  ↓
         Stock Updated
         Warehouse inventory increases automatically
```

**Key rules:**
- PR status must be `approved` before it can be used in a PO
- PO can be created from PR (auto-populates items) or as direct order
- GRN can be partial (receives less than PO quantity)
- Material transfers move stock between warehouses/sites
- Material issue slips record consumption against a project
- Material wastage tracks losses (cut-off scraps, damage, theft)

---

## 4. Finance Flow

### 4.1 Invoicing & Payments

```
Invoice (draft)
  ├── Items added (description, qty, unit_price → total_price)
  ├── Tax & retention auto-calculated
  └── Status: draft
         ↓
   Sent to client → status = sent
         ↓
   Payment received → paid_amount updated → due_amount recalculated
         ↓
   Status auto-updates: partially_paid / paid
```

### 4.2 Interim Payment Application (IPA)

```
IPA (draft)
  ├── Links to BOQ items (tracks previous vs current quantities)
  ├── Applied amount → Certified amount (engineer may adjust)
  ├── Retention deducted → Net amount calculated
  └── Status: draft
         ↓
   Submitted → status = submitted
         ↓
   Certified → status = certified (engineer approves work done)
         ↓
   Approved → status = approved (manager signs off)
         ↓
   Invoice generated → status = paid (when payment received)
```

### 4.3 Journal Entries & Accounting

```
Chart of Accounts (tree hierarchy, e.g. 1-1010 Cash & Bank)
         ↓
Journal Entry (date, description, reference)
  ├── Journal Entry Item 1 (account_id, debit_amount)
  └── Journal Entry Item 2 (account_id, credit_amount)
         ↓
General Ledger (all journal entries grouped by account)
         ↓
Trial Balance (debits = credits)
         ↓
Financial Reports:
  ├── Balance Sheet (assets = liabilities + equity)
  ├── Income Statement (revenue − expenses = net income)
  └── Cash Flow (journal entries to Cash & Bank accounts)
```

### 4.4 Bills Payable (AP)

```
Bill (draft)
  ├── Items (material/service, qty, unit_price)
  ├── Tax, retention, TDS calculated
  └── Vendor payable
         ↓
   Bill Payment recorded → paid_amount updated → due recalculated
```

---

## 5. HR Flow

### 5.1 People Management

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

### 5.2 Leave Management

```
Leave Request (draft)
  ├── Type: Annual / Sick / Emergency
  ├── Date range + reason
  └── Status: pending
         ↓
   Approved / Rejected by supervisor
```

### 5.3 Equipment & Assets

```
Equipment Registry (owned or hired)
  ├── Owned: purchase cost, depreciation tracking
  ├── Hired: hire rate, period, vendor, start/end dates
  ├── Allocation to project/site
  ├── Fuel consumption logs (equipment × date × qty × cost)
  └── Maintenance records (preventive/corrective, cost, next due)
```

---

## 6. Approval Workflow

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

---

## 7. Reporting Flow

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

## 8. Module Interaction Diagram

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

  ┌──────────┐     ┌──────────────┐
  │ Journal  │────►│    Trial     │────► Financial
  │ Entries  │     │   Balance    │     Reports
  └──────────┘     └──────────────┘
```

---

## 9. Key Business Rules

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

---

## 10. Approval Menu — How It Works

### Menu Structure

```
Sidebar → Approvals
  ├── Pending Approvals   (all users — see what needs your approval)
  └── Approval Workflows  (super-admin only — configure approval rules)
```

### 10.1 Approval Workflows (Configuration)

Super-admins define **workflows** — one per document type:

```
Workflow: "Purchase Requisition Approval"
  ├── Document Type: purchase_requisition
  ├── Levels: 2
  └── Matrices:
        Level 1 → Role: Project Manager, Amount: ৳0 – ৳9,999,999
        Level 1 → Role: Site Supervisor, Amount: ৳0 – ৳9,999,999
        Level 2 → Role: Director, Amount: ৳10,000,000 – ৳999,999,999
```

Each **matrix row** defines:
- Which **role** can approve at that level
- **Amount range** they can authorise (min_amount → max_amount)
- Multiple roles at the same level = all must approve (parallel approval)
- Different levels = sequential (Level 1 must finish before Level 2)

### 10.2 Submission Flow

```
User creates a document (e.g. Purchase Requisition)
         ↓
User clicks "Submit for Approval" button on the show page
         ↓
Controller calls ApprovalService::submitForApproval(model, document_type, amount, user_id)
         ↓
Service looks up ApprovalWorkflow where document_type matches and is_active = true
         ↓
  ┌── No workflow found? → Model auto-approved (status = 'ordered')
  │
  └── Workflow found?
           ↓
        Approval record created (status = 'pending', current_level = 1)
           ↓
        Approvable model status unchanged (remains 'draft' or 'submitted')
```

### 10.3 Approval Process

```
Approver visits Pending Approvals → sees documents needing their sign-off
         ↓
Clicks "Review" → sees document details + approval history + approver list
         ↓
Two options:
  ├── APPROVE (optional comment)
  │     ↓
  │   Service checks: have all approvers at current level approved?
  │     ├── No → status stays pending, waiting for others
  │     └── Yes → is there a next level?
  │           ├── Yes → current_level++ (moves to Level 2, 3, etc.)
  │           └── No → status = 'approved' + model status updated:
  │                 ├── purchase_requisition → status = 'approved'
  │                 └── purchase_order → status = 'ordered'
  │
  └── REJECT (comment required)
        ↓
      status = 'rejected' + model status = 'rejected'

Submitter can WITHDRAW anytime while status = 'pending'
  → status = 'withdrawn' + model status returns to 'draft'
```

### 10.4 Who Can Approve?

Determined by role-amount matching at the current level:

```
User's role → matches ApprovalMatrix.role_id at current level
          AND
Document amount → between ApprovalMatrix.min_amount and max_amount
```

- A single user can have multiple roles and qualify through any of them
- Once a user approves at a level, they cannot approve again at the same level
- `getCurrentLevelApprovers()` returns all users who qualify
- `getRemainingApprovers()` filters out those who already approved

### 10.5 Approval History

Every approve/reject action is recorded in `approval_history`:

```
approval_id | level | approved_by | status | comment | approved_at
```

The show page displays a timeline of all actions with:
- Avatar initial + name of approver
- Level number
- Timestamp
- Comment (if any)

### 10.6 Models Using Approvable

| Model | document_type | Submit button location | Status on approve |
|-------|--------------|----------------------|-------------------|
| PurchaseRequisition | `purchase_requisition` | Show page | `approved` |
| PurchaseOrder | `purchase_order` | Show page | `ordered` |
| Invoice | `invoice` | (trait included, no UI yet) | — |
| Tender | `tender` | (trait included, no UI yet) | — |
| Budget | `budget` | (trait included, no UI yet) | — |

### 10.7 Sidebar Visibility

- **Pending Approvals** — visible to all authenticated users
- **Approval Workflows** — visible only to users with `super-admin` role

---

## 11. Technology Stack

| Layer | Technology |
|-------|------------|
| Framework | Laravel 12 (PHP 8.2+) |
| Frontend | Blade + Tailwind CSS v4 + Alpine.js |
| Build | Vite 7 |
| Admin Panel | Tyro Dashboard v1.7 + Tyro Login |
| Database | SQLite (dev) / MySQL (production) |
| PDF | barryvdh/laravel-dompdf |
| Excel | Maatwebsite/Laravel-Excel |
| Files | Spatie MediaLibrary |
| Auth | Sanctum (API), Tyro Login (web) |
