<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Project;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PurchaseRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseRequisition::with('project', 'requester', 'items.material');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $requisitions = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.procurement.requisitions.index', compact('requisitions', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $materials = Material::all();
        return view('admin.procurement.requisitions.create', compact('projects', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'required_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.estimated_unit_price' => 'nullable|numeric|min:0',
        ]);

        $requisition = DB::transaction(function () use ($validated) {
            $number = 'PR-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $requisition = PurchaseRequisition::create([
                'project_id' => $validated['project_id'],
                'requested_by' => Auth::id(),
                'requisition_number' => $number,
                'status' => 'draft',
                'required_date' => $validated['required_date'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                PurchaseRequisitionItem::create([
                    'purchase_requisition_id' => $requisition->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'estimated_unit_price' => $item['estimated_unit_price'] ?? null,
                ]);
            }

            return $requisition;
        });

        return redirect()->route('admin.procurement.requisitions.index')
            ->with('success', "Requisition {$requisition->requisition_number} created successfully.");
    }

    public function show(PurchaseRequisition $requisition)
    {
        $requisition->load('project', 'requester', 'items.material', 'approvals.history.approver');
        return view('admin.procurement.requisitions.show', compact('requisition'));
    }

    public function edit(PurchaseRequisition $requisition)
    {
        $requisition->load('items');
        $projects = Project::all();
        $materials = Material::all();
        return view('admin.procurement.requisitions.edit', compact('requisition', 'projects', 'materials'));
    }

    public function update(Request $request, PurchaseRequisition $requisition)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'required_date' => 'nullable|date',
            'status' => 'required|in:draft,submitted,approved,rejected',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.estimated_unit_price' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $requisition) {
            $requisition->update([
                'project_id' => $validated['project_id'],
                'status' => $validated['status'],
                'required_date' => $validated['required_date'] ?? null,
            ]);

            $requisition->items()->delete();

            foreach ($validated['items'] as $item) {
                PurchaseRequisitionItem::create([
                    'purchase_requisition_id' => $requisition->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'estimated_unit_price' => $item['estimated_unit_price'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.procurement.requisitions.index')
            ->with('success', 'Requisition updated successfully.');
    }

    public function submitForApproval(PurchaseRequisition $requisition)
    {
        if ($requisition->status !== 'draft') {
            return back()->with('error', 'Only draft requisitions can be submitted for approval.');
        }

        $totalAmount = $requisition->items->sum(fn($i) => ($i->quantity * ($i->estimated_unit_price ?? 0)));

        $approvalService = app(ApprovalService::class);
        $approval = $approvalService->submitForApproval($requisition, 'purchase_requisition', $totalAmount, Auth::id());

        $requisition->update(['status' => $approval ? 'submitted' : 'approved']);

        if (!$approval) {
            return redirect()->route('admin.procurement.requisitions.index')
                ->with('success', 'No approval workflow configured. Requisition auto-approved.');
        }

        return back()->with('success', 'Requisition submitted for approval.');
    }

    public function destroy(PurchaseRequisition $requisition)
    {
        $requisition->delete();
        return redirect()->route('admin.procurement.requisitions.index')
            ->with('success', 'Requisition deleted successfully.');
    }

    public function getItems(PurchaseRequisition $requisition)
    {
        $items = $requisition->items()->with('material')->get()->map(fn($i) => [
            'material_id' => $i->material_id,
            'material_name' => $i->material?->name,
            'quantity' => $i->quantity,
            'unit_price' => $i->estimated_unit_price,
        ]);
        return response()->json($items);
    }

    public function printPdf(PurchaseRequisition $requisition)
    {
        $requisition->load('project', 'requester', 'items.material');
        $pdf = Pdf::loadView('admin.procurement.requisitions.pdf.requisition', compact('requisition'));
        return $pdf->stream();
    }
}
