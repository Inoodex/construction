<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequisition;
use App\Models\Vendor;
use App\Services\ApprovalService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('vendor', 'requisition');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $orders = $query->latest()->paginate(15);
        $vendors = Vendor::all();

        return view('admin.procurement.purchase-orders.index', compact('orders', 'vendors'));
    }

    public function create()
    {
        $requisitions = PurchaseRequisition::where('status', 'approved')->get();
        $vendors = Vendor::whereIn('status', ['active', 'approved'])->get();
        $materials = Material::all();
        return view('admin.procurement.purchase-orders.create', compact('requisitions', 'vendors', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_requisition_id' => 'nullable|exists:purchase_requisitions,id',
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $total = collect($validated['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']);

            $number = 'PO-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $order = PurchaseOrder::create([
                'purchase_requisition_id' => $validated['purchase_requisition_id'] ?? null,
                'vendor_id' => $validated['vendor_id'],
                'po_number' => $number,
                'status' => 'draft',
                'total_amount' => $total,
                'order_date' => $validated['order_date'],
            ]);

            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            return $order;
        });

        return redirect()->route('admin.procurement.purchase-orders.index')
            ->with('success', "PO {$order->po_number} created successfully.");
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('vendor', 'requisition', 'items.material', 'approvals.history.approver');
        return view('admin.procurement.purchase-orders.show', compact('purchaseOrder'));
    }

    public function printPdf(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('vendor', 'items.material');
        $po = $purchaseOrder;
        $pdf = Pdf::loadView('admin.procurement.purchase-orders.pdf.invoice', compact('po'))
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);
        return $pdf->stream('PO-' . $po->po_number . '.pdf');
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items');
        $requisitions = PurchaseRequisition::where('status', 'approved')->get();
        $vendors = Vendor::whereIn('status', ['active', 'approved'])->get();
        $materials = Material::all();
        return view('admin.procurement.purchase-orders.edit', compact('purchaseOrder', 'requisitions', 'vendors', 'materials'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'purchase_requisition_id' => 'nullable|exists:purchase_requisitions,id',
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'status' => 'required|in:draft,submitted,ordered,partially_received,received,cancelled',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $purchaseOrder) {
            $total = collect($validated['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']);

            $purchaseOrder->update([
                'purchase_requisition_id' => $validated['purchase_requisition_id'] ?? null,
                'vendor_id' => $validated['vendor_id'],
                'status' => $validated['status'],
                'total_amount' => $total,
                'order_date' => $validated['order_date'],
            ]);

            $purchaseOrder->items()->delete();

            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('admin.procurement.purchase-orders.index')
            ->with('success', 'PO updated successfully.');
    }

    public function submitForApproval(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return back()->with('error', 'Only draft purchase orders can be submitted for approval.');
        }

        $approvalService = app(ApprovalService::class);
        $approval = $approvalService->submitForApproval($purchaseOrder, 'purchase_order', $purchaseOrder->total_amount, Auth::id());

        if ($approval) {
            $purchaseOrder->update(['status' => 'submitted']);
        } else {
            $purchaseOrder->update(['status' => 'ordered']);
        }

        if (!$approval) {
            return redirect()->route('admin.procurement.purchase-orders.index')
                ->with('success', 'No approval workflow configured. Purchase order auto-approved.');
        }

        return back()->with('success', 'Purchase order submitted for approval.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('admin.procurement.purchase-orders.index')
            ->with('success', 'PO deleted successfully.');
    }
}
