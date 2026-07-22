<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Constants\ConcreteRatioConstants;
use App\Constants\RodMemberType;
use App\Http\Controllers\Controller;
use App\Models\ConcreteRatio;
use App\Models\ConcreteRatioMember;
use App\Models\Project;
use App\Models\RodCalculation;
use App\Services\ConcreteRatioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class ConcreteRatioController extends Controller
{
    protected ConcreteRatioService $service;

    public function __construct(ConcreteRatioService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = ConcreteRatio::with('project', 'creator', 'members')->withCount('members');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $concreteRatios = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.finance.concrete-ratios.index', compact('concreteRatios', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.finance.concrete-ratios.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'grade'      => 'nullable|in:' . implode(',', ConcreteRatioConstants::GRADES),
            'waste_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        $validated['reference_no'] = $this->service->generateReferenceNo($project);
        $validated['status'] = ConcreteRatioConstants::STATUS_DRAFT;
        $validated['created_by'] = Auth::id();

        $ratio = ConcreteRatio::create($validated);

        return redirect()->route('admin.finance.concrete-ratios.show', $ratio->id)
            ->with('success', 'Concrete Ratio created. Add members now.');
    }

    public function show(ConcreteRatio $concreteRatio)
    {
        $concreteRatio->load('project', 'creator', 'approver', 'rodCalculation', 'members');
        $summary = $this->service->summary($concreteRatio);

        return view('admin.finance.concrete-ratios.show', compact('concreteRatio', 'summary'));
    }

    public function edit(ConcreteRatio $concreteRatio)
    {
        $concreteRatio->load('project');
        $projects = Project::all();

        return view('admin.finance.concrete-ratios.edit', compact('concreteRatio', 'projects'));
    }

    public function update(Request $request, ConcreteRatio $concreteRatio)
    {
        abort_if(!$concreteRatio->isDraft(), 403, 'Only draft ratios can be edited.');

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'grade'        => 'nullable|in:' . implode(',', ConcreteRatioConstants::GRADES),
            'waste_percent'=> 'nullable|numeric|min:0|max:100',
        ]);

        $concreteRatio->update($validated);

        return redirect()->route('admin.finance.concrete-ratios.show', $concreteRatio->id)
            ->with('success', 'Concrete Ratio updated successfully.');
    }

    public function destroy(ConcreteRatio $concreteRatio)
    {
        abort_if(!$concreteRatio->isDraft(), 403, 'Only draft ratios can be deleted.');

        $concreteRatio->members()->delete();
        $concreteRatio->delete();

        return redirect()->route('admin.finance.concrete-ratios.index')
            ->with('success', 'Concrete Ratio deleted successfully.');
    }

    // ─── Copy from BBS ─────────────────────────────────────────

    public function copyFromBbs(Request $request)
    {
        $request->validate([
            'rod_calculation_id' => 'required|exists:rod_calculations,id',
        ]);

        $rodCalc = RodCalculation::with('members')->findOrFail($request->rod_calculation_id);

        $members = $rodCalc->members->map(fn($m) => [
            'rod_member_id' => $m->id,
            'type'          => $m->type,
            'member_code'   => $m->member_code,
            'quantity'      => $m->quantity,
            'length'        => $m->length,
            'width'         => $m->width,
            'height'        => $m->height,
            'depth'         => $m->depth,
            'thickness'     => $m->thickness,
            'cover'         => $m->cover,
        ]);

        return response()->json($members);
    }

    public function bbsByProject(Project $project)
    {
        $rodCalculations = RodCalculation::where('project_id', $project->id)
            ->withCount('members')
            ->get()
            ->map(fn($r) => [
                'id'            => $r->id,
                'reference_no'  => $r->reference_no,
                'title'         => $r->title,
                'description'   => $r->description,
                'members_count' => $r->members_count,
            ]);

        return response()->json($rodCalculations);
    }

    public function storeMember(Request $request, ConcreteRatio $concreteRatio)
    {
        abort_if(!$concreteRatio->isDraft(), 403, 'Only draft ratios can be modified.');

        $validated = $request->validate([
            'type'           => 'required|string|max:30',
            'member_code'    => ['required', 'string', 'max:100', Rule::unique('concrete_ratio_members', 'member_code')->where('concrete_ratio_id', $concreteRatio->id)],
            'quantity'       => 'required|integer|min:1',
            'length'         => 'nullable|numeric|min:0',
            'width'          => 'nullable|numeric|min:0',
            'height'         => 'nullable|numeric|min:0',
            'depth'          => 'nullable|numeric|min:0',
            'thickness'      => 'nullable|numeric|min:0',
            'cement_bags'    => 'nullable|numeric|min:0',
            'sand_m3'        => 'nullable|numeric|min:0',
            'aggregate_m3'   => 'nullable|numeric|min:0',
            'water_liters'   => 'nullable|numeric|min:0',
            'sort_order'     => 'nullable|integer',
            'remarks'        => 'nullable|string',
        ]);

        $validated['concrete_ratio_id'] = $concreteRatio->id;
        $validated['sort_order'] = $validated['sort_order'] ?? $concreteRatio->members()->count();

        $member = ConcreteRatioMember::create($validated);

        if (empty($validated['cement_bags']) && empty($validated['sand_m3']) && empty($validated['aggregate_m3']) && empty($validated['water_liters'])) {
            $this->service->recalculateMember($member);
        }

        return back()->with('success', "Member '{$member->member_code}' added.");
    }

    public function updateMember(Request $request, ConcreteRatio $concreteRatio, ConcreteRatioMember $concreteMember)
    {
        abort_if(!$concreteRatio->isDraft(), 403, 'Only draft ratios can be modified.');

        $validated = $request->validate([
            'type'           => 'required|string|max:30',
            'member_code'    => ['required', 'string', 'max:100', Rule::unique('concrete_ratio_members', 'member_code')->where('concrete_ratio_id', $concreteRatio->id)->ignore($concreteMember->id)],
            'quantity'       => 'required|integer|min:1',
            'length'         => 'nullable|numeric|min:0',
            'width'          => 'nullable|numeric|min:0',
            'height'         => 'nullable|numeric|min:0',
            'depth'          => 'nullable|numeric|min:0',
            'thickness'      => 'nullable|numeric|min:0',
            'cement_bags'    => 'nullable|numeric|min:0',
            'sand_m3'        => 'nullable|numeric|min:0',
            'aggregate_m3'   => 'nullable|numeric|min:0',
            'water_liters'   => 'nullable|numeric|min:0',
            'sort_order'     => 'nullable|integer',
            'remarks'        => 'nullable|string',
        ]);

        $concreteMember->update($validated);

        if (empty($validated['cement_bags']) && empty($validated['sand_m3']) && empty($validated['aggregate_m3']) && empty($validated['water_liters'])) {
            $this->service->recalculateMember($concreteMember->fresh());
        }

        return back()->with('success', "Member '{$concreteMember->member_code}' updated.");
    }

    public function destroyMember(ConcreteRatio $concreteRatio, ConcreteRatioMember $concreteMember)
    {
        abort_if(!$concreteRatio->isDraft(), 403, 'Only draft ratios can be modified.');

        $concreteMember->delete();

        return back()->with('success', 'Member deleted.');
    }

    // ─── Copy Members from BBS ──────────────────────────────────

    public function copyMembers(Request $request, ConcreteRatio $concreteRatio)
    {
        abort_if(!$concreteRatio->isDraft(), 403, 'Only draft ratios can be modified.');

        $request->validate([
            'rod_calculation_id' => 'required|exists:rod_calculations,id',
        ]);

        $rodCalc = RodCalculation::with('members')->findOrFail($request->rod_calculation_id);

        $concreteRatio->update(['rod_calculation_id' => $rodCalc->id]);

        foreach ($rodCalc->members as $rodMember) {
            $member = ConcreteRatioMember::create([
                'concrete_ratio_id' => $concreteRatio->id,
                'rod_member_id'     => $rodMember->id,
                'type'              => $rodMember->type,
                'member_code'       => $rodMember->member_code,
                'quantity'          => $rodMember->quantity,
                'length'            => $rodMember->length,
                'width'             => $rodMember->width,
                'height'            => $rodMember->height,
                'depth'             => $rodMember->depth,
                'thickness'         => $rodMember->thickness,
                'sort_order'        => $rodMember->sort_order,
            ]);
            $this->service->recalculateMember($member);
        }

        return back()->with('success', $rodCalc->members->count() . ' member(s) copied from BBS.');
    }

    // ─── Status Actions ─────────────────────────────────────────

    public function approve(ConcreteRatio $concreteRatio)
    {
        abort_if(!$concreteRatio->isDraft(), 403, 'Only draft ratios can be approved.');

        if ($concreteRatio->members()->count() === 0) {
            return back()->with('error', 'Cannot approve: add at least one member.');
        }

        $concreteRatio->update([
            'status'      => ConcreteRatioConstants::STATUS_APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Concrete Ratio approved.');
    }

    public function complete(ConcreteRatio $concreteRatio)
    {
        abort_if(!$concreteRatio->isApproved(), 403, 'Only approved ratios can be completed.');

        $concreteRatio->update(['status' => ConcreteRatioConstants::STATUS_COMPLETED]);

        return back()->with('success', 'Concrete Ratio marked as completed.');
    }

    public function reopen(ConcreteRatio $concreteRatio)
    {
        abort_if(!$concreteRatio->isApproved() && !$concreteRatio->isCompleted(), 403, 'Nothing to reopen.');

        $concreteRatio->update([
            'status'      => ConcreteRatioConstants::STATUS_DRAFT,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('success', 'Concrete Ratio reopened to draft.');
    }

    public function recalculate(ConcreteRatio $concreteRatio)
    {
        $this->service->recalculateAll($concreteRatio);

        return back()->with('success', 'All calculations refreshed.');
    }

    // ─── PDF Export ─────────────────────────────────────────────

    public function pdf(ConcreteRatio $concreteRatio)
    {
        $concreteRatio->load('project', 'creator', 'approver', 'rodCalculation', 'members');
        $summary = $this->service->summary($concreteRatio);

        $pdf = Pdf::loadView('admin.finance.concrete-ratios.pdf.concrete', compact('concreteRatio', 'summary'));

        return $pdf->stream("CR-{$concreteRatio->reference_no}.pdf");
    }
}
