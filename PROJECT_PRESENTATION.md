# 🏗️ Construction ERP — Workflow Walkthrough

## Inoodex

This document walks through a **real construction project scenario** to demonstrate how the system works end-to-end — from project kickoff to final payment.

---

## The Scenario

**Project:** Construction of "Green Tower" — a 12-story commercial building in Dhaka
**Duration:** 18 months
**Contract Value:** ৳45,00,00,000 (45 Crore BDT)
**Client:** ABC Developers Ltd.
**Contractor:** Inoodex Construction Ltd.

---

## Stage 1: Project Setup & Planning

### 1.1 Create the Project

The system admin logs in and creates the project:

```
Project Name: Green Tower
Type: Building Construction
Status: Planning
Budget: ৳45,00,00,000
Start Date: Jan 2026
End Date: Jun 2027
```

The project appears on the **Dashboard** immediately — active project count updates, budget allocation pie chart updates.

### 1.2 Define Sites

The construction site is registered:

```
Site: Green Tower - Main Site
Location: Gulshan, Dhaka
Status: Active
```

This site will later collect **site diary entries**, **site photos**, and **inspection checklists**.

### 1.3 Create Work Breakdown Structure (Phases)

The project is broken into phases with order:

```
Phase 1: Site Preparation & Earthwork
Phase 2: Substructure (Foundation)
Phase 3: Superstructure (12 Floors)
Phase 4: Architectural Finishes
Phase 5: MEP (Mechanical, Electrical, Plumbing)
Phase 6: External Works & Handover
```

### 1.4 Set Milestones

Key payment-triggering milestones are defined:

```
M1: Foundation Complete — Target: Mar 2026
M2: Structural Framework (6th Floor) — Target: Jul 2026
M3: Structural Framework (Top) — Target: Nov 2026
M4: Finishes Complete — Target: Apr 2027
M5: Handover — Target: Jun 2027
```

### 1.5 Create Tasks

Tasks are created under each phase with assignees, priorities, and dependencies:

```
Phase: Superstructure
├── Task: Cast Column (Floor 1) — Priority: High — Progress: 0%
├── Task: Cast Slab (Floor 1) — Priority: High — Depends on: Column
├── Task: Brickwork (Floor 1) — Priority: Medium — Depends on: Slab
└── Task: Plaster (Floor 1) — Priority: Low — Depends on: Brickwork
```

The **Gantt chart** view shows the full schedule with dependencies.

---

## Stage 2: Estimating & Budgeting

### 2.1 Create Bill of Quantities (BOQ)

The quantity surveyor creates the BOQ with line items pulled from the contract:

```
BOQ: Green Tower Main Contract
├── Item: Earthwork Excavation — 5,000 m³ @ ৳350 = ৳17,50,000
├── Item: PCC (1:3:6) — 500 m³ @ ৳5,200 = ৳26,00,000
├── Item: RCC (1:1.5:3) — 3,200 m³ @ ৳12,500 = ৳4,00,00,000
├── Item: Brickwork — 15,000 m² @ ৳950 = ৳1,42,50,000
├── Item: Plaster — 22,000 m² @ ৳280 = ৳61,60,000
├── Item: Tile Flooring — 8,000 m² @ ৳1,100 = ৳88,00,000
├── Item: Electrical Works — 1 Lot @ ৳2,50,00,000 = ৳2,50,00,000
├── Item: Plumbing Works — 1 Lot @ ৳1,80,00,000 = ৳1,80,00,000
└── Item: Finishing — 1 Lot @ ৳1,50,00,000 = ৳1,50,00,000
```

> *Items can be imported via **Excel** using the import template.*

### 2.2 Rate Analysis

For each BOQ item, the rate is broken down into components:

```
RCC (1:1.5:3) per m³ — Total Rate: ৳12,500
├── Material: Cement — 5 bags @ ৳520 = ৳2,600
├── Material: Sand — 0.45 m³ @ ৳1,200 = ৳540
├── Material: Aggregate — 0.90 m³ @ ৳2,800 = ৳2,520
├── Labour: Mason — 2 @ ৳1,100 = ৳2,200
├── Labour: Helper — 4 @ ৳800 = ৳3,200
├── Equipment: Mixer — 0.5 day @ ৳2,500 = ৳1,250
└── Overhead: 5% = ৳595
```

### 2.3 Set Budgets by Cost Code

Budgets are created with cost codes matching the WBS:

```
Cost Code: 01.01 — Site Preparation — Budget: ৳50,00,000
Cost Code: 02.01 — Foundation — Budget: ৳1,20,00,000
Cost Code: 03.01 — Superstructure — Budget: ৳18,00,00,000
Cost Code: 04.01 — Finishes — Budget: ৳5,00,00,000
Cost Code: 05.01 — MEP — Budget: ৳4,80,00,000
...
```

---

## Stage 3: Tendering & Procurement

### 3.1 Issue Tenders

A tender is created for the MEP works package:

```
Tender: MEP Works Package
Issue Date: 15 Feb 2026
Close Date: 10 Mar 2026
Status: Open
```

Vendors submit bids, which are recorded in the system with technical and financial scores:

```
Bid: ElectroTech Ltd. — ৳4,20,00,000 — Tech: 85 | Fin: 78 | Total: 163
Bid: PowerPlus Engineering — ৳4,50,00,000 — Tech: 92 | Fin: 85 | Total: 177
Bid: GreenMEP Solutions — ৳3,95,00,000 — Tech: 70 | Fin: 92 | Total: 162
```

PowerPlus Engineering wins with the highest combined score.

### 3.2 Create Purchase Orders

Once awarded, a PO is raised against the vendor:

```
PO: MEP Works - PowerPlus Engineering
Amount: ৳4,50,00,000
Status: Ordered
Items: As per tender BOQ
```

### 3.3 Receive Materials (GRN)

When materials arrive on site, a Goods Received Note is created:

```
GRN: Against PO-2026-001
Item: 500 bags Cement @ ৳520 — Qty OK
Item: 200 rods TMT Bar 16mm @ ৳1,850 — Qty OK
Status: Received
```

Inventory stock levels update automatically. If cement stock drops below 50 units, a **low stock alert** appears on the dashboard.

---

## Stage 4: Execution & Site Management

### 4.1 Daily Site Diary

The site engineer records daily activities:

```
Site Log — 05 Apr 2026
Weather: Sunny, 32°C
Work Done: Floor 3 slab casting completed (320 m³)
Workers Present: 45
Issues: Concrete pump breakdown — resolved by 11 AM
```

### 4.2 Site Photos

Photos are uploaded with captions for visual documentation:

```
Photo: Floor 3 Reinforcement — 05 Apr 2026
Caption: Steel reinforcement inspection before casting — spacing OK
```

### 4.3 Inspection Checklists

QA/QC inspections are recorded:

```
Checklist: Floor 3 Slab Inspection
├── Reinforcement spacing: ✅ Pass
├── Cover block placement: ✅ Pass
├── Shuttering alignment: ✅ Pass
├── Concrete slump test: ✅ Pass (75mm)
└── Curing arrangement: ✅ Pass
```

### 4.4 Progress Updates

Task progress is updated as work completes:

```
Task: Cast Slab (Floor 3) → 100% Complete
Milestone: Structural Framework (6th Floor) → 60% Complete
```

The **S-Curve report** automatically updates, showing planned vs actual progress.

---

## Stage 5: Financial Management

### 5.1 Interim Payment Application (IPA)

At the end of each month, a progress claim is submitted:

```
IPA-001 — Period: 01-31 Mar 2026
Applied Amount: ৳3,25,00,000
├── Foundation Work: 95% complete — ৳95,00,000
├── Floor 1-3 Structure: 100% — ৳1,80,00,000
└── Floor 4-6 Structure: 30% — ৳50,00,000

Submitted by: Project Manager → Status: Submitted
```

### 5.2 Engineer Certification

The engineer reviews and certifies:

```
Certified Amount: ৳3,10,00,000
Retention (5%): ৳15,50,000
Net Payable: ৳2,94,50,000
Certificate: IPA-001 certified with 5% deduction for minor defects
```

Status changes: **Submitted** → **Certified**

### 5.3 Invoice Generation

From the certified IPA, an invoice is generated with one click:

```
Invoice INV-20260401-XXXX
Amount: ৳2,94,50,000
Status: Sent (due in 30 days)
```

The invoice is linked back to the IPA for full audit trail.

### 5.4 Payment Recording

When the client pays, the payment is recorded:

```
Payment against INV-20260401-XXXX
Amount: ৳2,94,50,000
Date: 20 Apr 2026
Method: Bank Transfer
Reference: TRX-2026-0420-001
```

Invoice status updates: **Paid** ✅

### 5.5 Vendor Bills (AP)

On the payables side, the MEP contractor submits their bill:

```
Bill: PowerPlus Engineering — April Works
Amount: ৳35,00,000
Due Date: 15 May 2026
Status: Approved
```

When paid:
```
Payment: ৳35,00,000 — 10 May 2026
Bill Status: Paid ✅
```

---

## Stage 6: Monitoring & Alerts

### 6.1 Cost Overrun Alerts

The system continuously monitors budgets vs actuals:

```
🔴 CRITICAL ALERT — Cost Code: 03.01 (Superstructure)
   Budget: ৳18,00,00,000 | Actual: ৳22,50,00,000 | Variance: 125%
   Steel price escalation exceeded budget by 25%

🟡 WARNING — Cost Code: 04.01 (Finishes)
   Budget: ৳5,00,00,000 | Actual: ৳4,20,00,000 | Variance: 84%
   Approaching budget limit
```

The project manager can **Acknowledge** or **Resolve** alerts with notes.

### 6.2 Aging Reports

**AR Aging** shows what clients owe:

| Bucket | Amount |
|--------|--------|
| Current | ৳2,94,50,000 |
| 1-30 Days | ৳0 |
| 31-60 Days | ৳0 |
| 61-90 Days | ৳0 |
| 90+ Days | ৳0 |
| **Total** | **৳2,94,50,000** |

**AP Aging** shows what the contractor owes:

| Bucket | Amount |
|--------|--------|
| Current | ৳35,00,000 |
| 1-30 Days | ৳0 |
| 31-60 Days | ৳0 |
| 61-90 Days | ৳0 |
| 90+ Days | ৳0 |
| **Total** | **৳35,00,000** |

---

## Stage 7: Reports

### 7.1 Budget vs Actual — Monthly Summary

```
Cost Code      | Budget       | Actual       | Variance | %
01.01 Site Prep| ৳50,00,000  | ৳48,00,000  | +৳2,00,000 | 96%
02.01 Foundation| ৳1,20,00,000 | ৳1,15,00,000 | +৳5,00,000 | 96%
03.01 Structure | ৳18,00,00,000| ৳22,50,00,000| -৳4,50,00,000| 125% 🔴
04.01 Finishes  | ৳5,00,00,000 | ৳4,20,00,000 | +৳80,00,000| 84% 🟡
```

### 7.2 S-Curve — Progress Schedule

```
Planned: ████████████████░░░░░░░░ 65%
Actual:  ██████████████░░░░░░░░░░ 58%
```

The S-Curve shows the project is **7% behind schedule** — flagged for management attention.

### 7.3 Cash Flow Projection

```
Month        | Inflow        | Outflow       | Net
Apr 2026     | ৳2,94,50,000 | ৳1,85,00,000 | +৳1,09,50,000
May 2026     | ৳0           | ৳95,00,000   | -৳95,00,000
Jun 2026     | ৳3,50,00,000 | ৳2,10,00,000 | +৳1,40,00,000
...
```

### 7.4 Retention Tracker

```
Project     | Total Retained | Released   | Pending
Green Tower | ৳55,25,000     | ৳0         | ৳55,25,000
```

---

## Stage 8: Approval Workflow

Throughout the project lifecycle, documents flow through approvals:

```
Purchase Requisition (৳5,00,000+)
  → Site Engineer (Level 1)
  → Project Manager (Level 2)
  → Director (Level 3) — if amount > ৳10,00,000

Invoice (৳50,00,000+)
  → Quantity Surveyor (Level 1)
  → Project Manager (Level 2)
  → Managing Director (Level 3)
```

Each approval is recorded with timestamp and user — **full audit trail**.

---

## System Summary

### What the System Covers

| Stage | Module | Status |
|-------|--------|--------|
| Project Setup | Projects, Sites, Phases, Milestones | ✅ |
| Planning | Tasks, Gantt, Work Orders, Checklists | ✅ |
| Estimating | BOQ, Rate Analysis, Budgets | ✅ |
| Tendering | Tenders, Bid Evaluation, Awards | ✅ |
| Procurement | PR, PO, GRN, Vendors | ✅ |
| Inventory | Warehouses, Stocks, Transfers, Wastage | ✅ |
| Site Execution | Site Diary, Photos, Inspections | ✅ |
| Progress Billing | IPAs (Submit → Certify → Approve → Invoice) | ✅ |
| Accounts Receivable | Invoices, Payments, AR Aging | ✅ |
| Accounts Payable | Bills, Payments, AP Aging | ✅ |
| Cost Control | Cost Overrun Alerts, Budget vs Actual | ✅ |
| Reports | S-Curve, Cash Flow, Retention, 8 report types | ✅ |
| Approvals | Multi-level, amount-based, role-configured | ✅ |
| HR / Payroll | — | 📋 Planned |
| Document Control | — | 📋 Planned |
| Bank Guarantees | — | 📋 Planned |

---

## Key Benefits for Your Business

1. **Visibility** — Know exactly where every project stands at all times
2. **Cost Control** — Automated alerts prevent budget overruns from going unnoticed
3. **Cash Flow** — AR/AP aging shows money-in vs money-out at a glance
4. **Efficiency** — IPAs generate invoices in one click — no re-entering data
5. **Audit Trail** — Every approval, payment, and change is timestamped
6. **Site-to-Office** — Site diaries and photos keep the office informed in real time
7. **Progress Tracking** — S-Curves and milestone tracking show true project health
8. **Procurement Pipeline** — From requisition to payment — one integrated flow

---

*Green Tower is a hypothetical scenario. The system is live and ready to manage your projects end-to-end.*

*For a live demonstration with your own project data, please contact us.*
