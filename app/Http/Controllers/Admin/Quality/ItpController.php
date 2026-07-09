<?php

namespace App\Http\Controllers\Admin\Quality;

use App\Http\Controllers\Controller;
use App\Models\Itp;
use App\Models\ItpItem;
use App\Models\Project;
use Illuminate\Http\Request;

class ItpController extends Controller
{
    public function index(Request $request)
    {
        $query = Itp::with('project');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('phase')) {
            $query->where('phase', $request->phase);
        }

        $records = $query->latest()->paginate(20);
        $projects = Project::orderBy('name')->get();

        return view('admin.quality.itps.index', compact('records', 'projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        return view('admin.quality.itps.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phase' => 'required|in:foundation,superstructure,finishing,mep,other',
            'items' => 'nullable|array',
            'items.*.description' => 'required|string|max:255',
            'items.*.specification_reference' => 'nullable|string|max:255',
            'items.*.inspection_type' => 'required|in:visual,dimensional,testing,documentation',
            'items.*.acceptance_criteria' => 'nullable|string',
            'items.*.method' => 'required|in:observation,measurement,testing,review',
            'items.*.frequency' => 'required|in:each_occurrence,daily,weekly,monthly',
        ]);

        $validated['itp_number'] = Itp::generateItpNumber();
        $validated['status'] = 'draft';
        $validated['created_by'] = auth()->id();

        unset($validated['items']);
        $itp = Itp::create($validated);

        if ($request->filled('items')) {
            foreach ($request->items as $index => $item) {
                $itp->items()->create([
                    'description' => $item['description'],
                    'specification_reference' => $item['specification_reference'] ?? null,
                    'inspection_type' => $item['inspection_type'],
                    'acceptance_criteria' => $item['acceptance_criteria'] ?? null,
                    'method' => $item['method'],
                    'frequency' => $item['frequency'],
                    'order_index' => $index,
                ]);
            }
        }

        return redirect()->route('admin.quality.itps.index')
            ->with('success', 'ITP created.');
    }

    public function show(Itp $itp)
    {
        $itp->load('project', 'creator', 'items');
        return view('admin.quality.itps.show', compact('itp'));
    }

    public function edit(Itp $itp)
    {
        $itp->load('items');
        $projects = Project::orderBy('name')->get();
        return view('admin.quality.itps.edit', compact('itp', 'projects'));
    }

    public function update(Request $request, Itp $itp)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phase' => 'required|in:foundation,superstructure,finishing,mep,other',
            'status' => 'required|in:draft,active,completed,archived',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|exists:itp_items,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.specification_reference' => 'nullable|string|max:255',
            'items.*.inspection_type' => 'required|in:visual,dimensional,testing,documentation',
            'items.*.acceptance_criteria' => 'nullable|string',
            'items.*.method' => 'required|in:observation,measurement,testing,review',
            'items.*.frequency' => 'required|in:each_occurrence,daily,weekly,monthly',
            'items.*.status' => 'required|in:pending,in_progress,passed,failed,n_a',
            'items.*.result' => 'nullable|string',
            'items.*.inspected_date' => 'nullable|date',
            'items.*.inspector' => 'nullable|string|max:255',
        ]);

        $keptIds = [];
        unset($validated['items']);
        $itp->update($validated);

        if ($request->filled('items')) {
            foreach ($request->items as $index => $item) {
                $data = [
                    'description' => $item['description'],
                    'specification_reference' => $item['specification_reference'] ?? null,
                    'inspection_type' => $item['inspection_type'],
                    'acceptance_criteria' => $item['acceptance_criteria'] ?? null,
                    'method' => $item['method'],
                    'frequency' => $item['frequency'],
                    'status' => $item['status'] ?? 'pending',
                    'result' => $item['result'] ?? null,
                    'inspected_date' => $item['inspected_date'] ?? null,
                    'inspector' => $item['inspector'] ?? null,
                    'order_index' => $index,
                ];

                if (!empty($item['id'])) {
                    $existing = ItpItem::find($item['id']);
                    if ($existing && $existing->itp_id === $itp->id) {
                        $existing->update($data);
                        $keptIds[] = $existing->id;
                    }
                } else {
                    $new = $itp->items()->create($data);
                    $keptIds[] = $new->id;
                }
            }
        }

        $itp->items()->whereNotIn('id', $keptIds)->delete();

        return redirect()->route('admin.quality.itps.index')
            ->with('success', 'ITP updated.');
    }

    public function destroy(Itp $itp)
    {
        $itp->delete();
        return back()->with('success', 'ITP deleted.');
    }
}
