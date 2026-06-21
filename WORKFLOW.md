# Construction ERP — Manual Testing Guide

> Walk through every module by entering realistic sample data.  
> Items marked `→` are navigation paths in the sidebar.

---

## 0. Prerequisites

| Step | Action | Sample Data |
|---|---|---|
| 0.1 | Login as super-admin | Email: `admin@admin.com` / Password: `password` |
| 0.2 | Create Settings → _Settings_ | Company Name: `BuildCorp Ltd`, Currency: `MYR`, Timezone: `Asia/Kuala_Lumpur` |
| 0.3 | Create Roles → _Settings → Roles_ | Role: `Project Manager` / `Site Supervisor` / `Accountant` / `HR Manager` |
| 0.4 | Create Users (assign roles) → _Settings → Users_ | `pm@buildcorp.my` (Project Manager), `hr@buildcorp.my` (HR Manager) |
| 0.5 | Categories → _Settings → Categories_ | `Concrete Works`, `Steel Works`, `MEP Works`, `Finishing` |

---

## 1. Core — Project Setup

| Step | Action | Sample Data |
|---|---|---|
| 1.1 | **Create Project** → _Core → Projects → Add Project_ | Name: `Greenfield Tower`, Code: `GFT-2026`, Location: `Kuala Lumpur`, Start: `2026-07-01`, End: `2027-06-30`, Status: `Active` |
| 1.2 | **Add Sites** → _Core → Sites → Add Site_ | Name: `Main Tower Site`, Project: `Greenfield Tower`, Address: `Jalan Ampang, KL` |
| 1.3 | **Upload Site Photos** → _Project detail → Site Photos_ | Upload any JPG (pre-construction site photo) |
| 1.4 | **Log Site Activity** → _Core → Sites → Site Logs_ | Date: today, Description: `Site clearing commenced`, Weather: `Sunny` |

---

## 2. Core — Planning

| Step | Action | Sample Data |
|---|---|---|
| 2.1 | **Create Phases** → _Project detail → Phases → Add Phase_ | Name: `Foundation`, Project: `Greenfield Tower`, Order: `1` |
| 2.2 | **Create Milestones** → _Project detail → Milestones → Add Milestone_ | Name: `Foundation Complete`, Phase: `Foundation`, Due: `2026-09-30` |
| 2.3 | **Create Tasks** → _Core → Planning → Tasks → Add Task_ | Name: `Excavation`, Project: `Greenfield Tower`, Phase: `Foundation`, Milestone: `Foundation Complete`, Assigned: `Site Supervisor`, Start: `2026-07-15`, End: `2026-08-15`, Priority: `High` |
| 2.4 | **Add second Task** → _Core → Planning → Tasks → Add Task_ | Name: `Concrete Pouring`, Project: `Greenfield Tower`, Phase: `Foundation`, Start: `2026-08-16`, End: `2026-09-15` |
| 2.5 | **Link Task Dependencies** → _Edit task → Dependencies_ | `Concrete Pouring` depends on `Excavation` |
| 2.6 | **Assign Resources** → _Project → Resources → Add_ | Resource: `Excavator`, Qty: `2`, Task: `Excavation` |
| 2.7 | **Update Task Progress** → _Tasks → Edit → Progress %_ | Set `Excavation` to `50%` — verify project progress bar updates |
| 2.8 | **Create Work Orders** → _Core → Execution → Work Orders_ | Title: `Excavation Work Order`, Project: `Greenfield Tower`, Task: `Excavation`, Status: `Open`, Priority: `Urgent` |
| 2.9 | **Create Inspection Checklist** → _Core → Execution → Inspections_ | Title: `Excavation Safety Check`, Site: `Main Tower Site` |
| 2.10 | **Add Checklist Items** → _Edit Inspection → Add Items_ | Item 1: `Batters at correct angle` (Pass/Fail), Item 2: `Barricades in place` (Pass/Fail) |

---

## 3. Procurement

| Step | Action | Sample Data |
|---|---|---|
| 3.1 | **Add Vendor** → _Procurement → Reference Data → Vendors_ | Name: `SteelPro Sdn Bhd`, Contact: `Ah Chong`, Phone: `012-3456789`, Email: `chong@steelpro.my`, Category: `Material Supplier`, Status: `Approved` |
| 3.2 | **Add Second Vendor** → _Vendors_ | Name: `ConcreteMix Sdn Bhd`, Contact: `Raj`, Status: `Approved` |
| 3.3 | **Add Material** → _Procurement → Reference Data → Materials_ | Name: `Rebar 16mm`, Unit: `ton`, Category: `Steel`, Unit Price: `3200` |
| 3.4 | **Add Second Material** → _Materials_ | Name: `Ready-Mix Concrete Grade 30`, Unit: `m³`, Category: `Concrete`, Unit Price: `280` |
| 3.5 | **Add Warehouse** → _Procurement → Reference Data → Warehouses_ | Name: `Main Yard`, Location: `Kuala Lumpur` |
| 3.6 | **Create Requisition (PR)** → _Procurement → Requisitions → Add_ | Title: `Rebar for Foundation`, Project: `Greenfield Tower`, Items: `Rebar 16mm × 50 ton`, Vendor: `SteelPro`, Required: `2026-08-01` |
| 3.7 | **Approve Requisition** → _Approvals → Pending Approvals_ | Click Approve on the PR |
| 3.8 | **Create Purchase Order (PO)** → _Procurement → Purchase Orders → Add_ | From PR: `Rebar for Foundation`, Items confirmed, Submit for approval |
| 3.9 | **Approve PO** → _Approvals → Pending Approvals_ | Click Approve on the PO |
| 3.10 | **Receive Goods (GRN)** → _Procurement → Goods Received → Add_ | PO: select the PO, Receive: `48 ton` (partial), Site: `Main Tower Site`, Vehicle: `ABC-1234` |
| 3.11 | **Check Stock** → _Procurement → Inventory → Stocks_ | Verify Rebar 16mm shows balance `48 ton` at `Main Yard` |
| 3.12 | **Create RFQ** → _Procurement → RFQ → Add_ | Title: `Concrete Supply Q3`, Project: `Greenfield Tower`, Items: `Ready-Mix Concrete Grade 30 × 200 m³`, Send to: `ConcreteMix` |
| 3.13 | **Record Quotation** → _Procurement → Quotations → Add_ | Vendor: `ConcreteMix`, Amount: `280/m³`, Valid until: `2026-08-01` |
| 3.14 | **Transfer Material** → _Procurement → Inventory → Material Transfers_ | From: `Main Yard`, To: `Greenfield Tower Site`, Item: `Rebar 16mm`, Qty: `20 ton` |
| 3.15 | **Issue Material** → _Procurement → Inventory → Issue Slips_ | Project: `Greenfield Tower`, Item: `Rebar 16mm`, Qty: `10 ton`, Issued to: `Site Supervisor` |
| 3.16 | **Record Wastage** → _Procurement → Inventory → Material Wastage_ | Project: `Greenfield Tower`, Item: `Rebar 16mm`, Qty: `0.5 ton`, Reason: `Cut-off scraps` |

---

## 4. Finance

| Step | Action | Sample Data |
|---|---|---|
| 4.1 | **Create Budget** → _Finance → Cost Control → Budgets_ | Project: `Greenfield Tower`, Name: `Foundation Budget`, Total Budget: `500000`, Planned Value: `500000` |
| 4.2 | **Create BOQ** → _Finance → Estimating → BOQ → Add_ | Project: `Greenfield Tower`, Title: `Foundation BOQ`, Version: `1` |
| 4.3 | **Add BOQ Items** → _BOQ detail → Add Items_ | Item 1: `Excavation` × 1000 m³ @ RM 25 = RM 25,000; Item 2: `Rebar` × 50 ton @ RM 3,200 = RM 160,000 |
| 4.4 | **Create Rate Analysis** → _Finance → Estimating → Rate Analysis_ | Item: `Excavation`, Materials: `0`, Labour: `15`, Equipment: `8`, Overhead: `2` → Total: `25/m³` |
| 4.5 | **Create Tender** → _Finance → Estimating → Tenders → Add_ | Project: `Greenfield Tower`, Title: `Foundation Work Tender`, BOQ: `Foundation BOQ`, Issue to: `SteelPro`, Due: `2026-08-15` |
| 4.6 | **Record Tender Bid** → _Tender detail → Add Bid_ | Vendor: `SteelPro`, Amount: `480000`, Status: `Submitted` |
| 4.7 | **Invoice Client** → _Finance → Billing → Invoices → Add_ | Project: `Greenfield Tower`, Client: `BuildCorp Ltd` (self), Items: `Excavation completed`, Amount: `25000`, Date: today |
| 4.8 | **Record Payment** → _Invoice detail → Add Payment_ | Amount: `25000`, Method: `Bank Transfer`, Date: today |
| 4.9 | **Create IPA** → _Finance → Billing → IPAs → Add_ | Project: `Greenfield Tower`, Period: `Jul-Sep 2026`, Amount: `100000`, Submit → Certify → Approve → Generate Invoice |
| 4.10 | **Create Bill (AP)** → _Finance → Billing → Bills Payable → Add_ | Project: `Greenfield Tower`, Vendor: `SteelPro`, Items: `Rebar 48 ton @ 3200`, Amount: `153600` |
| 4.11 | **Check Cost Alert** → _Finance → Cost Control → Cost Alerts_ | Verify no overrun yet (adjust budget to `100000` to trigger alert) |
| 4.12 | **View Aging Reports** → _Finance → Aging Reports → AR Aging / AP Aging_ | Verify invoices and bills appear in correct aging buckets |

---

## 5. Human Resources

### 5.1 People Management

| Step | Action | Sample Data |
|---|---|---|
| 5.1.1 | **Add Employee** → _HR → People → All Employees → Add Employee_ | Name: `Ahmad Bin Ali`, Email: `ahmad@buildcorp.my`, Phone: `012-1112222`, Department: `Site Operations`, Designation: `Site Supervisor`, Basic Salary: `4000`, Status: `Active` |
| 5.1.2 | **Add Second Employee** → _Add Employee_ | Name: `Siti Binti Tan`, Email: `siti@buildcorp.my`, Department: `Admin`, Designation: `HR Clerk`, Basic Salary: `3000`, Status: `Active` |
| 5.1.3 | **Add Third Employee** → _Add Employee_ | Name: `Ravi Kumar`, Email: `ravi@buildcorp.my`, Department: `Site Operations`, Designation: `Labourer`, Basic Salary: `2000`, Status: `Active` |
| 5.1.4 | **Mark Attendance (bulk)** → _HR → People → Mark Attendance_ | Date: today, Select all 3 employees, Set each status: `Present`, Clock In: `08:00`, Clock Out: `17:00` |
| 5.1.5 | **View Monthly Summary** → _HR → People → Monthly Summary_ | Select month, verify counts and total hours per employee |
| 5.1.6 | **Log Timesheet** → _HR → People → Timesheets → Add_ | Employee: `Ahmad`, Project: `Greenfield Tower`, Date: today, Start: `08:00`, End: `17:00`, Description: `Supervised excavation` |
| 5.1.7 | **Second Timesheet** → _Timesheets → Add_ | Employee: `Ravi`, Project: `Greenfield Tower`, Date: today, Start: `08:00`, End: `18:00` (10h = 2h overtime) |
| 5.1.8 | **Submit Leave Request** → _HR → People → Leave Requests → Add_ | Employee: `Siti`, Type: `Annual Leave`, From: next Monday, To: next Wednesday, Reason: `Personal`, Status: `Pending` |

### 5.2 Payroll

| Step | Action | Sample Data |
|---|---|---|
| 5.2.1 | **Generate Wage Slips** → _HR → Payroll → Wage Slips_ | Select month: current month, Click Generate — verify wage slips created for all employees |
| 5.2.2 | **View Wage Slip** → Click on `Ahmad`'s wage slip | Verify basic salary RM 4000, attendance deductions, overtime from timesheet, 10% allowance, 5% deductions, net pay |
| 5.2.3 | **Print Wage Slip** → _Wage Slip detail → Print_ | Verify print-optimized layout |

### 5.3 Equipment & Assets

| Step | Action | Sample Data |
|---|---|---|
| 5.3.1 | **Add Equipment (Owned)** → _HR → Equipment & Assets → Equipment → Add_ | Name: `Excavator CAT 320`, Code: `EQ-001`, Make: `Caterpillar`, Model: `320`, Serial: `CAT320-12345`, Acquisition: `Owned`, Purchase Cost: `450000`, Status: `Operational` |
| 5.3.2 | **Add Equipment (Hired)** → _Add Equipment_ | Name: `Concrete Pump`, Code: `EQ-002`, Make: `Schwing`, Model: `SP500`, Acquisition: `Hired`, Hire Rate: `5000`, Hire Period: `Monthly`, Hire Vendor: `ConcreteMix`, Hire Start: `2026-07-01`, Status: `Operational` |
| 5.3.3 | **Allocate Equipment to Project** → _Equipment → Edit EQ-001_ | Project: `Greenfield Tower`, Site: `Main Tower Site`, Allocated Date: today |
| 5.3.4 | **Log Fuel Consumption** → _HR → Equipment & Assets → Fuel Logs → Add_ | Equipment: `Excavator CAT 320`, Date: today, Type: `Diesel`, Qty: `100`, Unit: `Liters`, Unit Cost: `2.15`, Meter Hours: `1250`, Vendor: `PetroLangkawi` |
| 5.3.5 | **Add Second Fuel Log** → _Fuel Logs → Add_ | Equipment: `Excavator CAT 320`, Date: yesterday, Type: `Diesel`, Qty: `80`, Unit: `Liters`, Unit Cost: `2.10`, Meter Hours: `1200` |
| 5.3.6 | **Record Maintenance** → _Equipment → Maintenance → Add_ | Equipment: `Excavator CAT 320`, Type: `Preventive`, Date: today, Cost: `1500`, Vendor: `CAT Services`, Next Due: `+3 months`, Notes: `Oil change + filter` |
| 5.3.7 | **Issue PPE** → _HR → Equipment & Assets → PPE Issuance → Add_ | Employee: `Ravi Kumar`, Item: `Safety Helmet`, Category: `Head Protection`, Qty: `1`, Size: `M`, Issue Date: today, Condition on Issue: `New` |
| 5.3.8 | **Issue Second PPE** → _PPE Issuance → Add_ | Employee: `Ahmad`, Item: `Safety Boots`, Size: `10`, Issue Date: today, Condition: `New` |

### 5.4 Safety & Compliance

| Step | Action | Sample Data |
|---|---|---|
| 5.4.1 | **Report Incident** → _HR → Safety & Compliance → Incident Reports → Add_ | Type: `Near Miss`, Severity: `Minor`, Date: today, Location: `Main Tower Site`, Description: `Worker almost stepped on exposed rebar`, Root Cause: `No warning signage`, Corrective Action: `Install warning signs`, Reported By: `Ahmad` |
| 5.4.2 | **Update Incident** → _Incident Reports → Edit_ | Status: `Under Investigation`, Investigation Notes: `Reviewed CCTV footage — signage was missing` |
| 5.4.3 | **Close Incident** → _Incident Reports → Edit_ | Status: `Closed`, Closure Date: today |
| 5.4.4 | **Create HSE Checklist** → _HR → Safety & Compliance → HSE Checklists → Add_ | Type: `General`, Title: `Weekly Site Safety Walk`, Date: today, Location: `Main Tower Site`, Status: `Completed` |
| 5.4.5 | **Add Checklist Items** → _Edit HSE Checklist → Items_ | Item 1: `Workers wearing PPE` → `Pass`, Item 2: `Fire extinguisher present` → `Pass`, Item 3: `First aid kit stocked` → `Fail` (Finding: `Kit missing bandages`, Corrective: `Restock by tomorrow`) |
| 5.4.6 | **Record Toolbox Talk** → _HR → Safety & Compliance → Toolbox Talks → Add_ | Date: today, Topic: `Working at Height Safety`, Conducted By: `Ahmad`, Duration: `30`, Location: `Site Office`, Attendees: `Ravi Kumar, 3 other workers`, Discussion Points: `Proper harness use, ladder inspection`, Action Items: `Inspect all ladders by Friday` |

### 5.5 Training

| Step | Action | Sample Data |
|---|---|---|
| 5.5.1 | **Record Training** → _HR → Training → Training Records → Add_ | Employee: `Ravi Kumar`, Name: `Safety Induction`, Provider: `NIOSH`, Start: `2026-06-01`, End: `2026-06-03`, Status: `Completed`, Certificate No: `NIOSH-2026-001`, Expiry: `2027-06-01`, Cost: `500` |
| 5.5.2 | **Second Training** → _Training Records → Add_ | Employee: `Ahmad`, Name: `Supervisor Safety Course`, Provider: `CIDB`, Start: `2026-07-01`, Status: `Planned`, Cost: `1200` |
| 5.5.3 | **Add Certification** → _HR → Training → Certifications & Licences → Add_ | Employee: `Ahmad`, Name: `Site Supervisor License`, Category: `License`, Issuing Authority: `CIDB`, Certificate No: `CIDB-SS-1234`, Issue: `2025-01-01`, Expiry: `2027-12-31`, Status: `Active` |
| 5.5.4 | **Add Second Certification** → _Certifications → Add_ | Employee: `Ravi`, Name: `Forklift Operator Permit`, Category: `Permit`, Issuing Authority: `DOSH`, Certificate No: `DOSH-FL-5678`, Issue: `2026-01-01`, Expiry: `2027-01-01`, Status: `Active`, Renewal Reminder: `2026-12-01` |

---

## 6. Reports

| Step | Action | Notes |
|---|---|---|
| 6.1 | **Project Cost Summary** → _Reports → Cost & Budgeting → Project Cost Summary_ | View by project, check budget vs actual |
| 6.2 | **Budget vs Actual** → _Reports → Cost & Budgeting → Budget vs Actual_ | Select `Greenfield Tower` |
| 6.3 | **Invoice Status** → _Reports → Financial Status → Invoice Status_ | Verify the RM 25,000 invoice appears |
| 6.4 | **Cash Flow** → _Reports → Financial Status → Cash Flow_ | View monthly cash in/out |
| 6.5 | **Progress S-Curve** → _Reports → Progress & Procurement → Progress S-Curve_ | Interactive chart — verify task progress reflects |
| 6.6 | **Procurement Spend** → _Reports → Progress & Procurement → Procurement Spend_ | View by project or vendor |
| 6.7 | **Export** → Any report → Click PDF or Excel | Verify download |

---

## 7. Approvals

| Step | Action | Notes |
|---|---|---|
| 7.1 | **View Pending Approvals** → _Approvals → Pending Approvals_ | Should show any unapproved PR/PO/invoices |
| 7.2 | **Approve / Reject** → Click Approve or Reject | Test both outcomes |
| 7.3 | **View Approval History** → _Approvals → All Approvals_ | Verify timeline of each approval |
| 7.4 | **Configure Workflows** → _Approvals → Approval Workflows_ | Add workflow: Module: `Purchase Order`, Levels: `2`, Role-based |

---

## 8. Filters & Pagination

Quick-check each module has working filters:

| Module | Filter By |
|---|---|
| Projects | Status, Date Range |
| Tasks | Project, Phase, Priority, Status |
| Vendors | Status, Category |
| Purchase Orders | Status, Project, Vendor |
| Invoices | Status, Project, Date |
| Attendance | Employee, Month |
| Timesheets | Employee, Project, Date |
| Equipment | Status, Acquisition Type |
| Fuel Logs | Equipment, Fuel Type |
| Incident Reports | Type, Severity, Status |
| HSE Checklists | Type, Status |
| Toolbox Talks | Employee, Date Range |
| Training Records | Employee, Status |
| Certifications | Employee, Category, Status |
| PPE Issuance | Employee, Category, Returned |

---

## 9. Validation & Edge Cases

| Test | Expected Result |
|---|---|
| Try to delete an employee with attendance records | Error: "Cannot delete — has related records" |
| Submit leave without selecting employee | Validation error on form |
| Enter negative quantity on a fuel log | Validation error |
| Create PO without approved PR | Should warn or restrict |
| Access `/dashboard/hr/fuel-logs` while logged out | Redirect to login page |
| Access a non-existent page `/dashboard/xyz` | 404 error page |
| Submit expired session (wait 2h or clear cookie) | 419 session expired page |
| Access page without permission (login as HR, try Finance) | 403 forbidden page |

---

## 10. Quick Smoke Test URLs

```
/dashboard
/dashboard/core/projects
/dashboard/core/sites
/dashboard/core/tasks
/dashboard/procurement/vendors
/dashboard/procurement/purchase-orders
/dashboard/finance/budgets
/dashboard/finance/invoices
/dashboard/hr/employees
/dashboard/hr/attendance
/dashboard/hr/timesheets
/dashboard/hr/wage-slips
/dashboard/hr/equipment
/dashboard/hr/leaves
/dashboard/hr/training-records
/dashboard/hr/ppe-issuances
/dashboard/hr/incident-reports
/dashboard/hr/certifications
/dashboard/hr/hse-checklists
/dashboard/hr/fuel-logs
/dashboard/hr/toolbox-talks
/dashboard/approvals
/dashboard/reports
```
