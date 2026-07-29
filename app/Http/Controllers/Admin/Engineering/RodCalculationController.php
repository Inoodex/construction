<?php

namespace App\Http\Controllers\Admin\Engineering;

use App\Constants\BarDirection;
use App\Constants\RodCalculationConstants;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\RodCalculation;
use App\Models\RodMember;
use App\Models\RodMemberBar;
use App\Exports\RodCalculationExport;
use App\Services\RodCalculationService;
use App\Models\Material;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RodCalculationController extends Controller
{
    protected RodCalculationService $service;

    public function __construct(RodCalculationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = RodCalculation::with('project', 'creator', 'members.bars')->withCount('members');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('reference_no', 'like', "%{$s}%")
                  ->orWhere('title', 'like', "%{$s}%")
                  ->orWhere('steel_grade', 'like', "%{$s}%")
                  ->orWhere('concrete_grade', 'like', "%{$s}%")
                  ->orWhereHas('members', function($mq) use ($s) {
                      $mq->where('member_code', 'like', "%{$s}%")
                         ->orWhere('remarks', 'like', "%{$s}%");
                  });
            });
        }

        $rodCalculations = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.engineering.rod-calculations.index', compact('rodCalculations', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.engineering.rod-calculations.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'              => 'required|exists:projects,id',
            'title'                   => 'required|string|max:255',
            'description'             => 'nullable|string',
            'steel_grade'             => 'nullable|string|max:50',
            'concrete_grade'          => 'nullable|string|max:20',
            'revision'                => 'nullable|max:50',
            'waste_percent'           => 'nullable|numeric|min:0|max:100',
            'concrete_waste_percent'  => 'nullable|numeric|min:0|max:100',
            'steel_price_per_kg'      => 'nullable|numeric|min:0',
            'cement_price_per_bag'    => 'nullable|numeric|min:0',
            'sand_price_per_cft'      => 'nullable|numeric|min:0',
            'aggregate_price_per_cft' => 'nullable|numeric|min:0',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        $validated['reference_no'] = $this->service->generateReferenceNo($project);
        $validated['status'] = RodCalculationConstants::STATUS_DRAFT;
        $validated['created_by'] = Auth::id();

        $calc = RodCalculation::create($validated);

        return redirect()->route('admin.finance.rod-calculations.show', $calc->id)
            ->with('success', 'Rod & Concrete Sheet created. Add members now.');
    }

    public function show(RodCalculation $rodCalculation)
    {
        $rodCalculation->load('project', 'creator', 'approver', 'requisition', 'members.bars');
        $summary = $this->service->summary($rodCalculation);

        return view('admin.engineering.rod-calculations.show', compact('rodCalculation', 'summary'));
    }

    public function edit(RodCalculation $rodCalculation)
    {
        $rodCalculation->load('project');
        $projects = Project::all();

        return view('admin.engineering.rod-calculations.edit', compact('rodCalculation', 'projects'));
    }

    public function update(Request $request, RodCalculation $rodCalculation)
    {
        abort_if(!$rodCalculation->isDraft(), 403, 'Only draft calculations can be edited.');

        $validated = $request->validate([
            'title'                   => 'required|string|max:255',
            'description'             => 'nullable|string',
            'steel_grade'             => 'nullable|string|max:50',
            'concrete_grade'          => 'nullable|string|max:20',
            'revision'                => 'nullable|max:50',
            'waste_percent'           => 'nullable|numeric|min:0|max:100',
            'concrete_waste_percent'  => 'nullable|numeric|min:0|max:100',
            'steel_price_per_kg'      => 'nullable|numeric|min:0',
            'cement_price_per_bag'    => 'nullable|numeric|min:0',
            'sand_price_per_cft'      => 'nullable|numeric|min:0',
            'aggregate_price_per_cft' => 'nullable|numeric|min:0',
        ]);

        $rodCalculation->update($validated);
        $this->service->recalculateAll($rodCalculation);

        return redirect()->route('admin.finance.rod-calculations.show', $rodCalculation->id)
            ->with('success', 'Rod & Concrete Sheet updated successfully.');
    }

    public function destroy(RodCalculation $rodCalculation)
    {
        abort_if(!$rodCalculation->isDraft(), 403, 'Only draft calculations can be deleted.');

        $rodCalculation->members()->each(function ($member) {
            $member->bars()->delete();
        });
        $rodCalculation->delete();

        return redirect()->route('admin.finance.rod-calculations.index')
            ->with('success', 'Rod Calculation deleted successfully.');
    }

    // ─── Member CRUD ────────────────────────────────────────────

    public function storeMember(Request $request, RodCalculation $rodCalculation)
    {
        abort_if(!$rodCalculation->isDraft(), 403, 'Only draft calculations can be modified.');

        $validated = $request->validate([
            'type'         => 'required|string|max:30',
            'member_code'  => ['required', 'string', 'max:100', Rule::unique('rod_members', 'member_code')->where('rod_calculation_id', $rodCalculation->id)],
            'quantity'     => 'required|integer|min:1',
            'length'       => 'nullable|numeric|min:0',
            'width'        => 'nullable|numeric|min:0',
            'height'       => 'nullable|numeric|min:0',
            'depth'        => 'nullable|numeric|min:0',
            'thickness'    => 'nullable|numeric|min:0',
            'cover'        => 'required|numeric|min:0',
            'sort_order'   => 'nullable|integer',
            'remarks'      => 'nullable|string',
        ]);

        $validated['rod_calculation_id'] = $rodCalculation->id;
        $validated['sort_order'] = $validated['sort_order'] ?? $rodCalculation->members()->count();

        $member = RodMember::create($validated);

        return back()->with('success', "Member '{$member->member_code}' added.");
    }

    public function updateMember(Request $request, RodCalculation $rodCalculation, RodMember $rodMember)
    {
        abort_if(!$rodCalculation->isDraft(), 403, 'Only draft calculations can be modified.');

        $validated = $request->validate([
            'type'         => 'required|string|max:30',
            'member_code'  => ['required', 'string', 'max:100', Rule::unique('rod_members', 'member_code')->where('rod_calculation_id', $rodCalculation->id)->ignore($rodMember->id)],
            'quantity'     => 'required|integer|min:1',
            'length'       => 'nullable|numeric|min:0',
            'width'        => 'nullable|numeric|min:0',
            'height'       => 'nullable|numeric|min:0',
            'depth'        => 'nullable|numeric|min:0',
            'thickness'    => 'nullable|numeric|min:0',
            'cover'        => 'required|numeric|min:0',
            'sort_order'   => 'nullable|integer',
            'remarks'      => 'nullable|string',
        ]);

        $rodMember->update($validated);
        $this->service->recalculateMember($rodMember->fresh('bars'));

        return back()->with('success', "Member '{$rodMember->member_code}' updated.");
    }

    public function destroyMember(RodCalculation $rodCalculation, RodMember $rodMember)
    {
        abort_if(!$rodCalculation->isDraft(), 403, 'Only draft calculations can be modified.');

        $rodMember->bars()->delete();
        $rodMember->delete();

        return back()->with('success', 'Member deleted.');
    }

    // ─── Bar CRUD ───────────────────────────────────────────────

    public function storeBar(Request $request, RodCalculation $rodCalculation, RodMember $rodMember)
    {
        abort_if(!$rodCalculation->isDraft(), 403, 'Only draft calculations can be modified.');

        $validated = $request->validate([
            'bar_name'       => 'required|string|max:100',
            'direction'      => 'required|string|max:50',
            'diameter'       => 'required|numeric|in:8,10,12,16,20,25,32',
            'actual_size'    => 'nullable|numeric|min:0',
            'spacing'        => 'nullable|numeric|min:1',
            'hook_length'    => 'nullable|numeric|min:0',
            'bend_length'    => 'nullable|numeric|min:0',
            'lap_length'     => 'nullable|numeric|min:0',
            'bars_count'     => 'nullable|integer|min:1',
            'is_manual_count'=> 'nullable|boolean',
            'sort_order'     => 'nullable|integer',
            'remarks'        => 'nullable|string',
        ]);

        if (empty($validated['actual_size']) || $validated['actual_size'] <= 0) {
            $validated['actual_size'] = $this->service->determineActualSize($rodMember, $validated['direction']);
        }

        if (empty($validated['hook_length']) || $validated['hook_length'] <= 0) {
            $validated['hook_length'] = $this->service->determineHookLength((float) $validated['diameter'], $validated['direction']);
        }

        $validated['rod_member_id'] = $rodMember->id;
        $validated['is_manual_count'] = $validated['is_manual_count'] ?? false;
        $validated['hook_length'] = $validated['hook_length'] ?? 0;
        $validated['bend_length'] = $validated['bend_length'] ?? 0;
        $validated['lap_length'] = $validated['lap_length'] ?? 0;
        $validated['bars_count'] = $validated['bars_count'] ?? 1;
        $validated['sort_order'] = $validated['sort_order'] ?? $rodMember->bars()->count();

        $bar = RodMemberBar::create($validated);
        $this->service->recalculateBar($bar);
        $bar->save();

        return back()->with('success', "Bar '{$bar->bar_name}' added.");
    }

    public function updateBar(Request $request, RodCalculation $rodCalculation, RodMember $rodMember, RodMemberBar $rodBar)
    {
        abort_if(!$rodCalculation->isDraft(), 403, 'Only draft calculations can be modified.');

        $validated = $request->validate([
            'bar_name'       => 'required|string|max:100',
            'direction'      => 'required|string|max:50',
            'diameter'       => 'required|numeric|in:8,10,12,16,20,25,32',
            'actual_size'    => 'nullable|numeric|min:0',
            'spacing'        => 'nullable|numeric|min:1',
            'hook_length'    => 'nullable|numeric|min:0',
            'bend_length'    => 'nullable|numeric|min:0',
            'lap_length'     => 'nullable|numeric|min:0',
            'bars_count'     => 'nullable|integer|min:1',
            'is_manual_count'=> 'nullable|boolean',
            'sort_order'     => 'nullable|integer',
            'remarks'        => 'nullable|string',
        ]);

        if (empty($validated['actual_size']) || $validated['actual_size'] <= 0) {
            $validated['actual_size'] = $this->service->determineActualSize($rodMember, $validated['direction']);
        }

        if (empty($validated['hook_length']) || $validated['hook_length'] <= 0) {
            $validated['hook_length'] = $this->service->determineHookLength((float) $validated['diameter'], $validated['direction']);
        }

        $validated['is_manual_count'] = $validated['is_manual_count'] ?? false;
        $validated['hook_length'] = $validated['hook_length'] ?? 0;
        $validated['bend_length'] = $validated['bend_length'] ?? 0;
        $validated['lap_length'] = $validated['lap_length'] ?? 0;

        $rodBar->update($validated);
        $rodBar->load('member');
        $this->service->recalculateBar($rodBar);
        $rodBar->save();

        return back()->with('success', "Bar '{$rodBar->bar_name}' updated.");
    }

    public function destroyBar(RodCalculation $rodCalculation, RodMember $rodMember, RodMemberBar $rodBar)
    {
        abort_if(!$rodCalculation->isDraft(), 403, 'Only draft calculations can be modified.');

        $rodBar->delete();

        return back()->with('success', 'Bar deleted.');
    }

    // ─── Status Actions ─────────────────────────────────────────

    public function approve(RodCalculation $rodCalculation)
    {
        abort_if(!$rodCalculation->isDraft(), 403, 'Only draft calculations can be approved.');

        if ($rodCalculation->members()->count() === 0) {
            return back()->with('error', 'Cannot approve: add at least one member with bars.');
        }

        $rodCalculation->update([
            'status'      => RodCalculationConstants::STATUS_APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Rod Calculation approved.');
    }

    public function complete(RodCalculation $rodCalculation)
    {
        abort_if(!$rodCalculation->isApproved(), 403, 'Only approved calculations can be completed.');

        $rodCalculation->update(['status' => RodCalculationConstants::STATUS_COMPLETED]);

        return back()->with('success', 'Rod Calculation marked as completed.');
    }

    public function reopen(RodCalculation $rodCalculation)
    {
        abort_if(!$rodCalculation->isApproved() && !$rodCalculation->isCompleted(), 403, 'Nothing to reopen.');

        $rodCalculation->update([
            'status'      => RodCalculationConstants::STATUS_DRAFT,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('success', 'Rod Calculation reopened to draft.');
    }

    public function recalculate(RodCalculation $rodCalculation)
    {
        $this->service->recalculateAll($rodCalculation);

        return back()->with('success', 'All calculations refreshed.');
    }

    public function duplicate(RodCalculation $rodCalculation)
    {
        $rodCalculation->load('members.bars');

        $newCalc = $rodCalculation->replicate([
            'reference_no',
            'status',
            'approved_by',
            'approved_at',
        ]);

        $newCalc->reference_no = $this->service->generateReferenceNo($rodCalculation->project);
        $newCalc->title = $rodCalculation->title . ' (Copy)';
        $newCalc->status = RodCalculationConstants::STATUS_DRAFT;
        $newCalc->created_by = Auth::id();
        $newCalc->save();

        foreach ($rodCalculation->members as $member) {
            $newMember = $member->replicate();
            $newMember->rod_calculation_id = $newCalc->id;
            $newMember->save();

            foreach ($member->bars as $bar) {
                $newBar = $bar->replicate();
                $newBar->rod_member_id = $newMember->id;
                $newBar->save();
            }
        }

        return redirect()->route('admin.finance.rod-calculations.show', $newCalc->id)
            ->with('success', "Rod Calculation '{$newCalc->reference_no}' duplicated successfully.");
    }

    // ─── PDF Export ─────────────────────────────────────────────

    public function pdf(RodCalculation $rodCalculation)
    {
        $rodCalculation->load('project', 'creator', 'approver', 'members.bars');
        $summary = $this->service->summary($rodCalculation);

        $pdf = Pdf::loadView('admin.engineering.rod-calculations.pdf.bbs', compact('rodCalculation', 'summary'));

        return $pdf->stream("BBS-{$rodCalculation->reference_no}.pdf");
    }

    public function excel(RodCalculation $rodCalculation)
    {
        return Excel::download(
            new RodCalculationExport($rodCalculation),
            "BBS-{$rodCalculation->reference_no}.xlsx"
        );
    }

    public function createPurchaseRequisition(RodCalculation $rodCalculation)
    {
        abort_if($rodCalculation->isDraft(), 403, 'Purchase Requisition can only be created from Approved or Completed calculation sheets.');

        if ($rodCalculation->purchase_requisition_id) {
            $existingPr = PurchaseRequisition::find($rodCalculation->purchase_requisition_id);
            if ($existingPr) {
                return redirect()->route('admin.procurement.requisitions.show', $existingPr->id)
                    ->with('info', "A Purchase Requisition ({$existingPr->requisition_number}) already exists for this calculation sheet.");
            }
        }

        $summary = $this->service->summary($rodCalculation);

        $requisition = DB::transaction(function () use ($rodCalculation, $summary) {
            $number = 'PR-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $pr = PurchaseRequisition::create([
                'project_id'         => $rodCalculation->project_id,
                'requested_by'       => Auth::id(),
                'requisition_number' => $number,
                'status'             => 'draft',
                'required_date'      => now()->addDays(7),
            ]);

            // 1. Add Steel Rebar Items (gross_total_kg)
            foreach ($summary['by_diameter'] as $d) {
                if ($d['total_kg'] <= 0) continue;

                $diaStr = (string) $d['diameter'];
                $matName = "Deformed Steel Rebar Ø{$diaStr}mm";
                $sku = "REBAR-" . str_pad($diaStr, 2, '0', STR_PAD_LEFT);

                $material = Material::firstOrCreate(
                    ['name' => $matName],
                    ['sku' => $sku, 'unit' => 'KG', 'description' => "Steel rebar Ø{$diaStr}mm (Grade 60)"]
                );

                $grossKg = round($d['total_kg'] * (1 + ($summary['waste_percent'] / 100)), 2);

                PurchaseRequisitionItem::create([
                    'purchase_requisition_id' => $pr->id,
                    'material_id'             => $material->id,
                    'quantity'                => $grossKg,
                    'estimated_unit_price'    => $summary['steel_price_per_kg'] > 0 ? $summary['steel_price_per_kg'] : null,
                ]);
            }

            // 2. Add Concrete Component Items
            if (($summary['gross_cement_bags'] ?? 0) > 0) {
                $cementMat = Material::firstOrCreate(
                    ['name' => 'Portland Cement (50kg Bag)'],
                    ['sku' => 'MAT-CEMENT-50KG', 'unit' => 'Bag', 'description' => 'Standard Portland Composite Cement 50kg bag']
                );
                PurchaseRequisitionItem::create([
                    'purchase_requisition_id' => $pr->id,
                    'material_id'             => $cementMat->id,
                    'quantity'                => $summary['gross_cement_bags'],
                    'estimated_unit_price'    => $summary['cement_price_per_bag'] > 0 ? $summary['cement_price_per_bag'] : null,
                ]);
            }

            if (($summary['gross_sand_cft'] ?? 0) > 0) {
                $sandMat = Material::firstOrCreate(
                    ['name' => 'Construction Sand (FM 1.5 - 2.5)'],
                    ['sku' => 'MAT-SAND-CFT', 'unit' => 'CFT', 'description' => 'Coarse river sand for concrete work']
                );
                PurchaseRequisitionItem::create([
                    'purchase_requisition_id' => $pr->id,
                    'material_id'             => $sandMat->id,
                    'quantity'                => $summary['gross_sand_cft'],
                    'estimated_unit_price'    => $summary['sand_price_per_cft'] > 0 ? $summary['sand_price_per_cft'] : null,
                ]);
            }

            if (($summary['gross_aggregate_cft'] ?? 0) > 0) {
                $aggMat = Material::firstOrCreate(
                    ['name' => 'Stone Aggregate (3/4" Down)'],
                    ['sku' => 'MAT-AGG-CFT', 'unit' => 'CFT', 'description' => 'Crushed stone chips aggregate']
                );
                PurchaseRequisitionItem::create([
                    'purchase_requisition_id' => $pr->id,
                    'material_id'             => $aggMat->id,
                    'quantity'                => $summary['gross_aggregate_cft'],
                    'estimated_unit_price'    => $summary['aggregate_price_per_cft'] > 0 ? $summary['aggregate_price_per_cft'] : null,
                ]);
            }

            $rodCalculation->update(['purchase_requisition_id' => $pr->id]);

            return $pr;
        });

        return redirect()->route('admin.procurement.requisitions.show', $requisition->id)
            ->with('success', "Purchase Requisition {$requisition->requisition_number} created from Sheet {$rodCalculation->reference_no}.");
    }
}
