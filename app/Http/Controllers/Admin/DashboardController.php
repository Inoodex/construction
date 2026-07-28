<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Attendance;
use App\Models\Bill;
use App\Models\Certification;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentMaintenance;
use App\Models\IncidentReport;
use App\Models\Invoice;
use App\Models\LeaveRequest;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\Site;
use App\Models\Stock;
use App\Models\Task;
use App\Models\TrainingRecord;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isClient = $user->hasRole('client');
        $clientId = $isClient ? $user->client_id : null;

        $projectIds = $clientId ? Project::where('client_id', $clientId)->pluck('id') : null;

        $projectBase = Project::when($clientId, fn ($q) => $q->where('client_id', $clientId));

        $totalProjects = (clone $projectBase)->count();
        $activeProjects = (clone $projectBase)->where('status', 'active')->count();
        $planningProjects = (clone $projectBase)->where('status', 'planning')->count();
        $completedProjects = (clone $projectBase)->where('status', 'completed')->count();
        $totalBudget = (clone $projectBase)->sum('budget');
        $totalSites = $clientId ? Site::whereIn('project_id', $projectIds)->count() : Site::count();

        $taskBase = Task::when($projectIds, fn ($q) => $q->whereIn('project_id', $projectIds));
        $totalTasks = (clone $taskBase)->count();
        $openTasks = (clone $taskBase)->where('status', 'open')->count();
        $inProgressTasks = (clone $taskBase)->where('status', 'in_progress')->count();
        $criticalTasks = (clone $taskBase)->where('priority', 'critical')->whereIn('status', ['open', 'in_progress'])->count();

        $vendorBase = Vendor::query();
        $totalVendors = (clone $vendorBase)->count();
        $approvedVendors = (clone $vendorBase)->where('status', 'approved')->count();
        $pendingVendors = (clone $vendorBase)->where('status', 'pending')->count();

        $poBase = PurchaseOrder::when($projectIds, fn ($q) => $q->whereIn('project_id', $projectIds));
        $totalPOs = (clone $poBase)->count();
        $pendingPOs = (clone $poBase)->whereIn('status', ['draft', 'ordered'])->count();
        $totalPOValue = (clone $poBase)->sum('total_amount');

        $lowStockItems = Stock::with(['material', 'warehouse', 'site'])
            ->when($projectIds, fn ($q) => $q->whereHas('site', fn ($q2) => $q2->whereIn('project_id', $projectIds)))
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

        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $todayAttendance = Attendance::whereDate('date', today())->count();
        $totalLeavePending = LeaveRequest::where('status', 'pending')->count();
        $totalEquipment = Equipment::count();
        $activeMaintenance = EquipmentMaintenance::where('status', 'pending')->count();
        $openIncidents = IncidentReport::whereIn('status', ['open', 'under_investigation'])->count();
        $totalTraining = TrainingRecord::count();
        $expiredCert = Certification::where('status', 'expired')->count();

        $invoiceBase = Invoice::when($projectIds, fn ($q) => $q->whereIn('project_id', $projectIds));
        $totalInvoices = (clone $invoiceBase)->count();
        $unpaidInvoices = (clone $invoiceBase)->whereIn('status', ['draft', 'sent', 'overdue', 'partially_paid'])->count();
        $totalInvoiceAmount = (clone $invoiceBase)->sum('total_amount');
        $totalARDue = (clone $invoiceBase)->sum('due_amount');

        $billBase = Bill::when($projectIds, fn ($q) => $q->whereIn('project_id', $projectIds));
        $totalBills = (clone $billBase)->count();
        $unpaidBills = (clone $billBase)->whereIn('status', ['draft', 'sent', 'overdue', 'partially_paid'])->count();
        $totalAPDue = (clone $billBase)->sum('due_amount');

        $pendingApprovals = Approval::where('status', 'pending')->count();

        $tasksByPriority = (clone $taskBase)->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority');

        $projectsByStatus = (clone $projectBase)->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentProjects = (clone $projectBase)->with('creator')->latest()->take(5)->get();
        $recentPOs = (clone $poBase)->with('vendor')->latest()->take(5)->get();
        $recentIncidents = IncidentReport::with('employee')->latest()->take(5)->get();
        $recentEmployees = Employee::latest()->take(5)->get();

        $monthlyRevenue = Invoice::when($projectIds, fn ($q) => $q->whereIn('project_id', $projectIds))
            ->where('issue_date', '>=', now()->subMonths(5)->startOfMonth())
            ->select(DB::raw('DATE_FORMAT(issue_date, "%b %Y") as month_label'), DB::raw('SUM(total_amount) as total'), DB::raw('MIN(issue_date) as min_date'))
            ->groupBy('month_label')
            ->orderBy('min_date', 'asc')
            ->pluck('total', 'month_label');

        $monthlyExpenses = Bill::when($projectIds, fn ($q) => $q->whereIn('project_id', $projectIds))
            ->where('bill_date', '>=', now()->subMonths(5)->startOfMonth())
            ->select(DB::raw('DATE_FORMAT(bill_date, "%b %Y") as month_label'), DB::raw('SUM(total_amount) as total'), DB::raw('MIN(bill_date) as min_date'))
            ->groupBy('month_label')
            ->orderBy('min_date', 'asc')
            ->pluck('total', 'month_label');

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
            'totalInvoices', 'unpaidInvoices', 'totalInvoiceAmount', 'totalARDue',
            'totalBills', 'unpaidBills', 'totalAPDue',
            'pendingApprovals',
            'tasksByPriority', 'projectsByStatus',
            'recentProjects', 'recentPOs', 'recentIncidents', 'recentEmployees',
            'monthlyRevenue', 'monthlyExpenses'
        ));
    }
}
