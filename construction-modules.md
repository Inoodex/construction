# Construction Management System — Laravel Modules

> A reference guide for building a Construction Management ERP in Laravel.

## 📁 Module Groups

---

### 1. Core Project

#### 1.1 Project Management _(Must Have)_

- Projects, phases, milestones, timelines (প্রজেক্ট, পর্যায়, মাইলস্টোন, টাইমলাইন)
- Gantt chart / schedule view (গ্যান্ট চার্ট / শিডিউল ভিউ)
- Progress tracking (%) (প্রগতি ট্র্যাকিং (%))
- Resource planning per project (প্রতি প্রজেক্টের জন্য রিসোর্স পরিকল্পনা)
- Multi-site / multi-project support (মাল্টি-সাইট / মাল্টি-প্রজেক্ট সাপোর্ট)
- Project status workflows (Planning → Active → On Hold → Completed) (প্রজেক্ট স্ট্যাটাস ওয়ার্কফ্লো (পরিকল্পনা → সক্রিয় → স্থগিত → সম্পন্ন))

#### 1.2 Task & Work Orders _(Must Have)_

- Task creation and assignment (টাস্ক তৈরি এবং অ্যাসাইনমেন্ট)
- Work order generation (ওয়ার্ক অর্ডার জেনারেশন)
- Priority levels (Low / Medium / High / Critical) (অগ্রাধিকার স্তর (নিম্ন / মাঝারি / উচ্চ / জরুরি))
- Status workflows (Open → In Progress → Review → Closed) (স্ট্যাটাস ওয়ার্কফ্লো (খোলা → চলমান → রিভিউ → বন্ধ))
- Task dependencies and blocking relationships (টাস্ক ডিপেন্ডেন্সি এবং ব্লকিং রিলেশনশিপ)

#### 1.3 Site Management _(Must Have)_

- Site/location registry (সাইট/লোকেশন রেজিস্ট্রি)
- Daily site logs and field reports (দৈনিক সাইট লগ এবং ফিল্ড রিপোর্ট)
- Inspection checklists (ইন্সপেকশন চেকলিস্ট)
- Material delivery tracking at site level (সাইট লেভেলে ম্যাটেরিয়াল ডেলিভারি ট্র্যাকিং)
- Weather log integration (আবহাওয়া লগ ইন্টিগ্রেশন)
- Site photo uploads (সাইট ফটো আপলোড)

---

### 2. Finance & Accounting

#### 2.1 Budgeting & Cost Control _(Must Have)_

- Project budget creation (প্রজেক্ট বাজেট তৈরি)
- Cost code / WBS (Work Breakdown Structure) (কস্ট কোড / WBS (ওয়ার্ক ব্রেকডাউন স্ট্রাকচার))
- Budget vs actual tracking (বাজেট বনাম প্রকৃত ট্র্যাকিং)
- Cost overrun alerts (কস্ট ওভাররান অ্যালার্ট)
- Forecast to complete (ETC / EAC) (সম্পন্ন করার পূর্বাভাস (ETC / EAC))

#### 2.2 Estimating & BOQ _(Must Have)_

- Bill of Quantities (BOQ) builder (বিল অফ কোয়ান্টিটিজ (BOQ) বিল্ডার)
- Cost estimation per line item (প্রতি লাইন আইটেমের জন্য কস্ট এস্টিমেশন)
- Rate analysis and unit rate library (রেট অ্যানালাইসিস এবং ইউনিট রেট লাইব্রেরি)
- Material takeoff sheets (ম্যাটেরিয়াল টেক-অফ শিট)
- BOQ import via Excel (এক্সেল এর মাধ্যমে BOQ ইমপোর্ট)

#### 2.3 Tender Management _(Must Have)_

- Pre-qualification of bidders (বিডারদের প্রি-কোয়ালিফিকেশন)
- Tender package creation and distribution (টেন্ডার প্যাকেজ তৈরি এবং বিতরণ)
- Bid submission tracking (বিড সাবমিশন ট্র্যাকিং)
- Bid evaluation and comparison matrix (বিড মূল্যায়ন এবং তুলনা ম্যাট্রিক্স)
- Award letters and tender closeout (অ্যাওয়ার্ড লেটার এবং টেন্ডার ক্লোজআউট)

#### 2.4 Invoicing & Accounts _(Must Have)_

- Client invoice generation (progress / milestone billing) (ক্লায়েন্ট ইনভয়েস জেনারেশন (প্রগ্রেস / মাইলস্টোন বিলিং))
- Interim payment applications (IPA) (ইন্টারিম পেমেন্ট অ্যাপ্লিকেশন (IPA))
- Accounts payable / receivable (দেনা হিসাব / পাওনা হিসাব)
- Retention tracking and release (রিটেনশন ট্র্যাকিং এবং রিলিজ)
- Bank guarantee tracking (ব্যাংক গ্যারান্টি ট্র্যাকিং)
- Journal entries and general ledger (জার্নাল এন্ট্রি এবং জেনারেল লেজার)
- Cash flow statements (ক্যাশ ফ্লো স্টেটমেন্ট)
- Tax / VAT handling (ট্যাক্স / ভ্যাট হ্যান্ডলিং)

---

### 3. Procurement & Materials

#### 3.1 Vendor / Supplier Management _(Must Have)_

- Vendor profiles, contact details, trade categories (ভেন্ডর প্রোফাইল, কন্টাক্ট ডিটেইলস, ট্রেড ক্যাটাগরি)
- Pre-qualification and approval status (প্রি-কোয়ালিফিকেশন এবং অ্যাপ্রুভাল স্ট্যাটাস)
- Vendor performance ratings (ভেন্ডর পারফরম্যান্স রেটিং)
- Payment terms and credit limits (পেমেন্ট শর্তাবলী এবং ক্রেডিট লিমিট)
- Blacklist / suspension tracking (ব্ল্যাকলিস্ট / সাসপেনশন ট্র্যাকিং)

#### 3.2 Procurement _(Must Have)_

- Purchase requisitions (PR) (পারচেজ রিকুইজিশন (PR))
- Purchase orders (PO) (পারচেজ অর্ডার (PO))
- Material approval / submittal process (ম্যাটেরিয়াল অ্যাপ্রুভাল / সাবমিটাল প্রসেস)
- Vendor comparison and quotation analysis (ভেন্ডর তুলনা এবং কোটেশন অ্যানালাইসিস)
- Goods received notes (GRN) (গুডস রিসিভড নোটস (GRN))
- Multi-level approval workflows (মাল্টি-লেভেল অ্যাপ্রুভাল ওয়ার্কফ্লো)

#### 3.3 Inventory & Warehouse _(Must Have)_

- Material stock management (multiple warehouses/sites) (ম্যাটেরিয়াল স্টক ম্যানেজমেন্ট (একাধিক ওয়্যারহাউস/সাইট))
- Site-to-site material transfers (সাইট-টু-সাইট ম্যাটেরিয়াল ট্রান্সফার)
- Material issue slips (ম্যাটেরিয়াল ইস্যু স্লিপ)
- Wastage and loss tracking (অপচয় এবং ক্ষতি ট্র্যাকিং)
- Reorder level alerts (পুনরায় অর্ডারের লেভেল অ্যালার্ট)
- Material reconciliation reports (ম্যাটেরিয়াল রিকনসিলিয়েশন রিপোর্ট)

#### 3.4 Subcontractor Management _(Must Have)_

- Subcontractor pre-qualification (সাবকন্ট্রাক্টর প্রি-কোয়ালিফিকেশন)
- Subcontract agreements (সাবকন্ট্রাক্ট চুক্তি)
- Scope of work definitions (কাজের পরিসর নির্ধারণ)
- Progress payment certificates (প্রগ্রেস পেমেন্ট সার্টিফিকেট)
- Retention tracking (রিটেনশন ট্র্যাকিং)
- Subcontractor performance scoring (সাবকন্ট্রাক্টর পারফরম্যান্স স্কোরিং)

---

### 4. HR & Equipment

#### 4.1 HR & Payroll _(Must Have)_

- Worker / employee profiles (কর্মী / কর্মচারী প্রোফাইল)
- Attendance and timesheet tracking (উপস্থিতি এবং টাইমশিট ট্র্যাকিং)
- Labour cost allocation per project (প্রতি প্রজেক্টে লেবার কস্ট অ্যালোকেশন)
- Wage slip generation (ওয়েজ স্লিপ জেনারেশন)
- Leave and overtime management (ছুটি এবং ওভারটাইম ম্যানেজমেন্ট)
- Training records and certifications (ট্রেনিং রেকর্ড এবং সার্টিফিকেশন)
- PPE issuance tracking (পিপিই ইস্যু ট্র্যাকিং)

#### 4.2 Equipment & Assets _(Must Have)_

- Equipment registry and specifications (ইকুইপমেন্ট রেজিস্ট্রি এবং স্পেসিফিকেশন)
- Owned vs hired (rented) plant tracking (নিজস্ব বনাম ভাড়া করা প্ল্যান্ট ট্র্যাকিং)
- Hire rates and hire period management (ভাড়া রেট এবং ভাড়া পিরিয়ড ম্যানেজমেন্ট)
- Allocation to projects/sites (প্রজেক্ট/সাইটে অ্যালোকেশন)
- Preventive maintenance schedules (প্রিভেন্টিভ মেইনটেইন্যান্স শিডিউল)
- Fuel consumption logs (ফুয়েল কনজাম্পশন লগ)
- Asset depreciation tracking (অ্যাসেট ডেপ্রিসিয়েশন ট্র্যাকিং)

#### 4.3 Safety & Compliance _(Must Have)_

- HSE (Health, Safety & Environment) checklists (এইচএসই (স্বাস্থ্য, নিরাপত্তা এবং পরিবেশ) চেকলিস্ট)
- Incident and accident reports (ঘটনা এবং দুর্ঘটনা রিপোর্ট)
- Permits to work (PTW) (কাজের পারমিট (PTW))
- Certification and licence tracking (সার্টিফিকেশন এবং লাইসেন্স ট্র্যাকিং)
- Toolbox talk records (টুলবক্স টক রেকর্ড)
- Safety audit records (সেফটি অডিট রেকর্ড)

---

### 5. Quality & Risk

#### 5.1 Quality Control / QA _(Must Have)_

- Inspection and test plans (ITP) (ইন্সপেকশন এবং টেস্ট প্ল্যান (ITP))
- Non-conformance reports (NCR) (নন-কনফরমেন্স রিপোর্ট (NCR))
- Defect / punch list / snagging list (ডিফেক্ট / পাঞ্চ লিস্ট / স্ন্যাগিং লিস্ট)
- Material test certificates (ম্যাটেরিয়াল টেস্ট সার্টিফিকেট)
- Concrete mix design approvals (কংক্রিট মিক্স ডিজাইন অ্যাপ্রুভাল)
- Quality audit records (কোয়ালিটি অডিট রেকর্ড)
- Corrective action tracking (CAR) (কারেক্টিভ অ্যাকশন ট্র্যাকিং (CAR))

#### 5.2 Risk Management _(Important)_

- Risk register per project (প্রতি প্রজেক্টের জন্য রিস্ক রেজিস্টার)
- Risk assessment matrix — probability × impact (রিস্ক অ্যাসেসমেন্ট ম্যাট্রিক্স — সম্ভাবনা × প্রভাব)
- Risk owner assignment (রিস্ক ওনার অ্যাসাইনমেন্ট)
- Mitigation plans (মিটিগেশন পরিকল্পনা)
- Contingency plans (কন্টিনজেন্সি পরিকল্পনা)
- Risk status tracking (Open → In Progress → Mitigated → Closed) (রিস্ক স্ট্যাটাস ট্র্যাকিং (খোলা → চলমান → প্রশমিত → বন্ধ))
- Risk review dates (রিস্ক রিভিউ তারিখ)

---

### 6. Client & Documentation

#### 6.1 CRM & Clients _(Important)_

- Client profiles, contact details, documents (ক্লায়েন্ট প্রোফাইল, যোগাযোগের তথ্য, ডকুমেন্ট)
- Lead / opportunity tracking with estimated value (আনুমানিক মূল্যসহ লিড / অপর্চুনিটি ট্র্যাকিং)
- Proposals and quotations (প্রপোজাল এবং কোটেশন)
- Communication history log (যোগাযোগের ইতিহাস লগ)
- Lead status workflow (New → Contacted → Proposal Sent → Negotiation → Won / Lost) (লিড স্ট্যাটাস ওয়ার্কফ্লো (নতুন → যোগাযোগ করা হয়েছে → প্রস্তাব পাঠানো হয়েছে → আলোচনা → জেতা / হারানো))
- Client portal — view project progress and invoices (optional) (ক্লায়েন্ট পোর্টাল — প্রজেক্টের অগ্রগতি এবং ইনভয়েস দেখার সুবিধা (ঐচ্ছিক))

#### 6.2 Document Management _(Must Have)_

- Drawing register and revision control (ড্রয়িং রেজিস্টার এবং রিভিশন কন্ট্রোল)
- Shop drawings and as-built drawings (শপ ড্রয়িং এবং অ্যাজ-বিল্ট ড্রয়িং)
- Drawing transmittals (ড্রয়িং ট্রান্সমিটালস)
- RFIs (Request for Information) (আরএফআই (তথ্যের জন্য অনুরোধ))
- Submittal tracking (সাবমিটাল ট্র্যাকিং)
- Change orders / variation orders (চেঞ্জ অর্ডার / ভেরিয়েশন অর্ডার)
- Version control and approval workflows (ভার্সন কন্ট্রোল এবং অ্যাপ্রুভাল ওয়ার্কফ্লো)

#### 6.3 Contract Management _(Must Have)_

- Main contract repository (মূল কন্ট্রাক্ট রিপোজিটরি)
- Contract variations / amendments (কন্ট্রাক্ট ভেরিয়েশন / সংশোধনী)
- Claims management (ক্লেমস ম্যানেজমেন্ট)
- Retention and milestone tracking (রিটেনশন এবং মাইলস্টোন ট্র্যাকিং)
- Bank guarantee and insurance tracking (ব্যাংক গ্যারান্টি এবং ইন্স্যুরেন্স ট্র্যাকিং)
- Contract closeout checklist (কন্ট্রাক্ট ক্লোজআউট চেকলিস্ট)

---

### 7. System & Reporting

#### 7.1 Settings & Configuration _(Must Have)_

- Company profile and branding (কোম্পানি প্রোফাইল এবং ব্র্যান্ডিং)
- Financial year and currency setup (আর্থিক বছর এবং কারেন্সি সেটআপ)
- Tax rate configuration (ট্যাক্স রেট কনফিগারেশন)
- Approval matrix / workflow configuration (অ্যাপ্রুভাল ম্যাট্রিক্স / ওয়ার্কফ্লো কনফিগারেশন)
- Number series for POs, invoices, work orders (পিও, ইনভয়েস, ওয়ার্ক অর্ডারের জন্য নম্বর সিরিজ)
- Email / SMS gateway settings (ইমেইল / এসএমএস গেটওয়ে সেটিংস)
- Multi-company / multi-branch support (optional) (মাল্টি-কোম্পানি / মাল্টি-ব্রাঞ্চ সাপোর্ট (ঐচ্ছিক))

#### 7.2 Reports & Analytics _(Must Have)_

- Executive dashboard with KPIs (কেপিআই সহ এক্সিকিউটিভ ড্যাশবোর্ড)
- Cost and financial reports (কস্ট এবং ফিন্যান্সিয়াল রিপোর্ট)
- Progress and schedule reports (S-curve) (প্রগ্রেস এবং শিডিউল রিপোর্ট (S-curve))
- Labour and equipment utilisation reports (লেবার এবং ইকুইপমেন্ট ইউটিলাইজেশন রিপোর্ট)
- Custom report builder (কাস্টম রিপোর্ট বিল্ডার)
- Scheduled report delivery (email) (শিডিউলড রিপোর্ট ডেলিভারি (ইমেইল))
- PDF / Excel export (পিডিএফ / এক্সেল এক্সপোর্ট)

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
15. Reports & Analytics
```

---

## 🗂️ Suggested Laravel Module Structure

```
Modules/
├── System/
│   ├── Settings/
│   ├── Auth/
│   └── Roles/
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
| 16  | Risk Management              | Quality & Risk | Important |
| 17  | CRM & Clients                | Client & Docs  | Important |
| 18  | Document Management          | Client & Docs  | Must Have |
| 19  | Contract Management          | Client & Docs  | Must Have |
| 20  | Settings & Configuration     | System         | Must Have |
| 21  | Reports & Analytics          | System         | Must Have |
| 22  | Roles & Permissions          | System         | Must Have |

---

# Construction Management — সব মডিউলের বিস্তারিত ব্যাখ্যা (বাংলা)

> কম পরিচিত মডিউলগুলো বিশেষভাবে বিস্তারিত আলোচনা করা হয়েছে।

---

## 🟢 সহজ / পরিচিত মডিউল (সংক্ষিপ্ত)

---

### ১.১ Project Management — প্রজেক্ট ম্যানেজমেন্ট

একটি নির্মাণ প্রজেক্ট শুরু থেকে শেষ পর্যন্ত ট্র্যাক করার মূল মডিউল।

- প্রজেক্ট তৈরি করা হয় (নাম, বাজেট, শুরু ও শেষ তারিখ)
- প্রজেক্টকে ভাগ করা হয় **Phase** (পর্যায়) → **Milestone** (গুরুত্বপূর্ণ লক্ষ্যবিন্দু) → **Task** (কাজ)
- **Gantt Chart** — একটি বার চার্ট যেখানে প্রতিটি কাজের সময়সীমা দেখা যায়
- **S-Curve** — প্রজেক্টের অগ্রগতি একটি বাঁকা লাইনে দেখায়, পরিকল্পিত অগ্রগতির সাথে তুলনা করা যায়

---

### ১.২ Task & Work Orders — টাস্ক ও ওয়ার্ক অর্ডার

- **Task** — ছোট কাজের একক, যেমন "গেট নম্বর ৩-এর দরজা লাগানো"
- **Work Order** — আনুষ্ঠানিক নির্দেশনা পত্র, কোনো কর্মী বা সাবকন্ট্রাক্টরকে একটি নির্দিষ্ট কাজ করতে বলা হয়
- **Task Dependency** — একটি কাজ শেষ না হলে পরের কাজ শুরু হবে না

---

### ১.৩ Site Management — সাইট ম্যানেজমেন্ট

- প্রতিটি নির্মাণ সাইটের দৈনিক কার্যক্রম রেকর্ড করা
- **Daily Log** — আজকে সাইটে কী হলো, কতজন কাজ করলো, কী ডেলিভারি এলো
- **Field Report** — সাইট ইঞ্জিনিয়ার মাঠ থেকে যে রিপোর্ট পাঠান

---

### ৪.১ HR & Payroll — এইচআর ও বেতন

- কর্মী নিয়োগ, উপস্থিতি, ছুটি, ওভারটাইম
- বেতন স্লিপ তৈরি
- প্রতিটি প্রজেক্টে কত শ্রম খরচ হলো তা হিসাব করা
- পরিচিত মডিউল, বিশেষ ব্যাখ্যা প্রয়োজন নেই

---

### ৭.১ Settings & Configuration — সেটিংস

- কোম্পানির নাম, লোগো, ঠিকানা
- ট্যাক্স / ভ্যাট রেট সেট করা
- ইমেইল/SMS গেটওয়ে

---

### ৭.২ Reports & Analytics — রিপোর্ট ও বিশ্লেষণ

- বিভিন্ন মডিউলের ডেটা একত্রিত করে রিপোর্ট তৈরি
- PDF / Excel এ ডাউনলোড

---

## 🔴 কম পরিচিত / জটিল মডিউল (বিস্তারিত)

---

### ২.১ Budgeting & Cost Control — বাজেট ও কস্ট কন্ট্রোল

নির্মাণে শুধু বাজেট বানানোই যথেষ্ট নয় — **বাজেট বনাম আসল খরচ** প্রতিনিয়ত তুলনা করতে হয়।

**Cost Code কী?**
প্রতিটি খরচের ধরনকে একটি কোড দেওয়া হয়।
উদাহরণ:

```
01 → মাটি কাটা
02 → ফাউন্ডেশন
03 → কংক্রিট কাজ
04 → রড বাঁধাই
05 → ইলেকট্রিক্যাল
```

এই কোড দিয়ে বোঝা যায় কোন ধরনের কাজে কত খরচ হচ্ছে।

**WBS (Work Breakdown Structure) কী?**
পুরো প্রজেক্টকে ছোট ছোট কাজে ভাগ করার একটি কাঠামো।

```
প্রজেক্ট: সিটি টাওয়ার
├── ফাউন্ডেশন
│   ├── মাটি খনন
│   ├── পাইলিং
│   └── ফুটিং কংক্রিট
├── গ্রাউন্ড ফ্লোর
│   ├── কলাম
│   ├── বিম
│   └── স্ল্যাব
```

প্রতিটি WBS আইটেমের আলাদা বাজেট থাকে।

**ETC ও EAC কী?**

- **ETC (Estimate to Complete)** — এখন পর্যন্ত কাজের পর, বাকি কাজ শেষ করতে আর কত টাকা লাগবে
- **EAC (Estimate at Completion)** — পুরো প্রজেক্ট শেষে মোট কত খরচ হবে বলে ধারণা করা হচ্ছে

উদাহরণ: বাজেট ছিল ১ কোটি টাকা, এখন পর্যন্ত ৬০ লাখ খরচ হয়েছে, কিন্তু কাজ হয়েছে মাত্র ৫০%।
তাহলে EAC = ১.২ কোটি → মানে ২০ লাখ টাকা বাজেট বেশি লাগবে।

---

### ২.২ Estimating & BOQ — এস্টিমেটিং ও বিওকিউ

**BOQ (Bill of Quantities) কী?**
প্রজেক্টে কী কী উপকরণ এবং কাজ লাগবে তার বিস্তারিত তালিকা, পরিমাণ এবং দাম সহ।

উদাহরণ:

```
আইটেম        | পরিমাণ  | একক  | রেট      | মোট
-------------|---------|------|----------|----------
সিমেন্ট      | ৫০০     | ব্যাগ | ৫৫০ টাকা | ২,৭৫,০০০
রড (১২mm)   | ১০ টন   | টন   | ৮০,০০০  | ৮,০০,০০০
ইট           | ৫০,০০০  | পিস  | ১২ টাকা  | ৬,০০,০০০
```

**Rate Analysis কী?**
একটি কাজের একক মূল্য কীভাবে নির্ধারণ হয় তার বিশ্লেষণ।
উদাহরণ: ১ ঘনমিটার কংক্রিট ঢালাইয়ের রেট বের করতে হলে —
সিমেন্ট + বালু + খোয়া + পানি + শ্রমিক + মিক্সার ভাড়া = ইউনিট রেট

**Material Takeoff কী?**
ড্রয়িং দেখে হিসাব করা, পুরো প্রজেক্টে মোট কতটুকু উপকরণ লাগবে।

---

### ২.৩ Tender Management — টেন্ডার ম্যানেজমেন্ট

বাংলাদেশে নির্মাণ কাজ পাওয়ার সবচেয়ে সাধারণ পদ্ধতি হলো টেন্ডার।

**প্রক্রিয়াটি এভাবে চলে:**

```
১. Pre-qualification → বিডারদের যোগ্যতা যাচাই
      ↓
২. Tender Package তৈরি → BOQ, স্পেসিফিকেশন, শর্তাবলী
      ↓
৩. Tender বিতরণ → আগ্রহী কোম্পানিগুলোকে পাঠানো
      ↓
৪. Bid Submission → নির্দিষ্ট তারিখে দর জমা
      ↓
৫. Bid Evaluation → সব দর তুলনা করা
      ↓
৬. Award Letter → সেরা দরদাতাকে কাজ দেওয়া
```

**Pre-qualification কী?**
সবাই টেন্ডারে অংশ নিতে পারে না। আগে দেখা হয় —
অভিজ্ঞতা আছে কিনা, আর্থিক সামর্থ্য আছে কিনা, লাইসেন্স আছে কিনা।

**Bid Evaluation Matrix কী?**
সব বিডারের দর একটি টেবিলে সাজিয়ে তুলনা করা।
শুধু দাম নয়, অভিজ্ঞতা, সময়, মান — সব মিলিয়ে স্কোর দেওয়া হয়।

---

### ২.৪ Invoicing & Accounts — ইনভয়েস ও হিসাব

**IPA (Interim Payment Application) কী?**
বড় প্রজেক্টে একসাথে পুরো টাকা দেওয়া হয় না। কাজ যতটুকু হয়, ততটুকুর টাকা চাওয়া হয়।
উদাহরণ: প্রতি মাসে বা প্রতি ২০% কাজ হলে পেমেন্ট আবেদন করা।

**Retention (রিটেনশন) কী?**
ক্লায়েন্ট ইচ্ছাকৃতভাবে প্রতিটি পেমেন্ট থেকে ৫-১০% আটকে রাখে।
কাজ সম্পূর্ণ হওয়ার পর এবং ত্রুটিমুক্ত প্রমাণিত হলে এই টাকা ছেড়ে দেওয়া হয়।
এটা কন্ট্রাক্টরকে ভালো কাজ করতে বাধ্য করার একটি পদ্ধতি।

উদাহরণ:

```
Invoice পাঠানো হলো: ১০,০০,০০০ টাকা
Retention (৫%):      -৫০,০০০ টাকা
প্রকৃত পেমেন্ট:      ৯,৫০,০০০ টাকা
```

প্রজেক্ট শেষে আটকে রাখা মোট ৫০,০০০ টাকা ফেরত পাওয়া যাবে।

**Bank Guarantee কী?**
ব্যাংক থেকে নেওয়া একটি গ্যারান্টি পত্র। ক্লায়েন্ট নিশ্চিত হতে চায় যে কন্ট্রাক্টর কাজ না করলে ব্যাংক ক্ষতিপূরণ দেবে।
সাধারণত কন্ট্রাক্ট মূল্যের ১০% পরিমাণের ব্যাংক গ্যারান্টি জমা দিতে হয়।

---

### ৩.১ Vendor / Supplier Management — ভেন্ডর ম্যানেজমেন্ট

শুধু সরবরাহকারীর তালিকা রাখা নয় — পুরো সম্পর্ক ব্যবস্থাপনা।

**Pre-qualification কী?**
সব সরবরাহকারী থেকে কেনাকাটা করা ঠিক নয়। আগে যাচাই করা হয় —

- ট্রেড লাইসেন্স আছে কিনা
- পণ্যের মান ঠিক আছে কিনা
- সময়মতো সরবরাহ করতে পারে কিনা
- আর্থিকভাবে সক্ষম কিনা

**Performance Rating কী?**
প্রতিটি অর্ডারের পর ভেন্ডরকে রেটিং দেওয়া হয়।
সময়মতো ডেলিভারি? মানসম্পন্ন পণ্য? পরবর্তীতে এই রেটিং দেখে সিদ্ধান্ত নেওয়া হয়।

**Blacklist কী?**
যে ভেন্ডর বারবার সমস্যা করে, তাকে ব্ল্যাকলিস্টে রাখা হয় — পরবর্তী টেন্ডারে অংশ নিতে পারবে না।

---

### ৩.২ Procurement — ক্রয় ব্যবস্থাপনা

**PR → PO → GRN — এই তিনটি ধাপ বোঝা জরুরি:**

```
PR (Purchase Requisition)
সাইট ইঞ্জিনিয়ার বলছে: "আমাদের ৫০০ ব্যাগ সিমেন্ট দরকার"
        ↓
PO (Purchase Order)
ম্যানেজার অনুমোদন দিলে সরবরাহকারীকে আনুষ্ঠানিক অর্ডার পাঠানো হলো
        ↓
GRN (Goods Received Note)
সিমেন্ট সাইটে এলো, গণনা করে বুঝে নেওয়া হলো — "হ্যাঁ, ৫০০ ব্যাগই এসেছে"
```

**GRN কেন গুরুত্বপূর্ণ?**
GRN ছাড়া পেমেন্ট হওয়া উচিত নয়। GRN প্রমাণ করে মাল আসলেই পৌঁছেছে।

**Material Submittal কী?**
কোনো উপকরণ সাইটে ব্যবহারের আগে ইঞ্জিনিয়ার বা ক্লায়েন্টের কাছ থেকে অনুমোদন নেওয়া।
উদাহরণ: কোন ব্র্যান্ডের রড ব্যবহার করা হবে, তার নমুনা ও স্পেসিফিকেশন জমা দিয়ে অনুমোদন নেওয়া।

---

### ৩.৩ Inventory & Warehouse — মজুদ ব্যবস্থাপনা

**Material Reconciliation কী?**
মাসের শেষে হিসাব মেলানো —

```
মাসের শুরুতে ছিল:     ১০০০ ব্যাগ সিমেন্ট
এই মাসে কিনেছি:     + ৫০০ ব্যাগ
কাজে ব্যবহার হয়েছে: - ৮০০ ব্যাগ
এখন থাকার কথা:      = ৭০০ ব্যাগ

গুদামে আছে:            ৬৮০ ব্যাগ
পার্থক্য (অপচয়/চুরি):  ২০ ব্যাগ
```

এই ২০ ব্যাগের হিসাব কোথায় গেল তা খুঁজে বের করতে হবে।

**Material Issue Slip কী?**
গুদাম থেকে সাইটে মাল নেওয়ার সময় একটি স্লিপ কাটতে হয়। কোন প্রজেক্টের জন্য, কতটুকু, কে নিয়েছে — সব লেখা থাকে।

---

### ৩.৪ Subcontractor Management — সাবকন্ট্রাক্টর ম্যানেজমেন্ট

**Subcontractor কে?**
মূল ঠিকাদার (আপনি) পুরো কাজ নিজে না করে বিশেষজ্ঞ কোম্পানিকে অংশবিশেষ দেন।
উদাহরণ: ইলেকট্রিক্যাল কাজ → আলাদা ইলেকট্রিক্যাল কোম্পানিকে দেওয়া।

**Scope of Work (কাজের পরিসর) কী?**
সাবকন্ট্রাক্টর ঠিক কী কী করবে এবং কী করবে না তার সুনির্দিষ্ট বিবরণ।
এটা পরিষ্কার না হলে পরে বিরোধ হয়।

**Progress Payment Certificate কী?**
সাবকন্ট্রাক্টর যতটুকু কাজ করেছে, সেটা যাচাই করে পেমেন্টের সনদ দেওয়া।
সরাসরি ইনভয়েস নয় — আগে কাজ যাচাই, তারপর সনদ, তারপর পেমেন্ট।

---

### ৪.২ Equipment & Assets — যন্ত্রপাতি ও সম্পদ

**Owned vs Hired Plant কী?**

- **Owned (নিজস্ব)** — কোম্পানির নিজের ক্রেন, এক্সকেভেটর
- **Hired (ভাড়া)** — অন্যের কাছ থেকে ভাড়া নেওয়া যন্ত্রপাতি
  দুটোর হিসাব আলাদাভাবে রাখতে হয়।

**Preventive Maintenance কী?**
যন্ত্র নষ্ট হওয়ার আগেই নির্দিষ্ট সময় পরপর রক্ষণাবেক্ষণ করা।
উদাহরণ: প্রতি ২৫০ ঘণ্টা চলার পর ইঞ্জিন অয়েল পরিবর্তন করতে হবে।
এটা না করলে বড় মেরামতে অনেক বেশি খরচ হয়।

**Asset Depreciation কী?**
যন্ত্রপাতির মূল্য প্রতি বছর কমতে থাকে।
উদাহরণ: একটি ক্রেন কিনেছিলাম ৫০ লাখে, প্রতি বছর ১০% মূল্য কমে।
৫ বছর পর এর হিসাবি মূল্য = ৩০ লাখ।
এই হিসাব না রাখলে সম্পদের প্রকৃত মূল্য জানা যায় না।

---

### ৪.৩ Safety & Compliance — নিরাপত্তা ও সম্মতি

**PTW (Permit to Work) কী?**
কিছু বিপজ্জনক কাজ শুরু করার আগে আনুষ্ঠানিক অনুমতি নিতে হয়।
উদাহরণ: উচ্চতায় কাজ, বৈদ্যুতিক কাজ, সীমাবদ্ধ জায়গায় কাজ।
PTW ছাড়া এই কাজ শুরু করা নিষেধ।

**Toolbox Talk কী?**
প্রতিদিন কাজ শুরুর আগে ৫-১০ মিনিটের একটি নিরাপত্তা আলোচনা।
সুপারভাইজার কর্মীদের সেদিনের ঝুঁকি ও সতর্কতা বলে দেন।
এটার রেকর্ড রাখা আইনগতভাবেও গুরুত্বপূর্ণ।

**Near-miss কী?**
দুর্ঘটনা ঘটেনি, কিন্তু হতে পারতো — এই ধরনের ঘটনা।
উদাহরণ: উপর থেকে হাতুড়ি পড়েছিল, কেউ ছিল না বলে বেঁচে গেছে।
Near-miss রিপোর্ট করলে ভবিষ্যতে আসল দুর্ঘটনা রোধ করা যায়।

---

### ৫.১ Quality Control / QA — মান নিয়ন্ত্রণ

**ITP (Inspection & Test Plan) কী?**
প্রজেক্টের কোন কোন পর্যায়ে, কী কী পরীক্ষা করতে হবে তার পূর্বনির্ধারিত পরিকল্পনা।
উদাহরণ:

```
কংক্রিট ঢালাইয়ের আগে → রড পরীক্ষা করতে হবে
কংক্রিট ঢালাইয়ের সময় → স্লাম্প টেস্ট করতে হবে
কংক্রিট ঢালাইয়ের পর → কিউব টেস্ট করতে হবে
```

**NCR (Non-Conformance Report) কী?**
কোনো কাজ নির্ধারিত মান বা স্পেসিফিকেশন অনুযায়ী হয়নি — এটা রিপোর্ট করার প্রক্রিয়া।
উদাহরণ: কংক্রিটের শক্তি নির্ধারিত মাত্রায় পৌঁছায়নি → NCR ইস্যু করা হলো।
NCR পেলে কন্ট্রাক্টরকে ব্যাখ্যা দিতে হবে এবং সংশোধন করতে হবে।

**Punch List / Snagging List কী?**
প্রজেক্ট শেষ হওয়ার আগে ক্লায়েন্ট পুরো কাজ ঘুরে দেখেন এবং যেসব ছোটখাটো ত্রুটি আছে তার তালিকা করেন।
উদাহরণ: "এই ঘরের দেওয়ালে পেইন্ট এর দাগ আছে", "এই দরজার তালা ঠিকমতো লাগছে না"।
এগুলো ঠিক না করা পর্যন্ত চূড়ান্ত পেমেন্ট হবে না।

**CAR (Corrective Action Report) কী?**
NCR পাওয়ার পর কন্ট্রাক্টর কী পদক্ষেপ নেবে তার আনুষ্ঠানিক পরিকল্পনা।
"সমস্যা কেন হলো + কীভাবে ঠিক করবো + ভবিষ্যতে আর হবে না কেন" — তিনটি অংশ থাকে।

**Concrete Mix Design Approval কী?**
কংক্রিট বানাতে সিমেন্ট, বালু, খোয়া, পানির যে অনুপাত ব্যবহার করা হবে — সেটা আগে ল্যাবে পরীক্ষা করে অনুমোদন নিতে হয়।
নইলে ঢালাই দেওয়ার পর যদি শক্তি কম হয় তখন ভাঙতে হবে।

---

### ৫.২ Risk Management — ঝুঁকি ব্যবস্থাপনা

**কেন দরকার?**
নির্মাণ কাজে অনেক অনিশ্চয়তা থাকে। আগে থেকে ঝুঁকি চিহ্নিত করলে ক্ষতি কমানো যায়।

**Risk Register কী?**
একটি প্রজেক্টের সব সম্ভাব্য ঝুঁকির তালিকা। প্রতিটি ঝুঁকির জন্য লেখা থাকে:

- ঝুঁকিটা কী
- কতটা সম্ভব (১-৫)
- হলে ক্ষতি কতটুকু (১-৫)
- কে দায়িত্বশীল
- কী করা হবে

**Risk Matrix কী?**

```
প্রভাব (Impact)
  ৫ |  ৫ | ১০ | ১৫ | ২০ | ২৫ |
  ৪ |  ৪ |  ৮ | ১২ | ১৬ | ২০ |
  ৩ |  ৩ |  ৬ |  ৯ | ১২ | ১৫ |
  ২ |  ২ |  ৪ |  ৬ |  ৮ | ১০ |
  ১ |  ১ |  ২ |  ৩ |  ৪ |  ৫ |
     ১    ২    ৩    ৪    ৫
              সম্ভাবনা (Probability)

স্কোর ১-৫   → Low (সবুজ)
স্কোর ৬-১২  → Medium (হলুদ)
স্কোর ১৩-২৫ → High (লাল)
```

**Mitigation vs Contingency পার্থক্য:**

- **Mitigation (প্রতিরোধ)** — ঝুঁকি কমাতে আগে থেকে যা করবো। উদাহরণ: বন্যার ঝুঁকি → সাইটের চারদিকে বাঁধ দেওয়া।
- **Contingency (বিকল্প পরিকল্পনা)** — ঝুঁকি তবুও ঘটলে কী করবো। উদাহরণ: বন্যা হয়েই গেলে → বিকল্প সাইটে সরঞ্জাম সরানোর পরিকল্পনা।

---

### ৬.১ CRM & Clients — ক্লায়েন্ট ম্যানেজমেন্ট

**Lead / Opportunity কী?**

- **Lead** — কেউ আগ্রহ দেখিয়েছে কিন্তু এখনো নিশ্চিত নয়
- **Opportunity** — সম্ভাবনা বেশি, আলোচনা চলছে

**Lead Status Workflow:**

```
New (নতুন লিড)
    ↓
Contacted (যোগাযোগ করা হয়েছে)
    ↓
Proposal Sent (প্রস্তাব পাঠানো হয়েছে)
    ↓
Negotiation (দরকষাকষি চলছে)
    ↓
Won ✅ / Lost ❌
```

**Client Portal কী?**
ক্লায়েন্ট নিজে লগইন করে দেখতে পারবেন:

- প্রজেক্ট কতটুকু হয়েছে
- কোন ইনভয়েস পেন্ডিং আছে
- সাইটের সাম্প্রতিক ছবি

---

### ৬.২ Document Management — নথি ব্যবস্থাপনা

**Drawing Register কী?**
প্রজেক্টের সব ড্রয়িং (নকশা) এর তালিকা। প্রতিটি ড্রয়িং এর নম্বর, তারিখ, সংশোধন ইতিহাস থাকে।

**Revision Control কী?**
একটি ড্রয়িং একবার তৈরি হলেই শেষ নয়। বারবার পরিবর্তন হয়।

```
Drawing A-001, Revision 0 → প্রথম সংস্করণ
Drawing A-001, Revision 1 → প্রথম পরিবর্তন
Drawing A-001, Revision 2 → দ্বিতীয় পরিবর্তন
```

সাইটে সবসময় সর্বশেষ সংস্করণ ব্যবহার হচ্ছে কিনা নিশ্চিত করতে হয়।

**RFI (Request for Information) কী?**
ড্রয়িং বা স্পেসিফিকেশনে কিছু অস্পষ্ট থাকলে কন্ট্রাক্টর ডিজাইনার বা ক্লায়েন্টকে লিখিতভাবে প্রশ্ন করে।
উদাহরণ: "ড্রয়িংয়ে ৩য় তলার এই কলামের মাপ দেওয়া নেই, কত হবে?"

**Transmittal কী?**
ড্রয়িং বা ডকুমেন্ট পাঠানোর সময় একটি কভারশিট। কী পাঠানো হলো, কটি কপি, কাকে পাঠানো হলো — এটা রেকর্ড রাখে।

**Variation Order / Change Order কী?**
চুক্তি স্বাক্ষরের পর যদি কাজের পরিবর্তন হয় — নতুন কাজ যোগ হয়, কিছু বাদ যায়, বা ডিজাইন পরিবর্তন হয় — সেটা আনুষ্ঠানিকভাবে নথিভুক্ত করা।
এটা ছাড়া অতিরিক্ত কাজের পেমেন্ট পাওয়া কঠিন হয়।

**Submittal কী?**
কন্ট্রাক্টর ক্লায়েন্ট বা ইঞ্জিনিয়ারের কাছে উপকরণ বা পদ্ধতির অনুমোদনের জন্য যে নথি জমা দেয়।
উদাহরণ: কোন ব্র্যান্ডের টাইলস ব্যবহার হবে তার নমুনা ও ক্যাটালগ জমা দিয়ে অনুমোদন নেওয়া।

---

### ৬.৩ Contract Management — চুক্তি ব্যবস্থাপনা

**Claims Management কী?**
কন্ট্রাক্টর যদি মনে করে তার কারণ ছাড়াই বাড়তি খরচ হয়েছে বা সময় বেশি লেগেছে, সে আনুষ্ঠানিকভাবে ক্লেম করতে পারে।
উদাহরণ: ক্লায়েন্ট ২ মাস দেরিতে ড্রয়িং দিয়েছে → কন্ট্রাক্টর সময় বৃদ্ধি ও অতিরিক্ত খরচের ক্লেম করল।

**Contract Closeout কী?**
প্রজেক্ট শেষে আনুষ্ঠানিকভাবে সব বিষয় নিষ্পত্তি করার প্রক্রিয়া:

- সব পেমেন্ট হয়েছে?
- Retention ছেড়ে দেওয়া হয়েছে?
- Bank Guarantee ফেরত দেওয়া হয়েছে?
- As-built drawings জমা দেওয়া হয়েছে?
- Punch list সম্পন্ন হয়েছে?
  সব ঠিক হলে চুক্তি আনুষ্ঠানিকভাবে বন্ধ হয়।

---

## 📌 মডিউলগুলোর সম্পর্ক একনজরে

```
CRM (লিড)
    ↓ লিড জেতার পর
PROJECT তৈরি
    ↓
BOQ / ESTIMATING → BUDGET নির্ধারণ
    ↓                     ↓
TENDERING          COST CONTROL (বাজেট vs খরচ)
    ↓
CONTRACT চূড়ান্ত
    ↓
PROCUREMENT (কেনাকাটা) → INVENTORY (গুদাম)
    ↓
SITE MANAGEMENT (দৈনিক কাজ)
    ↓
TASKS & WORK ORDERS
    ↓
HR (শ্রমিক)  +  EQUIPMENT (যন্ত্র)
    ↓
QUALITY CONTROL (মান যাচাই)
    ↓
SAFETY (নিরাপত্তা)
    ↓
RISK MANAGEMENT (ঝুঁকি নজরদারি)
    ↓
INVOICING (পেমেন্ট চাওয়া)
    ↓
DOCUMENTS (নথি সংরক্ষণ)
    ↓
CONTRACT CLOSEOUT (প্রজেক্ট বন্ধ)
    ↓
REPORTS (সব মডিউলের সারসংক্ষেপ)
```

---

_বাংলাদেশের নির্মাণ শিল্পের প্রেক্ষাপটে তৈরি — Laravel Construction Management ERP_

_Generated for Laravel Construction Management ERP project._
