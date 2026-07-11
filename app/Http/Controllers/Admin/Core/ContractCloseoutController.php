<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractCloseoutItem;
use Illuminate\Http\Request;

class ContractCloseoutController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with('closeoutItems', 'project')
            ->withCount(['closeoutItems', 'closeoutItems as completed_items_count' => function ($q) {
                $q->where('is_completed', true);
            }]);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('contract_number', 'like', '%'.$request->search.'%')
                    ->orWhere('title', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('complete')) {
            if ($request->complete === 'yes') {
                $query->having('completed_items_count', '>', 0)
                    ->whereColumn('closeout_items_count', '=', 'completed_items_count');
            } elseif ($request->complete === 'no') {
                $query->having('completed_items_count', '<', 'closeout_items_count');
            }
        }

        $contracts = $query->latest()->paginate(20);

        return view('admin.core.contract-closeout.index', compact('contracts'));
    }

    public function store(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_index' => 'nullable|integer',
        ]);

        $validated['contract_id'] = $contract->id;
        $validated['order_index'] = $validated['order_index'] ?? $contract->closeoutItems()->count();

        ContractCloseoutItem::create($validated);

        return back()->with('success', 'Closeout item added.');
    }

    public function toggle(ContractCloseoutItem $item)
    {
        $item->update([
            'is_completed' => !$item->is_completed,
            'completed_date' => !$item->is_completed ? now()->toDateString() : null,
            'completed_by' => !$item->is_completed ? auth()->id() : null,
        ]);

        return back()->with('success', $item->is_completed ? 'Item marked complete.' : 'Item reopened.');
    }

    public function destroy(ContractCloseoutItem $item)
    {
        $item->delete();

        return back()->with('success', 'Closeout item deleted.');
    }
}
