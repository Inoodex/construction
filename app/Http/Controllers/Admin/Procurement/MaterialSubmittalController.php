<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\MaterialSubmittal;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialSubmittalController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialSubmittal::with('project', 'submitter', 'reviewer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('submittal_number', 'like', '%' . $request->search . '%')
                    ->orWhere('material_name', 'like', '%' . $request->search . '%');
            });
        }

        $submittals = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.procurement.material-submittals.index', compact('submittals', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $materials = \App\Models\Material::orderBy('name')->get();
        return view('admin.procurement.material-submittals.create', compact('projects', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_name' => 'required|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model_reference' => 'nullable|string|max:255',
            'specification_details' => 'nullable|string',
            'quantity_unit' => 'nullable|string|max:50',
        ]);

        $number = 'MS-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        MaterialSubmittal::create(array_merge($validated, [
            'submittal_number' => $number,
            'status' => 'draft',
            'submitted_by' => Auth::id(),
        ]));

        return redirect()->route('admin.procurement.material-submittals.index')
            ->with('success', "Submittal $number created.");
    }

    public function show(MaterialSubmittal $materialSubmittal)
    {
        $materialSubmittal->load('project', 'submitter', 'reviewer');
        return view('admin.procurement.material-submittals.show', compact('materialSubmittal'));
    }

    public function edit(MaterialSubmittal $materialSubmittal)
    {
        if (!in_array($materialSubmittal->status, ['draft', 'resubmitted'])) {
            return redirect()->route('admin.procurement.material-submittals.show', $materialSubmittal)
                ->with('error', 'Only draft or resubmitted submittals can be edited.');
        }

        $projects = Project::all();
        $materials = \App\Models\Material::orderBy('name')->get();
        return view('admin.procurement.material-submittals.edit', compact('materialSubmittal', 'projects', 'materials'));
    }

    public function update(Request $request, MaterialSubmittal $materialSubmittal)
    {
        if (!in_array($materialSubmittal->status, ['draft', 'resubmitted'])) {
            return redirect()->route('admin.procurement.material-submittals.show', $materialSubmittal)
                ->with('error', 'Only draft or resubmitted submittals can be edited.');
        }

        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_name' => 'required|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model_reference' => 'nullable|string|max:255',
            'specification_details' => 'nullable|string',
            'quantity_unit' => 'nullable|string|max:50',
        ]);

        $materialSubmittal->update($validated);

        return redirect()->route('admin.procurement.material-submittals.show', $materialSubmittal)
            ->with('success', 'Submittal updated.');
    }

    public function destroy(MaterialSubmittal $materialSubmittal)
    {
        if (!in_array($materialSubmittal->status, ['draft', 'rejected', 'approved', 'approved_with_conditions'])) {
            return back()->with('error', 'Cannot delete submittal in current status.');
        }

        $materialSubmittal->delete();
        return redirect()->route('admin.procurement.material-submittals.index')
            ->with('success', 'Submittal deleted.');
    }

    public function submit(MaterialSubmittal $materialSubmittal)
    {
        if ($materialSubmittal->status !== 'draft') {
            return back()->with('error', 'Submittal is not in draft status.');
        }

        $materialSubmittal->update([
            'status' => 'submitted',
            'submitted_date' => now(),
        ]);

        return back()->with('success', 'Submittal submitted for review.');
    }

    public function review(Request $request, MaterialSubmittal $materialSubmittal)
    {
        if ($materialSubmittal->status !== 'submitted') {
            return back()->with('error', 'Submittal is not submitted for review.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approved,approved_with_conditions,rejected',
            'review_comments' => 'nullable|string',
            'resubmission_deadline' => 'nullable|date|required_if:action,rejected',
        ]);

        $data = [
            'status' => $validated['action'],
            'reviewed_by' => Auth::id(),
            'review_date' => now(),
            'review_comments' => $validated['review_comments'],
        ];

        if ($validated['action'] === 'rejected') {
            $data['resubmission_deadline'] = $validated['resubmission_deadline'] ?? null;
        }

        $materialSubmittal->update($data);

        $label = str_replace('_', ' ', $validated['action']);
        return redirect()->route('admin.procurement.material-submittals.index')->with('success', "Submittal {$label}.");
    }

    public function resubmitForm(MaterialSubmittal $materialSubmittal)
    {
        if ($materialSubmittal->status !== 'rejected') {
            return back()->with('error', 'Only rejected submittals can be resubmitted.');
        }

        return view('admin.procurement.material-submittals.resubmit', compact('materialSubmittal'));
    }

    public function resubmit(Request $request, MaterialSubmittal $materialSubmittal)
    {
        if ($materialSubmittal->status !== 'rejected') {
            return back()->with('error', 'Only rejected submittals can be resubmitted.');
        }

        $validated = $request->validate([
            'description' => 'nullable|string',
            'specification_details' => 'nullable|string',
        ]);

        $materialSubmittal->update([
            'description' => $validated['description'] ?? $materialSubmittal->description,
            'specification_details' => $validated['specification_details'] ?? $materialSubmittal->specification_details,
            'status' => 'resubmitted',
            'submitted_date' => now(),
            'reviewed_by' => null,
            'review_date' => null,
            'review_comments' => null,
        ]);

        return redirect()->route('admin.procurement.material-submittals.show', $materialSubmittal)
            ->with('success', 'Submittal resubmitted for review.');
    }
}
