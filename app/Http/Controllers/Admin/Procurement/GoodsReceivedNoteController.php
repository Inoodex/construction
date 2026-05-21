<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceivedNote;
use App\Models\GoodsReceivedNoteItem;
use App\Models\PurchaseOrder;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodsReceivedNoteController extends Controller
{
    public function index(Request $request)
    {
        $query = GoodsReceivedNote::with('purchaseOrder', 'receiver');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notes = $query->latest()->paginate(15);

        return view('admin.procurement.goods-received-notes.index', compact('notes'));
    }

    public function create()
    {
        $orders = PurchaseOrder::whereIn('status', ['ordered', 'partially_received'])->with('vendor', 'items.material')->get();
        return view('admin.procurement.goods-received-notes.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'received_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity_received' => 'required|numeric|min:0',
            'items.*.quantity_accepted' => 'required|numeric|min:0',
            'items.*.quantity_rejected' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $number = 'GRN-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $note = GoodsReceivedNote::create([
                'purchase_order_id' => $validated['purchase_order_id'],
                'grn_number' => $number,
                'received_date' => $validated['received_date'],
                'received_by' => Auth::id(),
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                GoodsReceivedNoteItem::create([
                    'goods_received_note_id' => $note->id,
                    'material_id' => $item['material_id'],
                    'quantity_received' => $item['quantity_received'],
                    'quantity_accepted' => $item['quantity_accepted'],
                    'quantity_rejected' => $item['quantity_rejected'],
                ]);
            }

            $po = PurchaseOrder::find($validated['purchase_order_id']);
            $totalReceived = GoodsReceivedNoteItem::whereHas('goodsReceivedNote', fn($q) => $q->where('purchase_order_id', $po->id))
                ->sum('quantity_accepted');
            $totalOrdered = $po->items->sum('quantity');
            $po->update(['status' => $totalReceived >= $totalOrdered ? 'received' : 'partially_received']);
        });

        return redirect()->route('admin.procurement.goods-received-notes.index')
            ->with('success', 'GRN created successfully.');
    }

    public function show(GoodsReceivedNote $goodsReceivedNote)
    {
        $goodsReceivedNote->load('purchaseOrder.vendor', 'purchaseOrder.items.material', 'receiver', 'items.material');
        return view('admin.procurement.goods-received-notes.show', compact('goodsReceivedNote'));
    }

    public function destroy(GoodsReceivedNote $goodsReceivedNote)
    {
        $goodsReceivedNote->delete();
        return redirect()->route('admin.procurement.goods-received-notes.index')
            ->with('success', 'GRN deleted successfully.');
    }
}
