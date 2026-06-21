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
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Equipment;
use App\Models\EquipmentMaintenance;
use App\Models\LeaveRequest;
use App\Models\IncidentReport;
use App\Models\TrainingRecord;
use App\Models\Certification;
use App\Models\Invoice;
use App\Models\Bill;
use App\Models\Approval;
use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Models\Privilege;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Core: Projects ---
        $totalProjects      = Project::count();
        $activeProjects     = Project::where('status', 'active')->count();
        $planningProjects   = Project::where('status', 'planning')->count();
        $completedProjects  = Project::where('status', 'completed')->count();
        $totalBudget        = Project::sum('budget');
        $totalSites         = Site::count();

        // --- Core: Tasks ---
        $totalTasks         = Task::count();
        $openTasks          = Task::where('status', 'open')->count();
        $inProgressTasks    = Task::where('status', 'in_progress')->count();
        $criticalTasks      = Task::where('priority', 'critical')->whereIn('status', ['open', 'in_progress'])->count();

        // --- Procurement: Vendors ---
        $totalVendors       = Vendor::count();
        $approvedVendors    = Vendor::where('status', 'approved')->count();
        $pendingVendors     = Vendor::where('status', 'pending')->count();

        // --- Procurement: POs ---
        $totalPOs           = PurchaseOrder::count();
        $pendingPOs         = PurchaseOrder::whereIn('status', ['draft', 'ordered'])->count();
        $totalPOValue       = PurchaseOrder::sum('total_amount');

        // --- Procurement: Low Stock ---
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

        // --- HR: Employee ---
        $totalEmployees     = Employee::count();
        $activeEmployees    = Employee::where('status', 'active')->count();

        // --- HR: Attendance ---
        $todayAttendance    = Attendance::whereDate('date', today())->count();
        $totalLeavePending  = LeaveRequest::where('status', 'pending')->count();

        // --- HR: Equipment ---
        $totalEquipment     = Equipment::count();
        $activeMaintenance  = EquipmentMaintenance::where('status', 'pending')->count();

        // --- HR: Safety & Training ---
        $openIncidents      = IncidentReport::whereIn('status', ['open', 'under_investigation'])->count();
        $totalTraining      = TrainingRecord::count();
        $expiredCert        = Certification::where('status', 'expired')->count();

        // --- Finance: Invoices & Bills ---
        $totalInvoices      = Invoice::count();
        $unpaidInvoices     = Invoice::whereIn('status', ['draft', 'sent', 'overdue'])->count();
        $totalInvoiceAmount = Invoice::sum('total_amount');
        $totalBills         = Bill::count();
        $unpaidBills        = Bill::whereIn('status', ['draft', 'sent', 'overdue'])->count();

        // --- Approvals ---
        $pendingApprovals   = Approval::where('status', 'pending')->count();

        // --- Charts ---
        $tasksByPriority = Task::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority');

        $projectsByStatus = Project::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // --- Recent Records ---
        $recentProjects = Project::with('creator')->latest()->take(5)->get();
        $recentPOs      = PurchaseOrder::with('vendor')->latest()->take(5)->get();
        $recentIncidents = IncidentReport::with('employee')->latest()->take(5)->get();
        $recentEmployees = Employee::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProjects', 'activeProjects', 'planningProjects', 'completedProjects',
            'totalBudget', 'totalSites',
            'totalTasks', 'openTasks', 'inProgressTasks', 'criticalTasks',
            'totalVendors', 'approvedVendors', 'pendingVendors',
            'totalPOs', 'pendingPOs', 'totalPOValue',
            'lowStockItems',
            'totalEmployees', 'activeEmployees',
            'todayAttendance', 'totalLeavePending',
            'totalEquipment', 'activeMaintenance',
            'openIncidents', 'totalTraining', 'expiredCert',
            'totalInvoices', 'unpaidInvoices', 'totalInvoiceAmount',
            'totalBills', 'unpaidBills',
            'pendingApprovals',
            'tasksByPriority', 'projectsByStatus',
            'recentProjects', 'recentPOs', 'recentIncidents', 'recentEmployees'
        ));
    }
}
