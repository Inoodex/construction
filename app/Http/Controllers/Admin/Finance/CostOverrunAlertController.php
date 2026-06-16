<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\CostOverrunAlert;
use App\Models\Project;
use App\Services\CostOverrunService;
use Illuminate\Http\Request;

class CostOverrunAlertController extends Controller
{
    public function index(Request $request)
    {
        $query = CostOverrunAlert::with('project', 'budget');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $alerts = $query->latest()->paginate(15);
        $projects = Project::all();

        $stats = [
            'total'    => CostOverrunAlert::count(),
            'open'     => CostOverrunAlert::where('status', 'open')->count(),
            'warning'  => CostOverrunAlert::where('severity', 'warning')->where('status', 'open')->count(),
            'danger'   => CostOverrunAlert::where('severity', 'danger')->where('status', 'open')->count(),
            'critical' => CostOverrunAlert::where('severity', 'critical')->where('status', 'open')->count(),
        ];

        return view('admin.finance.cost-overrun-alerts.index', compact('alerts', 'projects', 'stats'));
    }

    public function acknowledge(Request $request, CostOverrunAlert $alert, CostOverrunService $service)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);
        $service->acknowledge($alert->id, $request->notes);
        return back()->with('success', 'Alert acknowledged.');
    }

    public function resolve(Request $request, CostOverrunAlert $alert, CostOverrunService $service)
    {
        $request->validate(['notes' => 'required|string|max:500']);
        $service->resolve($alert->id, $request->notes);
        return back()->with('success', 'Alert resolved.');
    }
}
