<?php

namespace App\Http\Controllers\Admin\Quality;

use App\Http\Controllers\Controller;
use App\Models\PunchList;
use App\Models\PunchListItem;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PunchListController extends Controller
{
    public function index(Request $request)
    {
        $query = PunchList::with('project');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest('inspection_date')->paginate(20);
        $projects = Project::orderBy('name')->get();

        return view('admin.quality.punch-lists.index', compact('records', 'projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        return view('admin.quality.punch-lists.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'inspection_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:inspection_date',
            'items' => 'nullable|array',
            'items.*.description' => 'required|string|max:255',
            'items.*.location' => 'nullable|string|max:255',
            'items.*.trade' => 'required|in:civil,electrical,mechanical,plumbing,painting,other',
            'items.*.priority' => 'required|in:low,medium,high,critical',
            'items.*.assigned_to' => 'nullable|string|max:255',
            'items.*.notes' => 'nullable|string',
        ]);

        $validated['punch_list_number'] = PunchList::generatePunchListNumber();
        $validated['status'] = 'open';
        $validated['created_by'] = auth()->id();

        unset($validated['items']);
        $punchList = PunchList::create($validated);

        if ($request->filled('items')) {
            foreach ($request->items as $index => $item) {
                $punchList->items()->create([
                    'description' => $item['description'],
                    'location' => $item['location'] ?? null,
                    'trade' => $item['trade'],
                    'priority' => $item['priority'],
                    'assigned_to' => $item['assigned_to'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'order_index' => $index,
                ]);
            }
        }

        return redirect()->route('admin.quality.punch-lists.index')
            ->with('success', 'Punch list created.');
    }

    public function show(PunchList $punchList)
    {
        $punchList->load('project', 'creator', 'items');
        return view('admin.quality.punch-lists.show', compact('punchList'));
    }

    public function edit(PunchList $punchList)
    {
        $punchList->load('items');
        $projects = Project::orderBy('name')->get();
        return view('admin.quality.punch-lists.edit', compact('punchList', 'projects'));
    }

    public function update(Request $request, PunchList $punchList)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:open,in_progress,completed,closed',
            'inspection_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:inspection_date',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|exists:punch_list_items,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.location' => 'nullable|string|max:255',
            'items.*.trade' => 'required|in:civil,electrical,mechanical,plumbing,painting,other',
            'items.*.priority' => 'required|in:low,medium,high,critical',
            'items.*.status' => 'required|in:open,in_progress,completed,verified',
            'items.*.assigned_to' => 'nullable|string|max:255',
            'items.*.completed_date' => 'nullable|date',
            'items.*.verified_date' => 'nullable|date',
            'items.*.notes' => 'nullable|string',
        ]);

        $keptIds = [];
        unset($validated['items']);
        $punchList->update($validated);

        if ($request->filled('items')) {
            foreach ($request->items as $index => $item) {
                $data = [
                    'description' => $item['description'],
                    'location' => $item['location'] ?? null,
                    'trade' => $item['trade'],
                    'priority' => $item['priority'],
                    'status' => $item['status'] ?? 'open',
                    'assigned_to' => $item['assigned_to'] ?? null,
                    'completed_date' => $item['completed_date'] ?? null,
                    'verified_date' => $item['verified_date'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'order_index' => $index,
                ];

                if (!empty($item['id'])) {
                    $existing = PunchListItem::find($item['id']);
                    if ($existing && $existing->punch_list_id === $punchList->id) {
                        $existing->update($data);
                        $keptIds[] = $existing->id;
                    }
                } else {
                    $new = $punchList->items()->create($data);
                    $keptIds[] = $new->id;
                }
            }
        }

        $punchList->items()->whereNotIn('id', $keptIds)->delete();

        return redirect()->route('admin.quality.punch-lists.index')
            ->with('success', 'Punch list updated.');
    }

    public function destroy(PunchList $punchList)
    {
        $punchList->delete();
        return back()->with('success', 'Punch list deleted.');
    }

    public function printPdf(PunchList $punchList)
    {
        $punchList->load('project', 'creator', 'items');
        $pdf = Pdf::loadView('admin.quality.punch-lists.pdf.punch-list', compact('punchList'));
        return $pdf->stream();
    }
}
