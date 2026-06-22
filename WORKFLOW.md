# Construction ERP — Manual Testing Guide

> Walk through every module by entering realistic sample data.  
> Items marked `→` are navigation paths in the sidebar.

---

## 0. Prerequisites

| Step | Action                                           | Sample Data                                                                            |
| ---- | ------------------------------------------------ | -------------------------------------------------------------------------------------- |
| 0.1  | Login as super-admin                             | Email: `admin@admin.com` / Password: `password`                                        |
| 0.2  | Create Settings → _Settings_                     | Company Name: `Padma Construction Ltd`, Currency: `BDT`, Timezone: `Asia/Dhaka`        |
| 0.3  | Create Roles → _Settings → Roles_                | Role: `Project Manager` / `Site Supervisor` / `Accountant` / `HR Manager`              |
| 0.4  | Create Users (assign roles) → _Settings → Users_ | `pm@padma-bd.com` (Project Manager), `hr@padma-bd.com` (HR Manager)                    |
| 0.5  | Categories → _Settings → Categories_             | `Brick & Block Work`, `Steel & Rebar Work`, `Plumbing & Sanitary`, `Finishing & Paint` |

---

## 1. Core — Project Setup

| Step | Action                                                  | Sample Data                                                                                                                        |
| ---- | ------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| 1.1  | **Create Project** → _Core → Projects → Add Project_    | Name: `Rupayan City Uttara`, Code: `RCU-2026`, Location: `Uttara, Dhaka`, Start: `2026-07-01`, End: `2027-06-30`, Status: `Active` |
| 1.2  | **Add Sites** → _Core → Sites → Add Site_               | Name: `Tower-A Site`, Project: `Rupayan City Uttara`, Address: `Sector 7, Uttara, Dhaka-1230`                                      |
| 1.3  | **Upload Site Photos** → _Project detail → Site Photos_ | Upload any JPG (pre-construction site photo)                                                                                       |
| 1.4  | **Log Site Activity** → _Core → Sites → Site Logs_      | Date: today, Description: `Site clearing & boundary marking commenced`, Weather: `Sunny`                                           |

---

## 2. Core — Planning

| Step | Action                                                                | Sample Data                                                                                                                                                                                                      |
| ---- | --------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 2.1  | **Create Phases** → _Project detail → Phases → Add Phase_             | Name: `Foundation & Piling`, Project: `Rupayan City Uttara`, Order: `1`                                                                                                                                          |
| 2.2  | **Create Milestones** → _Project detail → Milestones → Add Milestone_ | Name: `Foundation Complete`, Phase: `Foundation & Piling`, Due: `2026-09-30`                                                                                                                                     |
| 2.3  | **Create Tasks** → _Core → Planning → Tasks → Add Task_               | Name: `Bore Pile Casting`, Project: `Rupayan City Uttara`, Phase: `Foundation & Piling`, Milestone: `Foundation Complete`, Assigned: `Site Supervisor`, Start: `2026-07-15`, End: `2026-08-15`, Priority: `High` |
| 2.4  | **Add second Task** → _Core → Planning → Tasks → Add Task_            | Name: `Pile Cap & Grade Beam`, Project: `Rupayan City Uttara`, Phase: `Foundation & Piling`, Start: `2026-08-16`, End: `2026-09-15`                                                                              |
| 2.5  | **Link Task Dependencies** → _Edit task → Dependencies_               | `Pile Cap & Grade Beam` depends on `Bore Pile Casting`                                                                                                                                                           |
| 2.6  | **Assign Resources** → _Project → Resources → Add_                    | Resource: `Pile Driving Rig`, Qty: `2`, Task: `Bore Pile Casting`                                                                                                                                                |
| 2.7  | **Update Task Progress** → _Tasks → Edit → Progress %_                | Set `Bore Pile Casting` to `50%` — verify project progress bar updates                                                                                                                                           |
| 2.8  | **Create Work Orders** → _Core → Execution → Work Orders_             | Title: `Pile Casting Work Order`, Project: `Rupayan City Uttara`, Task: `Bore Pile Casting`, Status: `Open`, Priority: `Urgent`                                                                                  |
| 2.9  | **Create Inspection Checklist** → _Core → Execution → Inspections_    | Title: `Pile Integrity Check`, Site: `Tower-A Site`                                                                                                                                                              |
| 2.10 | **Add Checklist Items** → _Edit Inspection → Add Items_               | Item 1: `Pile depth as per design` (Pass/Fail), Item 2: `Rebar cage placement correct` (Pass/Fail)                                                                                                               |

---

## 3. Procurement

| Step | Action                                                                                             | Sample Data                                                                                                                                                                                                                                                                |
| ---- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 3.1  | **Add Vendor** → _Procurement → Reference Data → Vendors_                                          | Name: `Bashundhara Steel Ltd`, Contact: `Monir Hossain`, Phone: `01712-345678`, Email: `monir@bsl-bd.com`, Category: `Material Supplier`, Status: `Approved`                                                                                                               |
| 3.2  | **Add Second Vendor** → _Vendors_                                                                  | Name: `Mirpur Concrete Ltd`, Contact: `Abdul Karim`, Status: `Approved`                                                                                                                                                                                                    |
| 3.3  | **Add Material** → _Procurement → Reference Data → Materials_                                      | Name: `60mm MS Rod (Grade 60)`, Unit: `ton`, Category: `Steel & Rebar Work`, Unit Price: `95000`                                                                                                                                                                           |
| 3.4  | **Add Second Material** → _Materials_                                                              | Name: `Ready-Mix Concrete (5000 PSI)`, Unit: `m³`, Category: `Brick & Block Work`, Unit Price: `7200`                                                                                                                                                                      |
| 3.5  | **Create Material Submittal (Draft)** → _Procurement → Reference Data → Material Submittals → Add_ | Project: `Rupayan City Uttara`, Title: `MS Rod Grade 60 for Foundation`, Material Name: `60mm MS Rod (Grade 60)`, Manufacturer: `Bashundhara Steel`, Brand: `BSRM`, Model Ref: `G60-60mm`, Specs: `ASTM A615 Grade 60, yield 415 MPa`, Qty/Unit: `50 ton`, Status: `Draft` |
| 3.6  | **Submit Submittal** → _Material Submittals → detail → Submit_                                     | Click Submit — status changes to `Submitted`, date auto-set                                                                                                                                                                                                                |
| 3.7  | **Review & Approve Submittal** → _Material Submittals → detail → Review_                           | Status: `Approved`, Reviewer comments: `Specs meet project requirements. Approved.`                                                                                                                                                                                        |
| 3.8  | **Create Second Submittal (for rejected workflow)** → _Material Submittals → Add_                  | Project: `Rupayan City Uttara`, Title: `Concrete Mix Design 5000 PSI`, Material Name: `Ready-Mix Concrete (5000 PSI)`, Manufacturer: `Mirpur Concrete`, Status: `Draft`                                                                                                    |
| 3.9  | **Submit & Reject** → _Submit, then Review with status Rejected_                                   | Status: `Rejected`, Comments: `Test report missing. Resubmit with 7-day compression test results.`, Deadine: `+14 days`                                                                                                                                                    |
| 3.10 | **Resubmit** → _Material Submittals → detail → Resubmit_                                           | Update description with test results, click Resubmit — status resets to `Resubmitted`, ready for re-review                                                                                                                                                                 |
| 3.11 | **Add Warehouse** → _Procurement → Reference Data → Warehouses_                                    | Name: `Mirpur Main Yard`, Location: `Mirpur, Dhaka`                                                                                                                                                                                                                        |
| 3.12 | **Create Requisition (PR)** → _Procurement → Requisitions → Add_                                   | Title: `MS Rod for Foundation`, Project: `Rupayan City Uttara`, Items: `60mm MS Rod × 50 ton`, Vendor: `Bashundhara Steel`, Required: `2026-08-01`                                                                                                                         |
| 3.13 | **Approve Requisition** → _Approvals → Pending Approvals_                                          | Click Approve on the PR                                                                                                                                                                                                                                                    |
| 3.14 | **Create Purchase Order (PO)** → _Procurement → Purchase Orders → Add_                             | From PR: `MS Rod for Foundation`, Items confirmed, Submit for approval                                                                                                                                                                                                     |
| 3.15 | **Approve PO** → _Approvals → Pending Approvals_                                                   | Click Approve on the PO                                                                                                                                                                                                                                                    |
| 3.16 | **Receive Goods (GRN)** → _Procurement → Goods Received → Add_                                     | PO: select the PO, Receive: `48 ton` (partial), Site: `Tower-A Site`, Vehicle: `ঢাকা মেট্রো-১২-৩৪৫৬`                                                                                                                                                                       |
| 3.17 | **Check Stock** → _Procurement → Inventory → Stocks_                                               | Verify MS Rod shows balance `48 ton` at `Mirpur Main Yard`                                                                                                                                                                                                                 |
| 3.18 | **Create RFQ** → _Procurement → RFQ → Add_                                                         | Title: `Concrete Supply Q3`, Project: `Rupayan City Uttara`, Items: `Ready-Mix Concrete × 200 m³`, Send to: `Mirpur Concrete`                                                                                                                                              |
| 3.19 | **Record Quotation** → _Procurement → Quotations → Add_                                            | Vendor: `Mirpur Concrete`, Amount: `7200/m³`, Valid until: `2026-08-01`                                                                                                                                                                                                    |
| 3.20 | **Transfer Material** → _Procurement → Inventory → Material Transfers_                             | From: `Mirpur Main Yard`, To: `Tower-A Site`, Item: `60mm MS Rod`, Qty: `20 ton`                                                                                                                                                                                           |
| 3.21 | **Issue Material** → _Procurement → Inventory → Issue Slips_                                       | Project: `Rupayan City Uttara`, Item: `60mm MS Rod`, Qty: `10 ton`, Issued to: `Site Supervisor`                                                                                                                                                                           |
| 3.22 | **Record Wastage** → _Procurement → Inventory → Material Wastage_                                  | Project: `Rupayan City Uttara`, Item: `60mm MS Rod`, Qty: `0.5 ton`, Reason: `Cut-off & bending scraps`                                                                                                                                                                    |
| 3.23 | **Add Subcontractor** → _Procurement → Subcontractors → All Subcontractors → Add_                  | Name: `Momin Construction Ltd`, Contact: `Md. Momin Uddin`, Phone: `01713-334455`, Email: `momin@momcon-bd.com`, Specialization: `Brick & Block Work`, Status: `Approved`                                                                                                  |
| 3.24 | **Add Second Subcontractor** → _Subcontractors → Add_                                              | Name: `Dhaka Finishing Group`, Contact: `Shahidul Islam`, Phone: `01714-556677`, Specialization: `Finishing & Paint`, Status: `Approved`                                                                                                                                   |
| 3.25 | **Create Subcontract Agreement** → _Procurement → Subcontractors → Subcontract Agreements → Add_   | Project: `Rupayan City Uttara`, Subcontractor: `Momin Construction Ltd`, Title: `Brick Work — Tower-A`, Agreement #: `SCA-2026-001`, Value: `12000000`, Period: `01 Aug 2026 — 30 Nov 2026`, Payment Terms: `90% milestone + 10% retention`, Status: `Active`              |
| 3.26 | **Create Progress Payment** → _Procurement → Subcontractors → Progress Payments → Add_             | Agreement: `Brick Work — Tower-A`, Certificate #: `CC-2026-001`, Period: `Aug 2026`, Claimed Amount: `3000000`, Certified Amount: `2800000`, Status: `Certified`                                                                                                           |
| 3.27 | **Mark Payment Complete** → _Progress Payments → Edit_                                             | Status: `Paid`, Paid Amount: `2800000`, Paid Date: today                                                                                                                                                                                                                   |

---

## 4. Finance

| Step | Action                                                                   | Sample Data                                                                                                                              |
| ---- | ------------------------------------------------------------------------ | ---------------------------------------------------------------------------------------------------------------------------------------- |
| 4.1  | **Create Budget** → _Finance → Cost Control → Budgets_                   | Project: `Rupayan City Uttara`, Name: `Foundation Budget`, Total Budget: `50000000`, Planned Value: `50000000`                           |
| 4.2  | **Create BOQ** → _Finance → Estimating → BOQ → Add_                      | Project: `Rupayan City Uttara`, Title: `Foundation BOQ`, Version: `1`                                                                    |
| 4.3  | **Add BOQ Items** → _BOQ detail → Add Items_                             | Item 1: `Earth Excavation` × 1000 m³ @ ৳ 250 = ৳ 250,000; Item 2: `MS Rod Work` × 50 ton @ ৳ 95,000 = ৳ 4,750,000                        |
| 4.4  | **Create Rate Analysis** → _Finance → Estimating → Rate Analysis_        | Item: `Earth Excavation`, Materials: `0`, Labour: `150`, Equipment: `80`, Overhead: `20` → Total: `250/m³`                               |
| 4.5  | **Create Tender** → _Finance → Estimating → Tenders → Add_               | Project: `Rupayan City Uttara`, Title: `Foundation Work Tender`, BOQ: `Foundation BOQ`, Issue to: `Bashundhara Steel`, Due: `2026-08-15` |
| 4.6  | **Record Tender Bid** → _Tender detail → Add Bid_                        | Vendor: `Bashundhara Steel`, Amount: `48000000`, Status: `Submitted`                                                                     |
| 4.7  | **Invoice Client** → _Finance → Billing → Invoices → Add_                | Project: `Rupayan City Uttara`, Client: `Padma Construction Ltd` (self), Items: `Excavation completed`, Amount: `250000`, Date: today    |
| 4.8  | **Record Payment** → _Invoice detail → Add Payment_                      | Amount: `250000`, Method: `Bank Transfer (BEFTN)`, Date: today                                                                           |
| 4.9  | **Create IPA** → _Finance → Billing → IPAs → Add_                        | Project: `Rupayan City Uttara`, Period: `Jul-Sep 2026`, Amount: `10000000`, Submit → Certify → Approve → Generate Invoice                |
| 4.10 | **Create Bill (AP)** → _Finance → Billing → Bills Payable → Add_         | Project: `Rupayan City Uttara`, Vendor: `Bashundhara Steel`, Items: `MS Rod 48 ton @ 95,000`, Amount: `4560000`                          |
| 4.11 | **Check Cost Alert** → _Finance → Cost Control → Cost Alerts_            | Verify no overrun yet (adjust budget to `10000000` to trigger alert)                                                                     |
| 4.12 | **View Aging Reports** → _Finance → Aging Reports → AR Aging / AP Aging_ | Verify invoices and bills appear in correct aging buckets                                                                                |

---

## 5. Human Resources

### 5.1 People Management

| Step  | Action                                                          | Sample Data                                                                                                                                                                           |
| ----- | --------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 5.1.1 | **Add Employee** → _HR → People → All Employees → Add Employee_ | Name: `Md. Rafiqul Islam`, Email: `rafiq@padma-bd.com`, Phone: `01711-223344`, Department: `Site Operations`, Designation: `Site Supervisor`, Basic Salary: `45000`, Status: `Active` |
| 5.1.2 | **Add Second Employee** → _Add Employee_                        | Name: `Shahnaj Parvin`, Email: `shahnaj@padma-bd.com`, Department: `Admin`, Designation: `HR Clerk`, Basic Salary: `30000`, Status: `Active`                                          |
| 5.1.3 | **Add Third Employee** → _Add Employee_                         | Name: `Md. Shajahan Miah`, Email: `shajahan@padma-bd.com`, Department: `Site Operations`, Designation: `Skilled Labourer`, Basic Salary: `18000`, Status: `Active`                    |
| 5.1.4 | **Mark Attendance (bulk)** → _HR → People → Mark Attendance_    | Date: today, Select all 3 employees, Set each status: `Present`, Clock In: `08:00`, Clock Out: `17:00`                                                                                |
| 5.1.5 | **View Monthly Summary** → _HR → People → Monthly Summary_      | Select month, verify counts and total hours per employee                                                                                                                              |
| 5.1.6 | **Log Timesheet** → _HR → People → Timesheets → Add_            | Employee: `Rafiqul`, Project: `Rupayan City Uttara`, Date: today, Start: `08:00`, End: `17:00`, Description: `Supervised pile casting`                                                |
| 5.1.7 | **Second Timesheet** → _Timesheets → Add_                       | Employee: `Shajahan`, Project: `Rupayan City Uttara`, Date: today, Start: `08:00`, End: `18:00` (10h = 2h overtime)                                                                   |
| 5.1.8 | **Submit Leave Request** → _HR → People → Leave Requests → Add_ | Employee: `Shahnaj`, Type: `Annual Leave`, From: next Monday, To: next Wednesday, Reason: `Personal`                                                                                  |

### 5.2 Payroll

| Step  | Action                                                | Sample Data                                                                                                                                         |
| ----- | ----------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------- |
| 5.2.1 | **Generate Wage Slips** → _HR → Payroll → Wage Slips_ | Select month: current month, Click Generate — verify wage slips created for all employees                                                           |
| 5.2.2 | **View Wage Slip** → Click on `Rafiqul`'s wage slip   | Verify basic salary ৳ 45000, attendance deductions, overtime from timesheet, 10% allowance, 5% deductions (house rent & medical allowance), net pay |
| 5.2.3 | **Print Wage Slip** → _Wage Slip detail → Print_      | Verify print-optimized layout                                                                                                                       |

### 5.3 Equipment & Assets

| Step  | Action                                                                  | Sample Data                                                                                                                                                                                                                             |
| ----- | ----------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 5.3.1 | **Add Equipment (Owned)** → _HR → Equipment & Assets → Equipment → Add_ | Name: `Pile Driving Rig (Hitachi KH90)`, Code: `EQ-001`, Make: `Hitachi`, Model: `KH90`, Serial: `HT-KH90-12345`, Acquisition: `Owned`, Purchase Cost: `8500000`, Status: `Operational`                                                 |
| 5.3.2 | **Add Equipment (Hired)** → _Add Equipment_                             | Name: `Tower Crane (TC7020)`, Code: `EQ-002`, Make: `Zoomlion`, Model: `TC7020`, Acquisition: `Hired`, Hire Rate: `350000`, Hire Period: `Monthly`, Hire Vendor: `Mirpur Concrete Ltd`, Hire Start: `2026-07-01`, Status: `Operational` |
| 5.3.3 | **Allocate Equipment to Project** → _Equipment → Edit EQ-001_           | Project: `Rupayan City Uttara`, Site: `Tower-A Site`, Allocated Date: today                                                                                                                                                             |
| 5.3.4 | **Log Fuel Consumption** → _HR → Equipment & Assets → Fuel Logs → Add_  | Equipment: `Pile Driving Rig (Hitachi KH90)`, Date: today, Type: `Diesel`, Qty: `120`, Unit: `Liters`, Unit Cost: `115`, Meter Hours: `1250`, Vendor: `Padma Oil Company Ltd`                                                           |
| 5.3.5 | **Add Second Fuel Log** → _Fuel Logs → Add_                             | Equipment: `Pile Driving Rig (Hitachi KH90)`, Date: yesterday, Type: `Diesel`, Qty: `95`, Unit: `Liters`, Unit Cost: `112`, Meter Hours: `1200`                                                                                         |
| 5.3.6 | **Record Maintenance** → _Equipment → Maintenance → Add_                | Equipment: `Pile Driving Rig (Hitachi KH90)`, Type: `Preventive`, Date: today, Cost: `45000`, Vendor: `Hitachi Bangladesh Services`, Next Due: `+3 months`, Notes: `Hydraulic oil change + filter replacement`                          |
| 5.3.7 | **Issue PPE** → _HR → Equipment & Assets → PPE Issuance → Add_          | Employee: `Shajahan Miah`, Item: `Safety Helmet`, Category: `Head Protection`, Qty: `1`, Size: `M`, Issue Date: today, Condition on Issue: `New`                                                                                        |
| 5.3.8 | **Issue Second PPE** → _PPE Issuance → Add_                             | Employee: `Rafiqul Islam`, Item: `Safety Boots`, Size: `10`, Issue Date: today, Condition: `New`                                                                                                                                        |

### 5.4 Safety & Compliance

| Step  | Action                                                                       | Sample Data                                                                                                                                                                                                                                                                                                  |
| ----- | ---------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| 5.4.1 | **Report Incident** → _HR → Safety & Compliance → Incident Reports → Add_    | Type: `Near Miss`, Severity: `Minor`, Date: today, Location: `Tower-A Site`, Description: `Worker almost stepped on exposed rebar at floor slab`, Root Cause: `No warning signage`, Corrective Action: `Install warning signs & barricade`, Reported By: `Rafiqul`                                           |
| 5.4.2 | **Update Incident** → _Incident Reports → Edit_                              | Status: `Under Investigation`, Investigation Notes: `Reviewed site photos — signage was missing`                                                                                                                                                                                                             |
| 5.4.3 | **Close Incident** → _Incident Reports → Edit_                               | Status: `Closed`, Closure Date: today                                                                                                                                                                                                                                                                        |
| 5.4.4 | **Create HSE Checklist** → _HR → Safety & Compliance → HSE Checklists → Add_ | Type: `General`, Title: `Weekly Site Safety Walk (Tower-A)`, Date: today, Location: `Tower-A Site`, Status: `Completed`                                                                                                                                                                                      |
| 5.4.5 | **Add Checklist Items** → _Edit HSE Checklist → Items_                       | Item 1: `Workers wearing PPE` → `Pass`, Item 2: `Fire extinguisher present & charged` → `Pass`, Item 3: `First aid kit stocked` → `Fail` (Finding: `Kit missing bandages & antiseptic`, Corrective: `Restock by tomorrow`)                                                                                   |
| 5.4.6 | **Record Toolbox Talk** → _HR → Safety & Compliance → Toolbox Talks → Add_   | Date: today, Topic: `Working at Height Safety (বাংলায়)`, Conducted By: `Rafiqul Islam`, Duration: `30`, Location: `Site Office`, Attendees: `Shajahan Miah, 4 other workers`, Discussion Points: `Proper harness use, scaffold inspection, ladder safety`, Action Items: `Inspect all scaffolding by Friday` |

### 5.5 Training

| Step  | Action                                                                    | Sample Data                                                                                                                                                                                                                                                            |
| ----- | ------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 5.5.1 | **Record Training** → _HR → Training → Training Records → Add_            | Employee: `Shajahan Miah`, Name: `Construction Safety Induction`, Provider: `OSHC Bangladesh`, Start: `2026-06-01`, End: `2026-06-03`, Status: `Completed`, Certificate No: `OSHC-2026-001`, Expiry: `2027-06-01`, Cost: `5000`                                        |
| 5.5.2 | **Second Training** → _Training Records → Add_                            | Employee: `Rafiqul Islam`, Name: `Supervisor Safety Course`, Provider: `BUET-CE`, Start: `2026-07-01`, Status: `Planned`, Cost: `15000`                                                                                                                                |
| 5.5.3 | **Add Certification** → _HR → Training → Certifications & Licences → Add_ | Employee: `Rafiqul Islam`, Name: `Construction Supervisor License`, Category: `License`, Issuing Authority: `REHAB Bangladesh`, Certificate No: `REHAB-CSL-1234`, Issue: `2025-01-01`, Expiry: `2027-12-31`, Status: `Active`                                          |
| 5.5.4 | **Add Second Certification** → _Certifications → Add_                     | Employee: `Shajahan`, Name: `Forklift Operator Permit`, Category: `Permit`, Issuing Authority: `DIFE (Dept. of Inspection for Factories)`, Certificate No: `DIFE-FL-5678`, Issue: `2026-01-01`, Expiry: `2027-01-01`, Status: `Active`, Renewal Reminder: `2026-12-01` |

---

## 6. Reports

| Step | Action                                                                         | Notes                                             |
| ---- | ------------------------------------------------------------------------------ | ------------------------------------------------- |
| 6.1  | **Project Cost Summary** → _Reports → Cost & Budgeting → Project Cost Summary_ | View by project, check budget vs actual           |
| 6.2  | **Budget vs Actual** → _Reports → Cost & Budgeting → Budget vs Actual_         | Select `Rupayan City Uttara`                      |
| 6.3  | **Invoice Status** → _Reports → Financial Status → Invoice Status_             | Verify the ৳ 250,000 invoice appears              |
| 6.4  | **Cash Flow** → _Reports → Financial Status → Cash Flow_                       | View monthly cash in/out                          |
| 6.5  | **Progress S-Curve** → _Reports → Progress & Procurement → Progress S-Curve_   | Interactive chart — verify task progress reflects |
| 6.6  | **Procurement Spend** → _Reports → Progress & Procurement → Procurement Spend_ | View by project or vendor                         |
| 6.7  | **Export** → Any report → Click PDF or Excel                                   | Verify download                                   |

---

## 7. Approvals

| Step | Action                                                       | Notes                                                           |
| ---- | ------------------------------------------------------------ | --------------------------------------------------------------- |
| 7.1  | **View Pending Approvals** → _Approvals → Pending Approvals_ | Should show any unapproved PR/PO/invoices                       |
| 7.2  | **Approve / Reject** → Click Approve or Reject               | Test both outcomes                                              |
| 7.3  | **View Approval History** → _Approvals → All Approvals_      | Verify timeline of each approval                                |
| 7.4  | **Configure Workflows** → _Approvals → Approval Workflows_   | Add workflow: Module: `Purchase Order`, Levels: `2`, Role-based |

---

## 8. Filters & Pagination

Quick-check each module has working filters:

| Module           | Filter By                        |
| ---------------- | -------------------------------- |
| Projects         | Status, Date Range               |
| Tasks            | Project, Phase, Priority, Status |
| Vendors          | Status, Category                 |
| Purchase Orders  | Status, Project, Vendor          |
| Invoices         | Status, Project, Date            |
| Attendance       | Employee, Month                  |
| Timesheets       | Employee, Project, Date          |
| Equipment        | Status, Acquisition Type         |
| Fuel Logs        | Equipment, Fuel Type             |
| Incident Reports | Type, Severity, Status           |
| HSE Checklists   | Type, Status                     |
| Toolbox Talks    | Employee, Date Range             |
| Training Records | Employee, Status                 |
| Certifications   | Employee, Category, Status       |
| PPE Issuance     | Employee, Category, Returned     |

---

## 9. Validation & Edge Cases

| Test                                                      | Expected Result                              |
| --------------------------------------------------------- | -------------------------------------------- |
| Try to delete an employee with attendance records         | Error: "Cannot delete — has related records" |
| Submit leave without selecting employee                   | Validation error on form                     |
| Enter negative quantity on a fuel log                     | Validation error                             |
| Create PO without approved PR                             | Should warn or restrict                      |
| Access `/dashboard/hr/fuel-logs` while logged out         | Redirect to login page                       |
| Access a non-existent page `/dashboard/xyz`               | 404 error page                               |
| Submit expired session (wait 2h or clear cookie)          | 419 session expired page                     |
| Access page without permission (login as HR, try Finance) | 403 forbidden page                           |

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
