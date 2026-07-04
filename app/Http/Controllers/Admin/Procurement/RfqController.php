<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Rfq;
use App\Models\RfqItem;
use App\Models\RfqVendor;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RfqController extends Controller
{
    public function index(Request $request)
    {
        $query = Rfq::with('project', 'creator', 'vendors.vendor');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $rfqs = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.procurement.rfqs.index', compact('rfqs', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $materials = Material::all();
        $vendors = Vendor::whereIn('status', ['active', 'approved'])->get();
        return view('admin.procurement.rfqs.create', compact('projects', 'materials', 'vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'issue_date' => 'required|date',
            'closing_date' => 'required|date|after_or_equal:issue_date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'vendor_ids' => 'required|array|min:1',
            'vendor_ids.*' => 'exists:vendors,id',
        ]);

        $rfq = DB::transaction(function () use ($validated) {
            $number = 'RFQ-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $rfq = Rfq::create([
                'project_id' => $validated['project_id'],
                'rfq_number' => $number,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'issue_date' => $validated['issue_date'],
                'closing_date' => $validated['closing_date'],
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            foreach ($validated['items'] as $item) {
                $material = Material::find($item['material_id']);
                RfqItem::create([
                    'rfq_id' => $rfq->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $material->unit ?? 'pcs',
                ]);
            }

            foreach ($validated['vendor_ids'] as $vendorId) {
                RfqVendor::create([
                    'rfq_id' => $rfq->id,
                    'vendor_id' => $vendorId,
                    'status' => 'invited',
                ]);
            }

            return $rfq;
        });

        return redirect()->route('admin.procurement.rfqs.show', $rfq)
            ->with('success', "RFQ {$rfq->rfq_number} created successfully.");
    }

    public function show(Rfq $rfq)
    {
        $rfq->load('project', 'creator', 'items.material', 'vendors.vendor', 'quotations.vendor', 'quotations.items.rfqItem.material');
        return view('admin.procurement.rfqs.show', compact('rfq'));
    }

    public function edit(Rfq $rfq)
    {
        if ($rfq->status !== 'draft') {
            return redirect()->route('admin.procurement.rfqs.show', $rfq)
                ->with('error', 'Only draft RFQs can be edited.');
        }

        $rfq->load('items', 'vendors');
        $projects = Project::all();
        $materials = Material::all();
        $vendors = Vendor::whereIn('status', ['active', 'approved', 'pending'])->get();
        return view('admin.procurement.rfqs.edit', compact('rfq', 'projects', 'materials', 'vendors'));
    }

    public function update(Request $request, Rfq $rfq)
    {
        if ($rfq->status !== 'draft') {
            return redirect()->route('admin.procurement.rfqs.show', $rfq)
                ->with('error', 'Only draft RFQs can be edited.');
        }

        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'issue_date' => 'required|date',
            'closing_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,sent',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'vendor_ids' => 'required|array|min:1',
            'vendor_ids.*' => 'exists:vendors,id',
        ]);

        DB::transaction(function () use ($validated, $rfq) {
            $rfq->update([
                'project_id' => $validated['project_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'issue_date' => $validated['issue_date'],
                'closing_date' => $validated['closing_date'],
                'status' => $validated['status'],
            ]);

            $rfq->items()->delete();
            foreach ($validated['items'] as $item) {
                $material = Material::find($item['material_id']);
                RfqItem::create([
                    'rfq_id' => $rfq->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $material->unit ?? 'pcs',
                ]);
            }

            $rfq->vendors()->delete();
            foreach ($validated['vendor_ids'] as $vendorId) {
                RfqVendor::create([
                    'rfq_id' => $rfq->id,
                    'vendor_id' => $vendorId,
                ]);
            }
        });

        return redirect()->route('admin.procurement.rfqs.show', $rfq)
            ->with('success', 'RFQ updated successfully.');
    }

    public function destroy(Rfq $rfq)
    {
        if (!in_array($rfq->status, ['draft', 'closed'])) {
            return redirect()->route('admin.procurement.rfqs.show', $rfq)
                ->with('error', 'Only draft or closed RFQs can be deleted.');
        }

        $rfq->delete();
        return redirect()->route('admin.procurement.rfqs.index')
            ->with('success', 'RFQ deleted successfully.');
    }

    public function send(Rfq $rfq)
    {
        if ($rfq->status !== 'draft') {
            return back()->with('error', 'RFQ is not in draft status.');
        }

        $rfq->update(['status' => 'sent']);
        return back()->with('success', "RFQ {$rfq->rfq_number} sent to vendors.");
    }

    public function close(Rfq $rfq)
    {
        if (!in_array($rfq->status, ['sent', 'awarded'])) {
            return back()->with('error', 'RFQ must be sent or awarded to close.');
        }

        $rfq->update(['status' => 'closed']);
        return back()->with('success', "RFQ {$rfq->rfq_number} closed.");
    }

    public function createQuotation(Request $request, Rfq $rfq)
    {
        $vendor = Vendor::findOrFail($request->vendor_id);

        if (!$rfq->vendors()->where('vendor_id', $vendor->id)->exists()) {
            return back()->with('error', 'Vendor is not invited to this RFQ.');
        }

        if ($rfq->quotations()->where('vendor_id', $vendor->id)->exists()) {
            return redirect()->route('admin.procurement.rfqs.editQuotation', [$rfq, 'vendor_id' => $vendor->id])
                ->with('info', 'Quotation already exists for this vendor. Edit it instead.');
        }

        $rfq->load('items.material');
        return view('admin.procurement.rfqs.quotations.create', compact('rfq', 'vendor'));
    }

    public function storeQuotation(Request $request, Rfq $rfq)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'quotation_number' => 'nullable|string|max:255',
            'submitted_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.rfq_item_id' => 'required|exists:rfq_items,id',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $rfq) {
            $quotation = Quotation::create([
                'rfq_id' => $rfq->id,
                'vendor_id' => $validated['vendor_id'],
                'quotation_number' => $validated['quotation_number'],
                'submitted_date' => $validated['submitted_date'],
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $item) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'rfq_item_id' => $item['rfq_item_id'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            $rfq->vendors()->where('vendor_id', $validated['vendor_id'])->update(['status' => 'submitted']);
        });

        return redirect()->route('admin.procurement.rfqs.show', $rfq)
            ->with('success', 'Quotation added successfully.');
    }

    public function editQuotation(Rfq $rfq, Quotation $quotation)
    {
        $quotation->load('items');
        $rfq->load('items.material');
        return view('admin.procurement.rfqs.quotations.edit', compact('rfq', 'quotation'));
    }

    public function updateQuotation(Request $request, Rfq $rfq, Quotation $quotation)
    {
        $validated = $request->validate([
            'quotation_number' => 'nullable|string|max:255',
            'submitted_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.rfq_item_id' => 'required|exists:rfq_items,id',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $quotation) {
            $quotation->update([
                'quotation_number' => $validated['quotation_number'],
                'submitted_date' => $validated['submitted_date'],
                'notes' => $validated['notes'],
            ]);

            $quotation->items()->delete();
            foreach ($validated['items'] as $item) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'rfq_item_id' => $item['rfq_item_id'],
                    'unit_price' => $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('admin.procurement.rfqs.show', $rfq)
            ->with('success', 'Quotation updated successfully.');
    }

    public function award(Request $request, Rfq $rfq)
    {
        $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
        ]);

        $quotation = Quotation::with('items', 'vendor')->findOrFail($request->quotation_id);

        if ($quotation->rfq_id !== $rfq->id) {
            return back()->with('error', 'Invalid quotation.');
        }

        if ($rfq->status === 'awarded') {
            return back()->with('error', 'RFQ already awarded.');
        }

        DB::transaction(function () use ($rfq, $quotation) {
            $rfq->quotations()->update(['is_winner' => false]);
            $quotation->update(['is_winner' => true]);
            $rfq->update(['status' => 'awarded']);

            $total = $quotation->items->sum(fn ($i) => $i->unit_price * $i->rfqItem->quantity);

            $number = 'PO-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $po = PurchaseOrder::create([
                'vendor_id' => $quotation->vendor_id,
                'project_id' => $quotation->rfq->project_id,
                'po_number' => $number,
                'status' => 'draft',
                'total_amount' => $total,
                'order_date' => now(),
            ]);

            foreach ($quotation->items as $qi) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'material_id' => $qi->rfqItem->material_id,
                    'quantity' => $qi->rfqItem->quantity,
                    'unit_price' => $qi->unit_price,
                ]);
            }
        });

        return redirect()->route('admin.procurement.rfqs.show', $rfq)
            ->with('success', "Quotation from {$quotation->vendor->name} awarded. Purchase Order created.");
    }
}
