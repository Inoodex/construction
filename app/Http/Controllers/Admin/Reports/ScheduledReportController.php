<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\ReportTemplate;
use App\Models\ScheduledReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduledReportController extends Controller
{
    public function index()
    {
        $schedules = ScheduledReport::with('template')->latest()->paginate(15);
        return view('admin.reports.scheduled-reports.index', compact('schedules'));
    }

    public function create()
    {
        $templates = ReportTemplate::all();
        return view('admin.reports.scheduled-reports.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_template_id' => 'required|exists:report_templates,id',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'frequency' => 'required|in:daily,weekly,monthly',
            'next_run_at' => 'required|date|after:now',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['recipients'] = $request->recipients;

        ScheduledReport::create($validated);

        return redirect()->route('admin.reports.scheduled-reports.index')
            ->with('success', 'Report schedule created.');
    }

    public function show(ScheduledReport $scheduledReport)
    {
        $scheduledReport->load('template');
        return view('admin.reports.scheduled-reports.show', compact('scheduledReport'));
    }

    public function edit(ScheduledReport $scheduledReport)
    {
        $templates = ReportTemplate::all();
        return view('admin.reports.scheduled-reports.edit', compact('scheduledReport', 'templates'));
    }

    public function update(Request $request, ScheduledReport $scheduledReport)
    {
        $validated = $request->validate([
            'report_template_id' => 'required|exists:report_templates,id',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'frequency' => 'required|in:daily,weekly,monthly',
            'next_run_at' => 'required|date|after:now',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['recipients'] = $request->recipients;

        $scheduledReport->update($validated);

        return redirect()->route('admin.reports.scheduled-reports.index')
            ->with('success', 'Report schedule updated.');
    }

    public function destroy(ScheduledReport $scheduledReport)
    {
        $scheduledReport->delete();
        return redirect()->route('admin.reports.scheduled-reports.index')
            ->with('success', 'Report schedule deleted.');
    }
}
