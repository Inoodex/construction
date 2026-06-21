<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HseChecklist;
use Illuminate\Http\Request;

class HseChecklistController extends Controller
{
    public function index(Request $request)
    {
        $query = HseChecklist::with('employee');

        if ($request->filled('checklist_type')) {
            $query->where('checklist_type', $request->checklist_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest('inspection_date')->paginate(20);

        return view('admin.hr.hse-checklists.index', compact('records'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.hse-checklists.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'checklist_type' => 'required|in:general,fire,electrical,scaffolding,ppe,excavation,other',
            'location' => 'nullable|string|max:255',
            'inspection_date' => 'required|date',
            'employee_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:open,closed',
            'findings' => 'nullable|string',
            'corrective_actions' => 'nullable|string',
            'closure_date' => 'nullable|date|after_or_equal:inspection_date',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.is_compliant' => 'boolean',
            'items.*.remarks' => 'nullable|string',
        ]);

        $validated['closure_date'] = $request->status === 'closed'
            ? ($request->closure_date ?? now())
            : $request->closure_date;

        $checklist = HseChecklist::create($validated);

        if ($request->filled('items')) {
            foreach ($request->items as $index => $item) {
                $checklist->items()->create([
                    'item_name' => $item['item_name'],
                    'is_compliant' => $item['is_compliant'] ?? false,
                    'remarks' => $item['remarks'] ?? null,
                    'order_index' => $index,
                ]);
            }
        }

        return redirect()->route('admin.hr.hse-checklists.index')
            ->with('success', 'HSE checklist created.');
    }

    public function show(HseChecklist $hseChecklist)
    {
        $hseChecklist->load('employee', 'items');
        return view('admin.hr.hse-checklists.show', compact('hseChecklist'));
    }

    public function edit(HseChecklist $hseChecklist)
    {
        $hseChecklist->load('items');
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.hse-checklists.edit', compact('hseChecklist', 'employees'));
    }

    public function update(Request $request, HseChecklist $hseChecklist)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'checklist_type' => 'required|in:general,fire,electrical,scaffolding,ppe,excavation,other',
            'location' => 'nullable|string|max:255',
            'inspection_date' => 'required|date',
            'employee_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:open,closed',
            'findings' => 'nullable|string',
            'corrective_actions' => 'nullable|string',
            'closure_date' => 'nullable|date|after_or_equal:inspection_date',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|exists:hse_checklist_items,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.is_compliant' => 'boolean',
            'items.*.remarks' => 'nullable|string',
        ]);

        $validated['closure_date'] = $request->status === 'closed'
            ? ($request->closure_date ?? now())
            : $request->closure_date;

        $hseChecklist->update($validated);

        $keptIds = [];
        if ($request->filled('items')) {
            foreach ($request->items as $index => $item) {
                $data = [
                    'item_name' => $item['item_name'],
                    'is_compliant' => $item['is_compliant'] ?? false,
                    'remarks' => $item['remarks'] ?? null,
                    'order_index' => $index,
                ];

                if (!empty($item['id'])) {
                    $existing = \App\Models\HseChecklistItem::find($item['id']);
                    if ($existing && $existing->hse_checklist_id === $hseChecklist->id) {
                        $existing->update($data);
                        $keptIds[] = $existing->id;
                    }
                } else {
                    $new = $hseChecklist->items()->create($data);
                    $keptIds[] = $new->id;
                }
            }
        }

        $hseChecklist->items()->whereNotIn('id', $keptIds)->delete();

        return redirect()->route('admin.hr.hse-checklists.index')
            ->with('success', 'HSE checklist updated.');
    }

    public function destroy(HseChecklist $hseChecklist)
    {
        $hseChecklist->delete();
        return back()->with('success', 'HSE checklist deleted.');
    }
}
