<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\SafetyAudit;
use App\Models\Project;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;

class SafetyAuditController extends Controller
{
    public function index(Request $request)
    {
        $query = SafetyAudit::with(['project', 'site', 'auditor']);

        if ($request->filled('audit_type')) {
            $query->where('audit_type', $request->audit_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $audits = $query->latest()->paginate(20)->withQueryString();
        $projects = Project::orderBy('name')->get();

        return view('admin.hr.safety-audits.index', compact('audits', 'projects'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.hr.safety-audits.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'auditor_id' => 'required|exists:users,id',
            'audit_date' => 'required|date',
            'audit_type' => 'required|in:internal,external,regulatory,client,other',
            'scope' => 'required|string|max:255',
            'findings' => 'nullable|string',
            'non_conformances' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'status' => 'required|in:scheduled,in_progress,completed,follow_up',
            'score' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['audit_number'] = SafetyAudit::generateAuditNumber();

        SafetyAudit::create($validated);

        return redirect()->route('admin.hr.safety-audits.index')
            ->with('success', 'Safety audit created successfully.');
    }

    public function show(SafetyAudit $safetyAudit)
    {
        $safetyAudit->load(['project', 'site', 'auditor']);

        return view('admin.hr.safety-audits.show', compact('safetyAudit'));
    }

    public function edit(SafetyAudit $safetyAudit)
    {
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.hr.safety-audits.edit', compact('safetyAudit', 'projects', 'users'));
    }

    public function update(Request $request, SafetyAudit $safetyAudit)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'site_id' => 'nullable|exists:sites,id',
            'auditor_id' => 'required|exists:users,id',
            'audit_date' => 'required|date',
            'audit_type' => 'required|in:internal,external,regulatory,client,other',
            'scope' => 'required|string|max:255',
            'findings' => 'nullable|string',
            'non_conformances' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'status' => 'required|in:scheduled,in_progress,completed,follow_up',
            'score' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $safetyAudit->update($validated);

        return redirect()->route('admin.hr.safety-audits.show', $safetyAudit)
            ->with('success', 'Safety audit updated successfully.');
    }

    public function destroy(SafetyAudit $safetyAudit)
    {
        $safetyAudit->delete();

        return redirect()->route('admin.hr.safety-audits.index')
            ->with('success', 'Safety audit deleted successfully.');
    }
}
