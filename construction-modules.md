# Construction Management System — Laravel Modules

> A reference guide for building a Construction Management ERP in Laravel.

## 📁 Module Groups

---

### 1. Core Project

#### 1.1 Project Management _(Must Have)_

- Projects, phases, milestones, timelines
- Gantt chart / schedule view
- Progress tracking (% complete) + S-curve tracking
- Critical path method (CPM) support
- Resource planning per project
- Multi-site / multi-project support
- Project status workflows (Planning → Active → On Hold → Completed)

#### 1.2 Task & Work Orders _(Must Have)_

- Task creation and assignment
- Work order generation
- Priority levels (Low / Medium / High / Critical)
- Status workflows (Open → In Progress → Review → Closed)
- Task dependencies and blocking relationships

#### 1.3 Site Management _(Must Have)_

- Site/location registry
- Daily site logs and field reports
- Inspection checklists
- Material delivery tracking at site level
- Weather log integration
- Site photo uploads

---

### 2. Finance & Accounting

#### 2.1 Budgeting & Cost Control _(Must Have)_

- Project budget creation
- Cost code / WBS (Work Breakdown Structure)
- Budget vs actual tracking
- Cost overrun alerts
- Forecast to complete (ETC / EAC)

#### 2.2 Estimating & BOQ _(Must Have)_

- Bill of Quantities (BOQ) builder
- Cost estimation per line item
- Rate analysis and unit rate library
- Material takeoff sheets
- BOQ import via Excel

#### 2.3 Tender Management _(Must Have)_

- Pre-qualification of bidders
- Tender package creation and distribution
- Bid submission tracking
- Bid evaluation and comparison matrix
- Award letters and tender closeout

#### 2.4 Invoicing & Accounts _(Must Have)_

- Client invoice generation (progress / milestone billing)
- Interim payment applications (IPA)
- Accounts payable / receivable
- Retention tracking and release
- Bank guarantee tracking
- Journal entries and general ledger
- Cash flow statements
- Tax / VAT handling

---

### 3. Procurement & Materials

#### 3.1 Vendor / Supplier Management _(Must Have)_

- Vendor profiles, contact details, trade categories
- Pre-qualification and approval status
- Vendor performance ratings
- Payment terms and credit limits
- Blacklist / suspension tracking

#### 3.2 Procurement _(Must Have)_

- Purchase requisitions (PR)
- Purchase orders (PO)
- Material approval / submittal process
- Vendor comparison and quotation analysis
- Goods received notes (GRN)
- Multi-level approval workflows

#### 3.3 Inventory & Warehouse _(Must Have)_

- Material stock management (multiple warehouses/sites)
- Site-to-site material transfers
- Material issue slips
- Wastage and loss tracking
- Reorder level alerts
- Material reconciliation reports

#### 3.4 Subcontractor Management _(Must Have)_

- Subcontractor pre-qualification
- Subcontract agreements
- Scope of work definitions
- Progress payment certificates
- Retention tracking
- Subcontractor performance scoring

---

### 4. HR & Equipment

#### 4.1 HR & Payroll _(Must Have)_

- Worker / employee profiles
- Attendance and timesheet tracking
- Labour cost allocation per project
- Wage slip generation
- Leave and overtime management
- Training records and certifications
- PPE issuance tracking

#### 4.2 Equipment & Assets _(Must Have)_

- Equipment registry and specifications
- Owned vs hired (rented) plant tracking
- Hire rates and hire period management
- Allocation to projects/sites
- Preventive maintenance schedules
- Fuel consumption logs
- Asset depreciation tracking

#### 4.3 Safety & Compliance _(Must Have)_

- HSE (Health, Safety & Environment) checklists
- Incident and accident reports
- Near-miss reporting
- Permits to work (PTW)
- Certification and licence tracking
- Toolbox talk records
- Safety audit records

---

### 5. Quality & Risk

#### 5.1 Quality Control / QA _(Must Have)_

- Inspection and test plans (ITP)
- Non-conformance reports (NCR)
- Defect / punch list / snagging list
- Material test certificates
- Concrete mix design approvals
- Quality audit records
- Corrective action tracking (CAR)

---

### 6. Client & Documentation

#### 6.1 Document Management _(Must Have)_

- Drawing register and revision control
- Shop drawings and as-built drawings
- Drawing transmittals
- RFIs (Request for Information)
- Submittal tracking
- Change orders / variation orders
- Version control and approval workflows

#### 6.2 Contract Management _(Must Have)_

- Main contract repository
- Contract variations / amendments
- Claims management
- Retention and milestone tracking
- Bank guarantee and insurance tracking
- Contract closeout checklist

---

### 7. System & Reporting

#### 7.1 Settings & Configuration _(Must Have)_

- Company profile and branding
- Financial year and currency setup
- Tax rate configuration
- Approval matrix / workflow configuration
- Number series for POs, invoices, work orders
- Email / SMS gateway settings
- Multi-company / multi-branch support (optional)

#### 7.2 Reports & Analytics _(Must Have)_

- Executive dashboard with KPIs
- Cost and financial reports
- Progress and schedule reports (S-curve)
- Labour and equipment utilisation reports
- Custom report builder
- Scheduled report delivery (email)
- PDF / Excel export

#### 7.3 Roles & Permissions _(Must Have)_

- RBAC (Role-Based Access Control) via Spatie
- Multi-role user support
- Module-level and record-level access control
- Project-scoped permissions
- Audit trail / activity log
- Permission groups per project

#### 7.4 Notifications & Communications _(Important)_

- In-app alert system
- Email and SMS triggers
- Project announcement board
- Approval and deadline reminders
- Notification preferences per user

---

## 🏗️ Recommended Build Order

Build modules in this sequence so each one has its dependencies ready:

```
1.  Settings & Configuration (company, currency, tax, approval matrix)
2.  Auth, Users, Roles & Permissions
3.  Vendor / Supplier Management
4.  Project Management + Site Management
5.  HR, Attendance & Payroll
6.  Estimating & BOQ  →  Tender Management  →  Budgeting & Cost Control
7.  Procurement       →  Inventory & Warehouse
8.  Task & Work Orders
9.  Subcontractor Management + Contract Management
10. Invoicing & Accounts (AP/AR, retention, bank guarantees)
11. Equipment & Assets
12. Quality Control / QA + Safety & Compliance
13. Risk Management
14. Document Management + CRM
15. Reports & Analytics + Notifications
```

---

## 🗂️ Suggested Laravel Module Structure

Using `nWidart/laravel-modules` for a modular monolith approach:

```
Modules/
├── System/
│   ├── Settings/
│   ├── Auth/
│   ├── Roles/
│   └── Notifications/
├── Core/
│   ├── Projects/
│   ├── Tasks/
│   └── Sites/
├── Finance/
│   ├── Budgeting/
│   ├── Estimating/
│   ├── Tendering/
│   └── Invoicing/
├── Procurement/
│   ├── Vendors/
│   ├── Purchasing/
│   ├── Inventory/
│   └── Subcontractors/
├── HR/
│   ├── Employees/
│   ├── Attendance/
│   └── Payroll/
├── Equipment/
├── Quality/
│   ├── QualityControl/
│   └── RiskManagement/
├── Safety/
├── Contracts/
├── Documents/
├── CRM/
└── Reports/
```

---

## ✅ Module Summary Table

| #   | Module                       | Group          | Priority  |
| --- | ---------------------------- | -------------- | --------- |
| 1   | Project Management           | Core Project   | Must Have |
| 2   | Task & Work Orders           | Core Project   | Must Have |
| 3   | Site Management              | Core Project   | Must Have |
| 4   | Budgeting & Cost Control     | Finance        | Must Have |
| 5   | Estimating & BOQ             | Finance        | Must Have |
| 6   | Tender Management            | Finance        | Must Have |
| 7   | Invoicing & Accounts         | Finance        | Must Have |
| 8   | Vendor / Supplier Management | Procurement    | Must Have |
| 9   | Procurement                  | Procurement    | Must Have |
| 10  | Inventory & Warehouse        | Procurement    | Must Have |
| 11  | Subcontractor Management     | Procurement    | Must Have |
| 12  | HR & Payroll                 | HR & Equipment | Must Have |
| 13  | Equipment & Assets           | HR & Equipment | Must Have |
| 14  | Safety & Compliance          | HR & Equipment | Must Have |
| 15  | Quality Control / QA         | Quality & Risk | Must Have |
| 16  | Document Management          | Client & Docs  | Must Have |
| 17  | Contract Management          | Client & Docs  | Must Have |
| 18  | Settings & Configuration     | System         | Must Have |
| 19  | Reports & Analytics          | System         | Must Have |
| 20  | Roles & Permissions          | System         | Must Have |

---

_Generated for Laravel Construction Management ERP project._
