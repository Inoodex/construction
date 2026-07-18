<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Constants\BarDirection;
use App\Constants\RodCalculationConstants;
use App\Constants\RodMemberType;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\RodCalculation;
use App\Models\RodMember;
use App\Models\RodMemberBar;
use App\Services\RodCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RodCalculationController extends Controller
{
    protected RodCalculationService $service;

    public function __construct(RodCalculationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = RodCalculation::with('project', 'creator');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rodCalculations = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.finance.rod-calculations.index', compact('rodCalculations', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.finance.rod-calculations.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'   => 'required|exists:projects,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'steel_grade'  => 'nullable|in:' . implode(',', RodCalculationConstants::STEEL_GRADES),
            'revision'     => 'nullable|max:50',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        $validated['reference_no'] = $this->service->generateReferenceNo($project);
        $validated['status'] = RodCalculationConstants::STATUS_DRAFT;
        $validated['formula_version'] = RodCalculationConstants::FORMULA_VERSION;
        $validated['created_by'] = Auth::id();

        $calc = RodCalculation::create($validated);

        return redirect()->route('admin.finance.rod-calculations.show', $calc->id)
            ->with('success', 'Rod Calculation created. Add members now.');
    }

    public function show(RodCalculation $rodCalculation)
    {
        $rodCalculation->load('project', 'creator', 'approver', 'members.bars');
        $summary = $this->service->summary($rodCalculation);

        return view('admin.finance.rod-calculations.show', compact('rodCalculation', 'summary'));
    }

    public function edit(RodCalculation $rodCalculation)
    {
        $rodCalculation->load('project');
        $projects = Project::all();

        return view('admin.finance.rod-calculations.edit', compact('rodCalculation', 'projects'));
    }

    public function update(Request $request, RodCalculation $rodCalculation)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'steel_grade'  => 'nullable|in:' . implode(',', RodCalculationConstants::STEEL_GRADES),
            'revision'     => 'nullable|max:50',
        ]);

        $rodCalculation->update($validated);

        return redirect()->route('admin.finance.rod-calculations.show', $rodCalculation->id)
            ->with('success', 'Rod Calculation updated successfully.');
    }

    public function destroy(RodCalculation $rodCalculation)
    {
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
        $validated = $request->validate([
            'type'         => 'required|in:' . implode(',', RodMemberType::ALL),
            'member_code'  => 'required|string|max:100',
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
        $validated = $request->validate([
            'type'         => 'required|in:' . implode(',', RodMemberType::ALL),
            'member_code'  => 'required|string|max:100',
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
        $rodMember->bars()->delete();
        $rodMember->delete();

        return back()->with('success', 'Member deleted.');
    }

    // ─── Bar CRUD ───────────────────────────────────────────────

    public function storeBar(Request $request, RodCalculation $rodCalculation, RodMember $rodMember)
    {
        $validated = $request->validate([
            'bar_name'       => 'required|string|max:100',
            'direction'      => 'required|in:' . implode(',', BarDirection::ALL),
            'diameter'       => 'required|numeric|in:8,10,12,16,20,25,32',
            'actual_size'    => 'required|numeric|min:1',
            'spacing'        => 'nullable|numeric|min:1',
            'hook_length'    => 'nullable|numeric|min:0',
            'bend_length'    => 'nullable|numeric|min:0',
            'lap_length'     => 'nullable|numeric|min:0',
            'bars_count'     => 'nullable|integer|min:1',
            'is_manual_count'=> 'nullable|boolean',
            'sort_order'     => 'nullable|integer',
            'remarks'        => 'nullable|string',
        ]);

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
        $validated = $request->validate([
            'bar_name'       => 'required|string|max:100',
            'direction'      => 'required|in:' . implode(',', BarDirection::ALL),
            'diameter'       => 'required|numeric|in:8,10,12,16,20,25,32',
            'actual_size'    => 'required|numeric|min:1',
            'spacing'        => 'nullable|numeric|min:1',
            'hook_length'    => 'nullable|numeric|min:0',
            'bend_length'    => 'nullable|numeric|min:0',
            'lap_length'     => 'nullable|numeric|min:0',
            'bars_count'     => 'nullable|integer|min:1',
            'is_manual_count'=> 'nullable|boolean',
            'sort_order'     => 'nullable|integer',
            'remarks'        => 'nullable|string',
        ]);

        $validated['is_manual_count'] = $validated['is_manual_count'] ?? false;
        $validated['hook_length'] = $validated['hook_length'] ?? 0;
        $validated['bend_length'] = $validated['bend_length'] ?? 0;
        $validated['lap_length'] = $validated['lap_length'] ?? 0;

        $rodBar->update($validated);
        $this->service->recalculateBar($rodBar->fresh('member'));
        $rodBar->save();

        return back()->with('success', "Bar '{$rodBar->bar_name}' updated.");
    }

    public function destroyBar(RodCalculation $rodCalculation, RodMember $rodMember, RodMemberBar $rodBar)
    {
        $rodBar->delete();

        return back()->with('success', 'Bar deleted.');
    }

    // ─── Status Actions ─────────────────────────────────────────

    public function approve(RodCalculation $rodCalculation)
    {
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
        $rodCalculation->update(['status' => RodCalculationConstants::STATUS_COMPLETED]);

        return back()->with('success', 'Rod Calculation marked as completed.');
    }

    public function reopen(RodCalculation $rodCalculation)
    {
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

    // ─── PDF Export ─────────────────────────────────────────────

    public function pdf(RodCalculation $rodCalculation)
    {
        $rodCalculation->load('project', 'creator', 'approver', 'members.bars');
        $summary = $this->service->summary($rodCalculation);

        $pdf = Pdf::loadView('admin.finance.rod-calculations.pdf.bbs', compact('rodCalculation', 'summary'));

        return $pdf->stream("BBS-{$rodCalculation->reference_no}.pdf");
    }
}
