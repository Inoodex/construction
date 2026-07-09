<?php

namespace App\Http\Controllers\Admin\Quality;

use App\Http\Controllers\Controller;
use App\Models\MaterialTestCertificate;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialTestCertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialTestCertificate::with('project');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('material_type')) {
            $query->where('material_type', $request->material_type);
        }
        if ($request->filled('compliance_status')) {
            $query->where('compliance_status', $request->compliance_status);
        }

        $records = $query->latest('test_date')->paginate(20);
        $projects = Project::orderBy('name')->get();

        return view('admin.quality.material-test-certificates.index', compact('records', 'projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        return view('admin.quality.material-test-certificates.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'material_name' => 'required|string|max:255',
            'material_type' => 'required|in:concrete,steel,soil,aggregate,cement,other',
            'supplier' => 'nullable|string|max:255',
            'batch_number' => 'nullable|string|max:255',
            'certificate_number' => 'required|string|max:255',
            'test_date' => 'required|date',
            'test_result' => 'required|in:pass,fail,conditional',
            'test_parameters' => 'nullable|string',
            'compliance_status' => 'required|in:compliant,non_compliant,pending',
            'certificate_file' => 'nullable|file|max:10240',
            'valid_until' => 'nullable|date|after_or_equal:test_date',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('certificate_file')) {
            $validated['certificate_file'] = $request->file('certificate_file')->store('material-certificates', 'public');
        }

        MaterialTestCertificate::create($validated);

        return redirect()->route('admin.quality.material-test-certificates.index')
            ->with('success', 'Material test certificate created.');
    }

    public function show(MaterialTestCertificate $materialTestCertificate)
    {
        $materialTestCertificate->load('project', 'creator');
        return view('admin.quality.material-test-certificates.show', compact('materialTestCertificate'));
    }

    public function edit(MaterialTestCertificate $materialTestCertificate)
    {
        $projects = Project::orderBy('name')->get();
        return view('admin.quality.material-test-certificates.edit', compact('materialTestCertificate', 'projects'));
    }

    public function update(Request $request, MaterialTestCertificate $materialTestCertificate)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'material_name' => 'required|string|max:255',
            'material_type' => 'required|in:concrete,steel,soil,aggregate,cement,other',
            'supplier' => 'nullable|string|max:255',
            'batch_number' => 'nullable|string|max:255',
            'certificate_number' => 'required|string|max:255',
            'test_date' => 'required|date',
            'test_result' => 'required|in:pass,fail,conditional',
            'test_parameters' => 'nullable|string',
            'compliance_status' => 'required|in:compliant,non_compliant,pending',
            'certificate_file' => 'nullable|file|max:10240',
            'valid_until' => 'nullable|date|after_or_equal:test_date',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('certificate_file')) {
            if ($materialTestCertificate->certificate_file) {
                Storage::disk('public')->delete($materialTestCertificate->certificate_file);
            }
            $validated['certificate_file'] = $request->file('certificate_file')->store('material-certificates', 'public');
        }

        $materialTestCertificate->update($validated);

        return redirect()->route('admin.quality.material-test-certificates.index')
            ->with('success', 'Material test certificate updated.');
    }

    public function destroy(MaterialTestCertificate $materialTestCertificate)
    {
        if ($materialTestCertificate->certificate_file) {
            Storage::disk('public')->delete($materialTestCertificate->certificate_file);
        }
        $materialTestCertificate->delete();
        return back()->with('success', 'Material test certificate deleted.');
    }
}
