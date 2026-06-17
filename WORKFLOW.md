# Construction Management System — Workflow

## 1. Project Setup

- **1.1** Create a Project → _Core → Sites → Add Site_
- **1.2** Upload Site Photos → _Project detail → Site Photos_
- **1.3** Log Site Activity → _Core → Sites → Site Logs_

## 2. Planning

- **2.1** Create Phases → _Project detail → Phases → Add Phase_
- **2.2** Create Milestones → _Project detail → Milestones → Add Milestone_
- **2.3** Create Tasks → _Core → Planning → Tasks → Add Task_ (assign to project/phase/milestone)
- **2.4** Assign Resources → _Project detail → Resources_
- **2.5** Create Work Orders → _Core → Execution → Work Orders_
- **2.6** Inspection Checklists → _Core → Execution → Inspections_

## 3. Procurement

- **3.1** Reference Data
  - **3.1.1** Add Vendors → _Procurement → Reference Data → Vendors_
  - **3.1.2** Add Materials → _Procurement → Reference Data → Materials_
  - **3.1.3** Add Warehouses → _Procurement → Reference Data → Warehouses_
- **3.2** Create Requisition → _Procurement → Requisitions → Add_
- **3.3** Approve Requisition → _Approvals → Pending Approvals_
- **3.4** Create Purchase Order → _Procurement → Purchase Orders_ (from approved requisition)
- **3.4** Receive Goods → _Procurement → Goods Received_ (from PO)
- **3.5** Inventory Management
  - **3.5.1** Manage Stocks → _Procurement → Inventory → Stocks_
  - **3.5.2** Transfer Materials → _Procurement → Inventory → Material Transfers_
  - **3.5.3** Issue Slips → _Procurement → Inventory → Issue Slips_
  - **3.5.4** Record Wastage → _Procurement → Inventory → Material Wastage_

## 4. Finance

- **4.1** Cost Control
  - **4.1.1** Set Budgets → _Finance → Cost Control → Budgets_
  - **4.1.2** Monitor Cost Alerts → _Finance → Cost Control → Cost Alerts_
- **4.2** Estimating
  - **4.2.1** Create Bill of Quantities → _Finance → Estimating → BOQ_
  - **4.2.2** Rate Analysis → _Finance → Estimating → Rate Analysis_
  - **4.2.3** Manage Tenders & Bids → _Finance → Estimating → Tenders_
- **4.3** Billing & Payables
  - **4.3.1** Invoice Client → _Finance → Billing → Invoices_
  - **4.3.2** Interim Payment Applications → _Finance → Billing → IPAs_ (create → submit → certify → approve via Approvals → generate invoice)
  - **4.3.3** Bills Payable (AP) → _Finance → Billing → Bills Payable_
- **4.4** Aging Reports
  - **4.4.1** AR Aging → _Finance → Aging Reports → AR Aging_
  - **4.4.2** AP Aging → _Finance → Aging Reports → AP Aging_

## 5. Reports

- **5.1** Cost & Budgeting
  - **5.1.1** Project Cost Summary
  - **5.1.2** Budget vs Actual
- **5.2** Financial Status
  - **5.2.1** Invoice Status
  - **5.2.2** Cash Flow
  - **5.2.3** Retention Tracker
- **5.3** Progress & Procurement
  - **5.3.1** Progress S-Curve (interactive chart + PDF)
  - **5.3.2** Labour & Equipment Utilisation
  - **5.3.3** Procurement Spend
- **5.4** Export any report as PDF or Excel

## 6. Configuration

- **6.1** Approval Workflows (super-admin) → _Approvals → Approval Workflows_
