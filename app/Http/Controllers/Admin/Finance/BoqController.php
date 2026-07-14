<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Imports\BoqItemsImport;
use App\Exports\ReportExport;
use App\Models\Boq;
use App\Models\BoqItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BoqController extends Controller
{
    public function index(Request $request)
    {
        $query = Boq::with('project', 'creator');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $boqs = $query->latest()->paginate(15);
        $projects = Project::all();
        return view('admin.finance.boqs.index', compact('boqs', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.finance.boqs.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,approved,revised',
        ]);

        $validated['boq_number'] = 'BOQ-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        $validated['total_amount'] = 0;
        $validated['created_by'] = Auth::id();

        $boq = Boq::create($validated);

        return redirect()->route('admin.finance.boqs.show', $boq->id)
            ->with('success', 'BOQ created. Add items now.');
    }

    public function show(Boq $boq)
    {
        $boq->load('project', 'creator', 'items');
        return view('admin.finance.boqs.show', compact('boq'));
    }

    public function edit(Boq $boq)
    {
        $projects = Project::all();
        return view('admin.finance.boqs.edit', compact('boq', 'projects'));
    }

    public function update(Request $request, Boq $boq)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,approved,revised',
        ]);

        $boq->update($validated);

        return redirect()->route('admin.finance.boqs.index')
            ->with('success', 'BOQ updated successfully.');
    }

    public function destroy(Boq $boq)
    {
        $boq->items()->delete();
        $boq->delete();
        return redirect()->route('admin.finance.boqs.index')
            ->with('success', 'BOQ deleted successfully.');
    }

    public function addItem(Request $request, Boq $boq)
    {
        $validated = $request->validate([
            'item_number' => 'required|string|max:50',
            'description' => 'required|string',
            'unit' => 'required|string|max:20',
            'quantity' => 'required|numeric|min:0.0001',
            'unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];
        $validated['boq_id'] = $boq->id;

        BoqItem::create($validated);

        $boq->update(['total_amount' => $boq->items()->sum('total_price')]);

        return back()->with('success', 'Item added to BOQ.');
    }

    public function removeItem(Boq $boq, BoqItem $boqItem)
    {
        $boqItem->delete();
        $boq->update(['total_amount' => $boq->items()->sum('total_price')]);

        return back()->with('success', 'Item removed from BOQ.');
    }

    public function importItems(Request $request, Boq $boq)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new BoqItemsImport($boq->id);
        Excel::import($import, $request->file('file'));

        $boq->update(['total_amount' => $boq->items()->sum('total_price')]);

        $message = $import->imported . ' item(s) imported successfully.';
        if (count($import->failures) > 0) {
            $errors = collect($import->failures)
                ->groupBy('row')
                ->map(fn($fails, $row) => "Row {$row}: " . implode('; ', $fails->pluck('errors')->flatten()->toArray()))
                ->values()
                ->toArray();
            return back()->with('warning', $message . ' ' . count($import->failures) . ' row(s) had errors.')->with('import_errors', $errors);
        }

        return back()->with('success', $message);
    }

    public function downloadTemplate()
    {
        $headings = ['item_number', 'description', 'unit', 'quantity', 'unit_price', 'notes'];
        $data = [
            ['ITEM-001', 'Sample item description', 'ea', 10, 1500, 'Optional notes'],
        ];
        return Excel::download(new ReportExport($data, $headings, 'BOQ Import Template'), 'boq-import-template.xlsx');
    }

    public function printPdf(Boq $boq)
    {
        $boq->load('project', 'creator', 'items');
        $pdf = Pdf::loadView('admin.finance.boqs.pdf.boq', compact('boq'));
        return $pdf->stream();
    }
}
