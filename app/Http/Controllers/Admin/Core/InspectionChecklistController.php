<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\InspectionChecklist;
use App\Models\InspectionChecklistItem;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InspectionChecklistController extends Controller
{
    public function index(Request $request)
    {
        $query = InspectionChecklist::with('site', 'inspector');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        $checklists = $query->withCount('items')->latest()->paginate(15);
        $sites = Site::with('project')->get();

        return view('admin.core.inspection-checklists.index', compact('checklists', 'sites'));
    }

    public function create()
    {
        $sites = Site::with('project')->get();
        return view('admin.core.inspection-checklists.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inspection_date' => 'required|date',
            'status' => 'required|in:pending,passed,failed,conditional',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.is_checked' => 'boolean',
            'items.*.remarks' => 'nullable|string',
        ]);

        $validated['inspector_id'] = Auth::id();

        $checklist = InspectionChecklist::create($validated);

        if ($request->filled('items')) {
            foreach ($request->items as $index => $item) {
                $checklist->items()->create([
                    'item_name' => $item['item_name'],
                    'is_checked' => $item['is_checked'] ?? false,
                    'remarks' => $item['remarks'] ?? null,
                    'order_index' => $index,
                ]);
            }
        }

        return redirect()->route('admin.core.inspection-checklists.index')
            ->with('success', 'Inspection checklist created successfully.');
    }

    public function show(InspectionChecklist $checklist)
    {
        $checklist->load('site', 'inspector', 'items');
        return view('admin.core.inspection-checklists.show', compact('checklist'));
    }

    public function edit(InspectionChecklist $checklist)
    {
        $checklist->load('items');
        $sites = Site::with('project')->get();
        return view('admin.core.inspection-checklists.edit', compact('checklist', 'sites'));
    }

    public function update(Request $request, InspectionChecklist $checklist)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inspection_date' => 'required|date',
            'status' => 'required|in:pending,passed,failed,conditional',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|exists:inspection_checklist_items,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.is_checked' => 'boolean',
            'items.*.remarks' => 'nullable|string',
        ]);

        $checklist->update($validated);

        $keptIds = [];
        if ($request->filled('items')) {
            foreach ($request->items as $index => $item) {
                $data = [
                    'item_name' => $item['item_name'],
                    'is_checked' => $item['is_checked'] ?? false,
                    'remarks' => $item['remarks'] ?? null,
                    'order_index' => $index,
                ];

                if (!empty($item['id'])) {
                    $existing = InspectionChecklistItem::find($item['id']);
                    if ($existing && $existing->inspection_checklist_id === $checklist->id) {
                        $existing->update($data);
                        $keptIds[] = $existing->id;
                    }
                } else {
                    $new = $checklist->items()->create($data);
                    $keptIds[] = $new->id;
                }
            }
        }

        $checklist->items()->whereNotIn('id', $keptIds)->delete();

        return redirect()->route('admin.core.inspection-checklists.index')
            ->with('success', 'Inspection checklist updated successfully.');
    }

    public function destroy(InspectionChecklist $checklist)
    {
        $checklist->delete();
        return redirect()->route('admin.core.inspection-checklists.index')
            ->with('success', 'Inspection checklist deleted successfully.');
    }
}
