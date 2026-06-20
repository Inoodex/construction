<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\VendorDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        if ($request->filled('qualification_status')) {
            $query->where('qualification_status', $request->qualification_status);
        }

        if ($request->filled('trade_category')) {
            $query->where('trade_category', $request->trade_category);
        }

        $tradeCategories = Category::tradeCategories()->pluck('value')->toArray();

        $vendors = $query->latest()->paginate(15);

        return view('admin.procurement.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.procurement.vendors.create');
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
        $vendor->load('documents', 'qualifiedBy');
        return view('admin.procurement.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.procurement.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $tradeCategories = Category::tradeCategories()->pluck('value')->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'trade_category' => ['nullable', 'string', 'max:255', Rule::in($tradeCategories)],
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

    public function uploadDocument(Request $request, Vendor $vendor)
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $filePath = $request->file('file')->store('vendor-documents', 'public');

        $vendor->documents()->create([
            'document_type' => $request->document_type,
            'title' => $request->title,
            'file_path' => $filePath,
            'expiry_date' => $request->expiry_date,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function deleteDocument(VendorDocument $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return back()->with('success', 'Document deleted successfully.');
    }

    public function updateQualification(Request $request, Vendor $vendor)
    {
        $request->validate([
            'qualification_status' => 'required|in:unqualified,under_review,qualified,rejected',
            'qualification_notes' => 'nullable|string|max:1000',
        ]);

        $vendor->qualification_status = $request->qualification_status;

        if ($request->qualification_status === 'qualified') {
            $vendor->qualified_at = now();
            $vendor->qualified_by = auth()->id();
        } elseif ($request->qualification_status === 'unqualified') {
            $vendor->qualified_at = null;
            $vendor->qualified_by = null;
        }

        $vendor->save();

        $statusLabels = [
            'unqualified' => 'Not Applied',
            'under_review' => 'Under Review',
            'qualified' => 'Qualified',
            'rejected' => 'Rejected',
        ];

        return back()->with('success', 'Vendor qualification status updated to "' . ($statusLabels[$request->qualification_status] ?? $request->qualification_status) . '".');
    }
}
