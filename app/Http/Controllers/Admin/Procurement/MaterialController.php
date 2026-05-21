<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        $materials = $query->latest()->paginate(15);

        return view('admin.procurement.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('admin.procurement.materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:materials,sku',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        Material::create($validated);

        return redirect()->route('admin.procurement.materials.index')
            ->with('success', 'Material created successfully.');
    }

    public function show(Material $material)
    {
        return view('admin.procurement.materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        return view('admin.procurement.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:materials,sku,' . $material->id,
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $material->update($validated);

        return redirect()->route('admin.procurement.materials.index')
            ->with('success', 'Material updated successfully.');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('admin.procurement.materials.index')
            ->with('success', 'Material deleted successfully.');
    }
}
