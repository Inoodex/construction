<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\SiteLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteLogController extends Controller
{
    public function globalIndex(Request $request)
    {
        $query = SiteLog::with('site.project', 'submitter');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        if ($request->filled('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        $logs = $query->latest()->paginate(15);
        $sites = Site::with('project')->get();

        return view('admin.core.site-logs.global_index', compact('logs', 'sites'));
    }

    public function index(Site $site)
    {
        $logs = $site->siteLogs()->with('submitter')->latest()->paginate(15);
        return view('admin.core.site-logs.index', compact('site', 'logs'));
    }

    public function create(Site $site)
    {
        return view('admin.core.site-logs.create', compact('site'));
    }

    public function store(Request $request, Site $site)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:daily_log,field_report',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'log_date' => 'required|date',
            'weather_conditions' => 'nullable|string|max:255',
            'temperature' => 'nullable|numeric|min:-50|max:60',
            'worker_count' => 'nullable|integer|min:0',
            'work_completed' => 'nullable|string',
            'equipment_used' => 'nullable|string',
            'materials_received' => 'nullable|string',
            'issues_notes' => 'nullable|string',
            'status' => 'required|in:draft,submitted',
        ]);

        $validated['submitted_by'] = Auth::id();

        $site->siteLogs()->create($validated);

        return redirect()->route('admin.core.sites.logs.index', $site)
            ->with('success', 'Site log created successfully.');
    }

    public function show(Site $site, SiteLog $log)
    {
        $log->load('submitter');
        return view('admin.core.site-logs.show', compact('site', 'log'));
    }

    public function edit(Site $site, SiteLog $log)
    {
        return view('admin.core.site-logs.edit', compact('site', 'log'));
    }

    public function update(Request $request, Site $site, SiteLog $log)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:daily_log,field_report',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'log_date' => 'required|date',
            'weather_conditions' => 'nullable|string|max:255',
            'temperature' => 'nullable|numeric|min:-50|max:60',
            'worker_count' => 'nullable|integer|min:0',
            'work_completed' => 'nullable|string',
            'equipment_used' => 'nullable|string',
            'materials_received' => 'nullable|string',
            'issues_notes' => 'nullable|string',
            'status' => 'required|in:draft,submitted',
        ]);

        $log->update($validated);

        return redirect()->route('admin.core.sites.logs.index', $site)
            ->with('success', 'Site log updated successfully.');
    }

    public function destroy(Site $site, SiteLog $log)
    {
        $log->delete();
        return redirect()->route('admin.core.sites.logs.index', $site)
            ->with('success', 'Site log deleted successfully.');
    }
}
