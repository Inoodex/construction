<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryItem;
use App\Models\Project;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Payment;
use App\Models\ProjectResource;
use App\Models\Task;
use App\Models\Milestone;
use App\Models\Phase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialReportService
{
    public function budgetVsActual($projectId = null, $from = null, $to = null)
    {
        $query = Budget::with('project')
            ->select('budgets.*')
            ->join('projects', 'projects.id', '=', 'budgets.project_id');

        if ($projectId) {
            $query->where('budgets.project_id', $projectId);
        }
        if ($from) {
            $query->whereDate('budgets.created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('budgets.created_at', '<=', $to);
        }

        $budgets = $query->get();

        $totalBudgeted = $budgets->sum('budgeted_amount');
        $totalActual = $budgets->sum('actual_cost');
        $variance = $totalBudgeted - $totalActual;
        $variancePct = $totalBudgeted > 0 ? round(($variance / $totalBudgeted) * 100, 2) : 0;

        $costCodes = $budgets->groupBy('cost_code')->map(function ($items) {
            $b = $items->sum('budgeted_amount');
            $a = $items->sum('actual_cost');
            return [
                'budgeted' => $b,
                'actual' => $a,
                'variance' => $b - $a,
                'variance_pct' => $b > 0 ? round((($b - $a) / $b) * 100, 2) : 0,
                'count' => $items->count(),
            ];
        });

        return compact('budgets', 'totalBudgeted', 'totalActual', 'variance', 'variancePct', 'costCodes');
    }

    public function projectCostSummary($projectId = null)
    {
        $projects = Project::with(['budgets', 'purchaseOrders', 'invoices', 'resources'])
            ->when($projectId, fn($q) => $q->where('id', $projectId))
            ->get();

        $summaries = $projects->map(function ($project) {
            $budgetAmount = $project->budgets->sum('budgeted_amount');
            $actualCost = $project->budgets->sum('actual_cost');
            $poTotal = $project->purchaseOrders->sum('total_amount');
            $invoicedTotal = $project->invoices->sum('total_amount');
            $paidTotal = $project->invoices->sum('paid_amount');
            $dueTotal = $project->invoices->sum('due_amount');
            $resourceCost = $project->resources->sum('total_cost');

            return [
                'project' => $project,
                'budget' => $project->budget,
                'budget_detail' => $budgetAmount,
                'actual_cost' => $actualCost,
                'po_total' => $poTotal,
                'invoiced' => $invoicedTotal,
                'paid' => $paidTotal,
                'due' => $dueTotal,
                'resource_cost' => $resourceCost,
                'total_spend' => $actualCost + $poTotal + $resourceCost,
                'remaining' => $project->budget - ($actualCost + $poTotal + $resourceCost),
                'utilization_pct' => $project->budget > 0
                    ? round((($actualCost + $poTotal + $resourceCost) / $project->budget) * 100, 2)
                    : 0,
            ];
        });

        $totals = [
            'budget' => $summaries->sum('budget'),
            'actual_cost' => $summaries->sum('actual_cost'),
            'po_total' => $summaries->sum('po_total'),
            'invoiced' => $summaries->sum('invoiced'),
            'paid' => $summaries->sum('paid'),
            'due' => $summaries->sum('due'),
            'total_spend' => $summaries->sum('total_spend'),
        ];

        return compact('summaries', 'totals');
    }

    public function procurementSpend($projectId = null, $from = null, $to = null)
    {
        $query = PurchaseOrder::with(['vendor', 'project', 'items.material'])
            ->select('purchase_orders.*');

        if ($projectId) {
            $query->where('project_id', $projectId);
        }
        if ($from) {
            $query->whereDate('order_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('order_date', '<=', $to);
        }

        $orders = $query->get();

        $byVendor = $orders->groupBy('vendor_id')->map(function ($items) {
            $vendor = $items->first()->vendor;
            return [
                'vendor_name' => $vendor->name ?? 'Unknown',
                'count' => $items->count(),
                'total' => $items->sum('total_amount'),
            ];
        })->sortByDesc('total');

        $byProject = $orders->groupBy(fn($po) => $po->project?->id ?? 0)->map(function ($items) {
            $project = $items->first()->project;
            return [
                'project_name' => $project->name ?? 'Unknown',
                'count' => $items->count(),
                'total' => $items->sum('total_amount'),
            ];
        })->sortByDesc('total');

        $totalSpend = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        return compact('orders', 'byVendor', 'byProject', 'totalSpend', 'totalOrders');
    }

    public function invoiceStatus($projectId = null, $status = null)
    {
        $query = Invoice::with(['project', 'payments'])
            ->select('invoices.*');

        if ($projectId) {
            $query->where('project_id', $projectId);
        }
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $invoices = $query->get();

        $summary = [
            'total_invoiced' => $invoices->sum('total_amount'),
            'total_paid' => $invoices->sum('paid_amount'),
            'total_due' => $invoices->sum('due_amount'),
            'total_retention' => $invoices->sum('retention_amount'),
            'overdue_count' => $invoices->where('status', 'overdue')->count(),
            'draft_count' => $invoices->where('status', 'draft')->count(),
            'sent_count' => $invoices->whereIn('status', ['sent', 'partially_paid'])->count(),
            'paid_count' => $invoices->where('status', 'paid')->count(),
        ];

        $byProject = $invoices->groupBy('project_id')->map(function ($items) {
            $project = $items->first()->project;
            return [
                'project_name' => $project->name ?? 'Unknown',
                'count' => $items->count(),
                'total' => $items->sum('total_amount'),
                'paid' => $items->sum('paid_amount'),
                'due' => $items->sum('due_amount'),
            ];
        });

        return compact('invoices', 'summary', 'byProject');
    }

    public function cashFlow($projectId = null, $months = 12)
    {
        $startDate = now()->subMonths(3)->startOfMonth();
        $endDate = $startDate->copy()->addMonths($months);

        // Find Cash & Bank account(s)
        $cashAccounts = ChartOfAccount::where('account_code', 'LIKE', '1-1010%')
            ->orWhere('name', 'LIKE', '%Cash%')
            ->orWhere('name', 'LIKE', '%Bank%')
            ->pluck('id');

        $jeQuery = JournalEntryItem::select(
            DB::raw("DATE_FORMAT(je.date, '%Y-%m') as month"),
            DB::raw('SUM(jei.debit_amount) as total_debit'),
            DB::raw('SUM(jei.credit_amount) as total_credit')
        )
            ->from('journal_entry_items as jei')
            ->join('journal_entries as je', 'je.id', '=', 'jei.journal_entry_id')
            ->whereIn('jei.account_id', $cashAccounts)
            ->where('je.status', 'posted')
            ->where('je.date', '>=', $startDate)
            ->where('je.date', '<', $endDate)
            ->groupBy(DB::raw("DATE_FORMAT(je.date, '%Y-%m')"))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $allMonths = collect();
        for ($i = 0; $i < $months; $i++) {
            $m = $startDate->copy()->addMonths($i)->format('Y-m');
            $row = $jeQuery[$m] ?? null;
            $allMonths[$m] = [
                'month' => $m,
                'inflow' => (float) ($row->total_debit ?? 0),
                'outflow' => (float) ($row->total_credit ?? 0),
                'net' => (float) (($row->total_debit ?? 0) - ($row->total_credit ?? 0)),
            ];
        }

        $totalInflow = $allMonths->sum('inflow');
        $totalOutflow = $allMonths->sum('outflow');
        $netCashFlow = $totalInflow - $totalOutflow;

        return compact('allMonths', 'totalInflow', 'totalOutflow', 'netCashFlow');
    }

    public function retentionTracker($projectId = null)
    {
        $query = Invoice::with('project')
            ->select('invoices.*')
            ->where('retention_amount', '>', 0);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $invoices = $query->get();

        $byProject = $invoices->groupBy('project_id')->map(function ($items) {
            $project = $items->first()->project;
            $totalRetention = $items->sum('retention_amount');
            $released = $items->where('status', 'paid')->sum('retention_amount');
            $pending = $totalRetention - $released;

            return [
                'project_name' => $project->name ?? 'Unknown',
                'invoice_count' => $items->count(),
                'total_retention' => $totalRetention,
                'released' => $released,
                'pending' => $pending,
                'release_pct' => $totalRetention > 0 ? round(($released / $totalRetention) * 100, 1) : 0,
            ];
        });

        $totalRetention = $invoices->sum('retention_amount');
        $totalReleased = $invoices->where('status', 'paid')->sum('retention_amount');
        $totalPending = $totalRetention - $totalReleased;

        return compact('invoices', 'byProject', 'totalRetention', 'totalReleased', 'totalPending');
    }

    public function progressSchedule($projectId = null)
    {
        $projects = Project::with('phases')
            ->when($projectId, fn($q) => $q->where('id', $projectId))
            ->get();

        $allSeries = [];

        foreach ($projects as $project) {
            $tasks = Task::where('project_id', $project->id)
                ->whereNotNull('start_date')
                ->whereNotNull('end_date')
                ->get();

            if ($tasks->isEmpty()) continue;

            $earliest = $tasks->min('start_date');
            $latest = $tasks->max('end_date');
            $today = now()->startOfDay();
            $end = max($latest, $today);

            $totalWeight = 0;
            $taskWeights = [];
            foreach ($tasks as $task) {
                $duration = Carbon::parse($task->start_date)->diffInDays(Carbon::parse($task->end_date)) + 1;
                $duration = max($duration, 1);
                $weight = $duration;
                $totalWeight += $weight;
                $taskWeights[] = compact('task', 'weight', 'duration');
            }

            $plannedPoints = [];
            $actualPoints = [];
            $milestonePoints = [];
            $labels = [];

            $date = Carbon::parse($earliest);
            $endDate = Carbon::parse($end);

            while ($date->lte($endDate)) {
                $d = $date->format('Y-m-d');
                $labels[] = $d;

                $plannedPct = 0;
                $actualPct = 0;

                foreach ($taskWeights as $tw) {
                    $t = $tw['task'];
                    $tStart = Carbon::parse($t->start_date);
                    $tEnd = Carbon::parse($t->end_date);
                    $w = $tw['weight'];

                    // Planned: linear from 0% at start to 100% at end
                    if ($date->lt($tStart)) {
                        $plannedTaskPct = 0;
                    } elseif ($date->gte($tEnd)) {
                        $plannedTaskPct = 100;
                    } else {
                        $elapsed = $tStart->diffInDays($date);
                        $plannedTaskPct = ($elapsed / $tw['duration']) * 100;
                    }

                    // Actual: progress_percent after start_date
                    if ($date->lt($tStart)) {
                        $actualTaskPct = 0;
                    } elseif ($date->gte($tStart) && $date->lte($today)) {
                        $actualTaskPct = (int) $t->progress_percent;
                    } else {
                        $actualTaskPct = (int) $t->progress_percent;
                    }

                    $plannedPct += ($plannedTaskPct * $w / 100);
                    $actualPct += ($actualTaskPct * $w / 100);
                }

                $plannedCumulative = $totalWeight > 0 ? ($plannedPct / $totalWeight) * 100 : 0;
                $actualCumulative = $totalWeight > 0 ? ($actualPct / $totalWeight) * 100 : 0;

                $plannedPoints[] = round($plannedCumulative, 2);
                $actualPoints[] = round($actualCumulative, 2);

                $date->addDay();
            }

            // Milestones
            $milestones = Milestone::where('project_id', $project->id)->get();
            foreach ($milestones as $ms) {
                $dateLabel = $ms->target_date ? Carbon::parse($ms->target_date)->format('Y-m-d') : null;
                if ($dateLabel && in_array($dateLabel, $labels)) {
                    $idx = array_search($dateLabel, $labels);
                    $milestonePoints[] = [
                        'date' => $dateLabel,
                        'name' => $ms->name,
                        'status' => $ms->status,
                        'y' => round($plannedPoints[$idx] ?? 0, 2),
                        'date_formatted' => Carbon::parse($dateLabel)->format('d M'),
                    ];
                }
            }

            $allSeries[] = [
                'project' => $project,
                'labels' => $labels,
                'planned' => $plannedPoints,
                'actual' => $actualPoints,
                'milestones' => $milestonePoints,
                'task_count' => $tasks->count(),
                'total_weight_days' => $totalWeight,
            ];
        }

        // Summary across all projects
        $totalTasks = Task::when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->whereNotNull('start_date')->count();
        $totalProjects = count($allSeries);
        $avgProgress = Task::when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->avg('progress_percent');

        $phases = Phase::withCount(['tasks' => function ($q) {
                $q->whereNotNull('start_date');
            }])
            ->when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->get();

        return compact('allSeries', 'totalTasks', 'totalProjects', 'avgProgress', 'phases');
    }

    public function resourceUtilisation($projectId = null, $resourceType = null)
    {
        $query = ProjectResource::with('project')
            ->select('project_resources.*');

        if ($projectId) {
            $query->where('project_id', $projectId);
        }
        if ($resourceType && $resourceType !== 'all') {
            $query->where('resource_type', $resourceType);
        }

        $resources = $query->get();

        $byType = $resources->groupBy('resource_type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'count' => $items->count(),
                'total_qty' => $items->sum('quantity'),
                'total_cost' => $items->sum('total_cost'),
            ];
        });

        $byProject = $resources->groupBy('project_id')->map(function ($items) {
            $project = $items->first()->project;
            $labour = collect($items)->where('resource_type', 'labor');
            $equipment = collect($items)->where('resource_type', 'equipment');
            $material = collect($items)->where('resource_type', 'material');
            return [
                'project_name' => $project->name ?? 'Unknown',
                'labour_cost' => $labour->sum('total_cost'),
                'labour_qty' => $labour->sum('quantity'),
                'equipment_cost' => $equipment->sum('total_cost'),
                'equipment_qty' => $equipment->sum('quantity'),
                'material_cost' => $material->sum('total_cost'),
                'total_cost' => $items->sum('total_cost'),
            ];
        });

        $summary = [
            'total_resources' => $resources->count(),
            'total_labour_cost' => $resources->where('resource_type', 'labor')->sum('total_cost'),
            'total_equipment_cost' => $resources->where('resource_type', 'equipment')->sum('total_cost'),
            'total_material_cost' => $resources->where('resource_type', 'material')->sum('total_cost'),
            'grand_total' => $resources->sum('total_cost'),
        ];

        return compact('resources', 'byType', 'byProject', 'summary');
    }

    public function getProjects()
    {
        return Project::select('id', 'name')->orderBy('name')->get();
    }
}
