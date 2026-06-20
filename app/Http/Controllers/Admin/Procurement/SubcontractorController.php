<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcontractor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubcontractorController extends Controller
{
    public function index(Request $request)
    {
        $query = Subcontractor::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('specialization', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('trade_category')) {
            $query->where('trade_category', $request->trade_category);
        }

        $tradeCategories = Category::tradeCategories()->pluck('value')->toArray();
        $subcontractors = $query->latest()->paginate(15);

        return view('admin.procurement.subcontractors.index', compact('subcontractors'));
    }

    public function create()
    {
        return view('admin.procurement.subcontractors.create');
    }

    public function store(Request $request)
    {
        $tradeCategories = Category::tradeCategories()->pluck('value')->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'trade_category' => ['nullable', 'string', 'max:255', Rule::in($tradeCategories)],
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,pending,approved,rejected,suspended',
        ]);

        Subcontractor::create($validated);

        return redirect()->route('admin.procurement.subcontractors.index')
            ->with('success', 'Subcontractor created successfully.');
    }

    public function show(Subcontractor $subcontractor)
    {
        return view('admin.procurement.subcontractors.show', compact('subcontractor'));
    }

    public function edit(Subcontractor $subcontractor)
    {
        return view('admin.procurement.subcontractors.edit', compact('subcontractor'));
    }

    public function update(Request $request, Subcontractor $subcontractor)
    {
        $tradeCategories = Category::tradeCategories()->pluck('value')->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'trade_category' => ['nullable', 'string', 'max:255', Rule::in($tradeCategories)],
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,pending,approved,rejected,suspended',
            'performance_rating' => 'nullable|integer|min:1|max:5',
        ]);

        $subcontractor->update($validated);

        return redirect()->route('admin.procurement.subcontractors.index')
            ->with('success', 'Subcontractor updated successfully.');
    }

    public function destroy(Subcontractor $subcontractor)
    {
        $subcontractor->delete();
        return redirect()->route('admin.procurement.subcontractors.index')
            ->with('success', 'Subcontractor deleted successfully.');
    }
}
