<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialWastage;
use App\Models\Project;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialWastageController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialWastage::with('project', 'site', 'material', 'reporter');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        $wastages = $query->latest()->paginate(15);
        $projects = Project::all();
        $materials = Material::all();

        return view('admin.procurement.material-wastages.index', compact('wastages', 'projects', 'materials'));
    }

    public function create()
    {
        $projects = Project::all();
        $sites = Site::all();
        $materials = Material::all();
        return view('admin.procurement.material-wastages.create', compact('projects', 'sites', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'site_id' => 'required|exists:sites,id',
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|numeric|min:0.0001',
            'reason' => 'required|string|max:500',
            'reported_date' => 'required|date',
        ]);

        $validated['reported_by'] = Auth::id();

        MaterialWastage::create($validated);

        return redirect()->route('admin.procurement.material-wastages.index')
            ->with('success', 'Wastage recorded successfully.');
    }

    public function show(MaterialWastage $materialWastage)
    {
        $materialWastage->load('project', 'site', 'material', 'reporter');
        return view('admin.procurement.material-wastages.show', compact('materialWastage'));
    }

    public function edit(MaterialWastage $materialWastage)
    {
        $projects = Project::all();
        $sites = Site::all();
        $materials = Material::all();
        return view('admin.procurement.material-wastages.edit', compact('materialWastage', 'projects', 'sites', 'materials'));
    }

    public function update(Request $request, MaterialWastage $materialWastage)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'site_id' => 'required|exists:sites,id',
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|numeric|min:0.0001',
            'reason' => 'required|string|max:500',
            'reported_date' => 'required|date',
        ]);

        $materialWastage->update($validated);

        return redirect()->route('admin.procurement.material-wastages.index')
            ->with('success', 'Wastage record updated successfully.');
    }

    public function destroy(MaterialWastage $materialWastage)
    {
        $materialWastage->delete();
        return redirect()->route('admin.procurement.material-wastages.index')
            ->with('success', 'Wastage record deleted successfully.');
    }
}
