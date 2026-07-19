# Rod Calculation Module Documentation

## Overview

Rod Calculation (Bar Bending Schedule — BBS) calculates steel quantity for RCC members on a project.

Users create a calculation sheet, add structural members, define bars (diameter, spacing, hooks, etc.), and the system auto-computes cutting length, bar count, total length, and total weight.

**Domain placement:** Finance → Estimating Analysis (alongside BOQ, Rate Analysis, Material Takeoffs)  
**Route prefix:** `/dashboard/finance/rod-calculations`  
**Route name:** `admin.finance.rod-calculations.*`  
**Privilege:** `finance.view` / `finance.create` / `finance.edit` / `finance.delete` / `finance.export`

---

## Status & Scope

| Layer | Status |
|---|---|
| This plan document | Complete (MVP + Phase 2 backlog) |
| Implementation (migrations, models, UI) | Complete (MVP) |
| MVP | Schema + CRUD + auto-calc + summary + PDF |
| Phase 2 | Shape codes UI, stirrups helpers, crank, waste %, Excel, full approval workflow, revision history |

---

## Units (mandatory)

| Measure | Unit | Notes |
|---|---|---|
| Member dimensions (L/W/H/D/T) | **mm** | Store and enter in millimetres |
| Cover, spacing, hooks, bends, lap | **mm** | |
| Diameter (D) | **mm** | Standard: 8, 10, 12, 16, 20, 25, 32 |
| Cutting / total length | **mm** | Convert to metres only for weight: `/ 1000` |
| Unit weight | **kg/m** | Formula `D² / 162` |
| Total weight | **kg** | |

---

## Database Schema

### rod_calculations

| Column | Type | Notes |
|---|---|---|
| id | BIGINT PK | |
| project_id | BIGINT FK → projects | required |
| reference_no | VARCHAR(50) | unique; auto-generated, e.g. `BBS-2026-00001` |
| title | VARCHAR(255) | e.g. "Ground Floor BBS" |
| description | TEXT NULL | |
| steel_grade | VARCHAR(20) NULL | e.g. `FY400`, `FY500`, `FY550`; project specification |
| revision | VARCHAR(50) NULL | free-text label for MVP (e.g. `R0`, `R1`); history = Phase 2 |
| status | VARCHAR(20) | `draft`, `approved`, `completed` (see `RodCalculationStatus` constants) |
| formula_version | VARCHAR(10) | default `'1.0'`; stamps calculation formula revision |
| waste_percent | DECIMAL(5,2) NULL | reserved; used in Phase 2 |
| created_by | BIGINT FK → users | |
| approved_by | BIGINT NULL FK → users | set when status → approved |
| approved_at | TIMESTAMP NULL | |
| created_at / updated_at | TIMESTAMP | |
| deleted_at | TIMESTAMP NULL | SoftDeletes |

**Indexes:** `project_id`, `status`, `created_by`, `reference_no` (unique)

### rod_members

| Column | Type | Notes |
|---|---|---|
| id | BIGINT PK | |
| rod_calculation_id | BIGINT FK | cascade delete |
| type | VARCHAR(30) | see `RodMemberType` constants (e.g. `footing`, `column`, `beam`) |
| member_code | VARCHAR(100) | e.g. `B1`, `C2`, `F-01` |
| quantity | INT | default 1; min 1 |
| length | DECIMAL(10,2) NULL | mm |
| width | DECIMAL(10,2) NULL | mm |
| height | DECIMAL(10,2) NULL | mm |
| depth | DECIMAL(10,2) NULL | mm |
| thickness | DECIMAL(10,2) NULL | mm |
| cover | DECIMAL(10,2) | mm; required; default by type (see defaults) |
| sort_order | INT | default 0 |
| remarks | TEXT NULL | |
| created_at / updated_at | TIMESTAMP | |

**Indexes:** `rod_calculation_id`, `type`, `member_code`

### rod_member_bars

| Column | Type | Notes |
|---|---|---|
| id | BIGINT PK | |
| rod_member_id | BIGINT FK | cascade delete |
| bar_name | VARCHAR(100) | e.g. `Main bar`, `Top`, `Stirrup` |
| direction | VARCHAR(20) | `X`, `Y`, `TOP`, `BOTTOM`, `MAIN`, `STIRRUP`, `DISTRIBUTION`, `OTHER` (see `BarDirection` constants) |
| diameter | DECIMAL(5,2) | mm |
| spacing | DECIMAL(10,2) NULL | mm; null = manual bar count |
| hook_length | DECIMAL(10,2) | mm; default 0 |
| bend_length | DECIMAL(10,2) | mm; default 0 |
| lap_length | DECIMAL(10,2) | mm; default 0 |
| actual_size | DECIMAL(10,2) | mm — span used before cover deduction |
| cutting_length | DECIMAL(10,2) | mm — **computed** |
| bars_count | INT | **computed** if spacing set; else manual |
| total_length | DECIMAL(12,2) | mm — **computed** |
| unit_weight | DECIMAL(10,4) | kg/m — **computed** |
| total_weight | DECIMAL(12,2) | kg — **computed** |
| shape_code | VARCHAR(50) NULL | reserved for Phase 2 |
| is_manual_count | BOOLEAN | default false; true skips spacing formula |
| sort_order | INT | default 0 |
| remarks | TEXT NULL | |
| created_at / updated_at | TIMESTAMP | |

**Indexes:** `rod_member_id`, `diameter`, `direction`

---

## Relationships

```text
projects
└── rod_calculations (1:N)
    ├── createdBy / approvedBy (User)
    └── rod_members (1:N)
        └── rod_member_bars (1:N)
```

Eloquent:

- `Project` → `hasMany(RodCalculation::class)`
- `RodCalculation` → `belongsTo(Project)`, `hasMany(RodMember)`, `belongsTo(User, created_by|approved_by)`
- `RodMember` → `belongsTo(RodCalculation)`, `hasMany(RodMemberBar)`
- `RodMemberBar` → `belongsTo(RodMember)`

---

## Constants (VARCHAR backing)

All `ENUM`-like columns use `VARCHAR` with PHP constants for easier maintenance. Define in a dedicated class or per-model:

```php
// app/Constants/RodCalculationConstants.php
class RodCalculationConstants
{
    const STATUS_DRAFT     = 'draft';
    const STATUS_APPROVED  = 'approved';
    const STATUS_COMPLETED = 'completed';

    const STEEL_GRADES = ['FY400', 'FY500', 'FY550'];

    const FORMULA_VERSION = '1.0'; // current default
}

class RodMemberType
{
    const FOOTING    = 'footing';
    const PILE_CAP   = 'pile_cap';
    const PILE       = 'pile';
    const COLUMN     = 'column';
    const BEAM       = 'beam';
    const GRADE_BEAM = 'grade_beam';
    const LINTEL     = 'lintel';
    const SLAB       = 'slab';
    const WALL       = 'wall';
    const STAIR      = 'stair';
    const CUSTOM     = 'custom';

    const ALL = [
        self::FOOTING, self::PILE_CAP, self::PILE, self::COLUMN,
        self::BEAM, self::GRADE_BEAM, self::LINTEL, self::SLAB,
        self::WALL, self::STAIR, self::CUSTOM,
    ];
}

class BarDirection
{
    const X              = 'X';
    const Y              = 'Y';
    const TOP            = 'TOP';
    const BOTTOM         = 'BOTTOM';
    const MAIN           = 'MAIN';
    const STIRRUP        = 'STIRRUP';
    const DISTRIBUTION   = 'DISTRIBUTION';
    const OTHER          = 'OTHER';

    const ALL = [
        self::X, self::Y, self::TOP, self::BOTTOM,
        self::MAIN, self::STIRRUP, self::DISTRIBUTION, self::OTHER,
    ];
}
```

Adding a new member type or direction = add one constant + update `ALL` array. No migration required.

---

## Member Types & Dimension Rules

| Type | Typical dims used | Default cover (mm) | Notes |
|---|---|---|---|
| footing | L, W, thickness/depth | 50 | Raft / isolated footing |
| pile_cap | L, W, depth | 50 | |
| pile | diameter→width, length/height | 50 | circular: use width as dia |
| column | width, depth, height | 40 | |
| beam | length, width, depth | 25 | |
| grade_beam | length, width, depth | 40 | |
| lintel | length, width, depth | 25 | |
| slab | length, width, thickness | 20 | |
| wall | length, height, thickness | 25 | |
| stair | length, width, thickness | 20 | |
| custom | any | 25 | user-defined; all dims optional |

**UI rule:** Show only relevant dim fields per type; store unused as `NULL`.

**`actual_size` for bars:** User enters the clear span / member length along bar direction (mm). Service does not invent it from member dims in MVP (avoids wrong assumptions); Phase 2 may auto-suggest from member geometry.

---

## Formulas

All lengths in **mm** unless noted.

### Effective Length

```
Effective Length = Actual Size − (Cover × 2)
```

If result &lt; 0 → validation error.

### Cutting Length

```
Cutting Length = Effective Length + Hook Length + Bend Length + Lap Length
```

### Bar Count

```
If is_manual_count OR spacing is null/0:
  Bars Count = user-entered value (min 1)
Else:
  Bars Count = floor(Effective Length / Spacing) + 1
```

### Unit Weight (kg/m)

```
Unit Weight = D² / 162
```

Where `D` = diameter in mm.

### Total Length (mm)

```
Total Length = Cutting Length × Bars Count × Member Quantity
```

### Total Weight (kg)

```
Total Weight = (Total Length / 1000) × Unit Weight
```

### Optional — with waste (Phase 2)

```
Total Weight (with waste) = Total Weight × (1 + waste_percent / 100)
```

### Sheet Summary

```
Sum total_weight of all bars
Group by diameter → Σ weight, Σ length
Group by member → Σ weight
```

---

## Calculation Trigger

| Event | Behaviour |
|---|---|
| Create / update bar | `RodCalculationService::recalculateBar($bar)` runs immediately before save |
| Update member quantity / cover | Recalculate **all** bars under that member |
| Soft-delete member | Cascade soft? Prefer hard-delete children on member delete; soft-delete only calculation header |
| Show / PDF | Read stored computed fields (do not recompute unless "Recalculate" action) |

**Recalculate** action on calculation show page: re-run all bars (fixes stale data after formula changes).

---

## Status Workflow

```text
draft ──approve──► approved ──complete──► completed
  ▲                   │
  └─────reopen────────┘  (admin / finance.edit only)
```

| Transition | Rules |
|---|---|
| → `approved` | Sets `approved_by`, `approved_at`; calculation must have ≥1 member with ≥1 bar |
| → `completed` | Locked for edit (except reopen); used when steel ordered / issued |
| Edit while `approved` / `completed` | Blocked unless reopen → `draft` |
| Approve privilege | `finance.edit` (MVP simple approve; full `Approvable` workflow = Phase 2) |

---

## Validation Rules

### Calculation

| Field | Rule |
|---|---|
| project_id | required, exists:projects |
| title | required, string, max:255 |
| steel_grade | nullable, in:FY400,FY500,FY550 |
| revision | nullable, max:50 |
| status | nullable, in:draft,approved,completed |
| formula_version | nullable, max:10 |
| waste_percent | nullable, numeric, 0–100 |

### Member

| Field | Rule |
|---|---|
| type | required, in:footing,pile_cap,pile,column,beam,grade_beam,lintel,slab,wall,stair,custom |
| member_code | required, max:100, unique per calculation |
| quantity | required, integer, min:1 |
| cover | required, numeric, min:0 |
| dims | numeric, min:0 when present; at least one dim recommended for non-custom |

### Bar

| Field | Rule |
|---|---|
| bar_name | required, max:100 |
| direction | required, in:X,Y,TOP,BOTTOM,MAIN,STIRRUP,DISTRIBUTION,OTHER |
| diameter | required, numeric, in:8,10,12,16,20,25,32 (or custom > 0) |
| actual_size | required, numeric, > cover×2 |
| spacing | nullable; required if not manual count; > 0 |
| bars_count | required if manual; integer ≥ 1 |
| hook/bend/lap | nullable → treat as 0; numeric ≥ 0 |

---

## UI Flow

### Screens

| Screen | Route | Purpose |
|---|---|---|
| Index | `GET .../rod-calculations` | List with project + status filters |
| Create | `GET/POST .../rod-calculations/create` | Header only (project, title, revision) |
| Show | `GET .../rod-calculations/{id}` | Members + bars nested; summary cards; actions |
| Edit header | `GET/PUT .../rod-calculations/{id}/edit` | Title, description, revision (if draft) |
| Add member | Modal or nested form on Show | Type, code, dims, cover, qty |
| Add / edit bar | Nested form under member | Inputs → live preview of computed fields (Alpine) |
| PDF | `GET .../rod-calculations/{id}/pdf` | Printable BBS |
| Approve / Reopen / Complete / Recalculate | POST actions on Show | Status + recompute |

### Index columns

Reference No · Project · Title · Steel Grade · Revision · Status · Members count · Total steel (kg) · Updated · Actions

### Show layout

1. Header card (reference_no, project, status, steel_grade, revision, created/approved by)
2. Summary: total kg, by-diameter table
3. Members accordion / table
4. Per member: bars table with computed columns (read-only computed cells)
5. Actions: Add Member, Recalculate, Approve, PDF, Edit, Delete

### Sidebar

Finance → Estimating Analysis:

- Bill of Quantities
- Rate Analysis
- Tenders
- **Rod Calculations** ← new

### Client role

Not exposed (internal estimating). No client portal access.

---

## Laravel Structure

```text
app/
├── Constants/
│   └── RodCalculationConstants.php   (RodCalculationConstants, RodMemberType, BarDirection)
├── Models/
│   ├── RodCalculation.php
│   ├── RodMember.php
│   └── RodMemberBar.php
├── Services/
│   └── RodCalculationService.php
└── Http/Controllers/Admin/Finance/
    └── RodCalculationController.php

database/migrations/
├── xxxx_create_rod_calculations_table.php
├── xxxx_create_rod_members_table.php
└── xxxx_create_rod_member_bars_table.php

resources/views/admin/finance/rod-calculations/
├── index.blade.php
├── create.blade.php
├── edit.blade.php
├── show.blade.php
├── partials/
│   ├── member-form.blade.php
│   ├── bar-form.blade.php
│   └── summary.blade.php
└── pdf/
    └── bbs.blade.php
```

### Service API (MVP)

```php
RodCalculationService::
  generateReferenceNo(Project $project): string
  recalculateBar(RodMemberBar $bar): RodMemberBar
  recalculateMember(RodMember $member): void
  recalculateAll(RodCalculation $calc): void
  summary(RodCalculation $calc): array  // total_kg, by_diameter[], by_member[]
```

### Controller actions (MVP)

```text
index, create, store, show, edit, update, destroy
storeMember, updateMember, destroyMember
storeBar, updateBar, destroyBar
approve, reopen, complete, recalculate, pdf
```

---

## Integration (MVP vs later)

| Integration | MVP | Phase 2 |
|---|---|---|
| Project FK | Yes | — |
| BOQ / Material Takeoff link | No | Optional link bar totals → takeoff / BOQ steel line |
| Materials master (steel stock) | No | Push qty by diameter |
| Approvable trait / ApprovalService | No (simple status) | Yes |
| Spatie Media (attachments / drawings) | No | Optional |

---

## Numbering & Defaults

- `reference_no` auto-generated on create: `BBS-{PROJECT_CODE}-{SEQ}` (zero-padded 5 digits, e.g. `BBS-PRJ01-00001`). Falls back to `BBS-{YEAR}-{SEQ}` if project has no code.
- Formula version stamped from `RodCalculationConstants::FORMULA_VERSION` on create.
- Diameter dropdown: 8, 10, 12, 16, 20, 25, 32 (+ "Other").
- Steel grade dropdown: FY400, FY500, FY550 (+ nullable).
- Cover defaults applied in UI when type changes (user can override).

---

## Build Order

1. Migrations + models + relationships  
2. `RodCalculationService` + unit tests for formulas  
3. Controller CRUD (header) + views index/create/show/edit  
4. Nested member + bar CRUD on show  
5. Summary + recalculate  
6. Status actions (approve / reopen / complete)  
7. PDF export  
8. Sidebar + routes under finance  
9. Phase 2 backlog items  

---

## Phase 2 — Future Features

| Feature | Notes |
|---|---|
| Shape codes | IS 2502 / BS shapes; cutting length from shape geometry |
| Stirrups helper | Auto cutting length from width×depth + hooks |
| Crank bars | Extra length for cranked bars |
| Waste % | Apply `waste_percent` on summary & PDF |
| Excel export | Maatwebsite — BBS sheet + diameter summary |
| Full approval workflow | `Approvable` + `ApprovalService` |
| Revision history | Snapshot or version rows when revision changes |
| Geometry auto-suggest | Suggest `actual_size` from member L/W/H |
| BOQ / takeoff sync | Push totals into estimating |
| Document number series | Customisable `BBS-{PRJ}-{SEQ}` format |
| Per-bar audit tracking | `created_by` / `updated_by` on `rod_members` and `rod_member_bars` |

---

## Acceptance Criteria (MVP)

- [ ] Create calculation linked to a project with auto-generated `reference_no`
- [ ] Steel grade selectable (FY400/FY500/FY550) on calculation header
- [ ] Add members of each type with correct dim fields
- [ ] Add bars; system stores computed cutting length, count, weight
- [ ] Changing quantity/cover/spacing updates related weights
- [ ] Show page displays total kg and by-diameter summary with reference_no in header
- [ ] Draft → Approved → Completed works; edit locked when not draft
- [ ] PDF prints BBS with reference_no, steel grade, member/bar lines and totals
- [ ] Soft-delete calculation; cascade delete members/bars
- [ ] Gated by finance privileges; appears under Estimating sidebar  

---

_Last updated: 2026-07-18 — MVP implementation complete. Updated with: VARCHAR over ENUM, reference_no, formula_version, steel_grade, constants class, Phase 2 audit tracking._
