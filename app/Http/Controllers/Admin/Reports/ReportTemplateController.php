<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\ReportTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportTemplateController extends Controller
{
    public function index()
    {
        $templates = ReportTemplate::with('creator')->latest()->paginate(15);
        return view('admin.reports.report-templates.index', compact('templates'));
    }

    public function create()
    {
        $reportTypes = ['financial', 'inventory', 'progress', 'safety'];
        return view('admin.reports.report-templates.create', compact('reportTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_type' => 'required|string|in:financial,inventory,progress,safety',
            'configuration' => 'nullable|json',
        ]);

        $validated['configuration'] = $request->filled('configuration')
            ? json_decode($request->configuration, true)
            : [];
        $validated['created_by'] = Auth::id();

        ReportTemplate::create($validated);

        return redirect()->route('admin.reports.report-templates.index')
            ->with('success', 'Report template created.');
    }

    public function show(ReportTemplate $reportTemplate)
    {
        $reportTemplate->load('creator', 'scheduledReports');
        return view('admin.reports.report-templates.show', compact('reportTemplate'));
    }

    public function edit(ReportTemplate $reportTemplate)
    {
        $reportTypes = ['financial', 'inventory', 'progress', 'safety'];
        return view('admin.reports.report-templates.edit', compact('reportTemplate', 'reportTypes'));
    }

    public function update(Request $request, ReportTemplate $reportTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_type' => 'required|string|in:financial,inventory,progress,safety',
            'configuration' => 'nullable|json',
        ]);

        $validated['configuration'] = $request->filled('configuration')
            ? json_decode($request->configuration, true)
            : [];

        $reportTemplate->update($validated);

        return redirect()->route('admin.reports.report-templates.index')
            ->with('success', 'Report template updated.');
    }

    public function destroy(ReportTemplate $reportTemplate)
    {
        $reportTemplate->delete();
        return redirect()->route('admin.reports.report-templates.index')
            ->with('success', 'Report template deleted.');
    }
}
