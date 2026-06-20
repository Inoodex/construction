<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Site;
use App\Models\Task;
use App\Models\Vendor;
use App\Models\PurchaseOrder;
use App\Models\Stock;
use App\Models\Material;
use App\Models\MaterialWastage;
use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Models\Privilege;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userModel = config('tyro-dashboard.user_model', 'App\\Models\\User');

        // --- Projects ---
        $totalProjects      = Project::count();
        $activeProjects     = Project::where('status', 'active')->count();
        $planningProjects   = Project::where('status', 'planning')->count();
        $completedProjects  = Project::where('status', 'completed')->count();
        $totalBudget        = Project::sum('budget');

        // --- Sites ---
        $totalSites         = Site::count();

        // --- Tasks ---
        $totalTasks         = Task::count();
        $openTasks          = Task::where('status', 'open')->count();
        $inProgressTasks    = Task::where('status', 'in_progress')->count();
        $criticalTasks      = Task::where('priority', 'critical')->whereIn('status', ['open', 'in_progress'])->count();

        // --- Vendors ---
        $totalVendors       = Vendor::count();
        $approvedVendors    = Vendor::where('status', 'approved')->count();
        $pendingVendors     = Vendor::where('status', 'pending')->count();

        // --- Procurement ---
        $totalPOs           = PurchaseOrder::count();
        $pendingPOs         = PurchaseOrder::whereIn('status', ['draft', 'ordered'])->count();
        $totalPOValue       = PurchaseOrder::sum('total_amount');

        // --- Inventory Alerts (stocks below min_stock threshold) ---
        $lowStockItems = Stock::with(['material', 'warehouse', 'site'])
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('quantity', '>', 0)
                       ->where('min_stock', '>', 0)
                       ->whereColumn('quantity', '<', 'min_stock');
                })->orWhere('quantity', '<=', 0);
            })
            ->orderBy('quantity', 'asc')
            ->take(10)
            ->get();

        // --- Recent Projects ---
        $recentProjects = Project::with('creator')
            ->latest()
            ->take(5)
            ->get();

        // --- Recent Purchase Orders ---
        $recentPOs = PurchaseOrder::with('vendor')
            ->latest()
            ->take(5)
            ->get();

        // --- Task breakdown by priority (for chart) ---
        $tasksByPriority = Task::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority');

        // --- Project status breakdown (for chart) ---
        $projectsByStatus = Project::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.dashboard', compact(
            'totalProjects', 'activeProjects', 'planningProjects', 'completedProjects', 'totalBudget',
            'totalSites',
            'totalTasks', 'openTasks', 'inProgressTasks', 'criticalTasks',
            'totalVendors', 'approvedVendors', 'pendingVendors',
            'totalPOs', 'pendingPOs', 'totalPOValue',
            'lowStockItems',
            'recentProjects',
            'recentPOs',
            'tasksByPriority',
            'projectsByStatus'
        ));
    }
}
