<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\BoqItem;
use App\Models\MaterialTakeoff;
use App\Models\Project;
use Illuminate\Http\Request;

class MaterialTakeoffController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialTakeoff::with('project', 'boqItem');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $takeoffs = $query->latest()->paginate(20);
        $projects = Project::all();

        return view('admin.finance.material-takeoffs.index', compact('takeoffs', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $boqItems = BoqItem::with('boq.project')->get();
        return view('admin.finance.material-takeoffs.create', compact('projects', 'boqItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'boq_item_id' => 'nullable|exists:boq_items,id',
            'description' => 'required|string|max:500',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'source_drawing' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];

        MaterialTakeoff::create($validated);

        return redirect()->route('admin.finance.material-takeoffs.index')
            ->with('success', 'Material takeoff created.');
    }

    public function show(MaterialTakeoff $materialTakeoff)
    {
        $materialTakeoff->load('project', 'boqItem.boq');
        return view('admin.finance.material-takeoffs.show', compact('materialTakeoff'));
    }

    public function edit(MaterialTakeoff $materialTakeoff)
    {
        $projects = Project::all();
        $boqItems = BoqItem::with('boq.project')->get();
        return view('admin.finance.material-takeoffs.edit', compact('materialTakeoff', 'projects', 'boqItems'));
    }

    public function update(Request $request, MaterialTakeoff $materialTakeoff)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'boq_item_id' => 'nullable|exists:boq_items,id',
            'description' => 'required|string|max:500',
            'unit' => 'nullable|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'source_drawing' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];

        $materialTakeoff->update($validated);

        return redirect()->route('admin.finance.material-takeoffs.index')
            ->with('success', 'Material takeoff updated.');
    }

    public function destroy(MaterialTakeoff $materialTakeoff)
    {
        $materialTakeoff->delete();
        return redirect()->route('admin.finance.material-takeoffs.index')
            ->with('success', 'Material takeoff deleted.');
    }
}
