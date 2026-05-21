<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('trade_category')) {
            $query->where('trade_category', $request->trade_category);
        }

        $vendors = $query->latest()->paginate(15);

        return view('admin.procurement.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.procurement.vendors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'trade_category' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,pending,approved,rejected,blacklisted',
        ]);

        $validated['is_blacklisted'] = ($validated['status'] ?? '') === 'blacklisted';

        Vendor::create($validated);

        return redirect()->route('admin.procurement.vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor)
    {
        return view('admin.procurement.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.procurement.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'trade_category' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,pending,approved,rejected,blacklisted',
            'performance_rating' => 'nullable|integer|min:1|max:5',
        ]);

        $validated['is_blacklisted'] = ($validated['status'] ?? '') === 'blacklisted';

        $vendor->update($validated);

        return redirect()->route('admin.procurement.vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('admin.procurement.vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }
}
