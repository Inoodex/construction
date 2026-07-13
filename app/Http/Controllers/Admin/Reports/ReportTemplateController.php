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
        $projects = \App\Models\Project::orderBy('name')->get();
        return view('admin.reports.report-templates.create', compact('reportTypes', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_type' => 'required|string|in:financial,inventory,progress,safety',
            'data_source' => 'required|string',
            'columns' => 'required|array|min:1',
            'columns.*' => 'string',
            'chart_type' => 'nullable|in:none,bar,line,pie,area',
            'group_by' => 'nullable|string',
            'filter_project_id' => 'nullable|exists:projects,id',
            'filter_date_from' => 'nullable|date',
            'filter_date_to' => 'nullable|date|after_or_equal:filter_date_from',
        ]);

        $configuration = [
            'data_source' => $validated['data_source'],
            'columns' => $validated['columns'],
            'chart_type' => $validated['chart_type'] ?? 'none',
            'group_by' => $validated['group_by'] ?? null,
            'filters' => array_filter([
                'project_id' => $validated['filter_project_id'] ?? null,
                'date_from' => $validated['filter_date_from'] ?? null,
                'date_to' => $validated['filter_date_to'] ?? null,
            ]),
        ];

        ReportTemplate::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'report_type' => $validated['report_type'],
            'configuration' => $configuration,
            'created_by' => Auth::id(),
        ]);

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
        $projects = \App\Models\Project::orderBy('name')->get();
        return view('admin.reports.report-templates.edit', compact('reportTemplate', 'reportTypes', 'projects'));
    }

    public function update(Request $request, ReportTemplate $reportTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'report_type' => 'required|string|in:financial,inventory,progress,safety',
            'data_source' => 'required|string',
            'columns' => 'required|array|min:1',
            'columns.*' => 'string',
            'chart_type' => 'nullable|in:none,bar,line,pie,area',
            'group_by' => 'nullable|string',
            'filter_project_id' => 'nullable|exists:projects,id',
            'filter_date_from' => 'nullable|date',
            'filter_date_to' => 'nullable|date|after_or_equal:filter_date_from',
        ]);

        $configuration = [
            'data_source' => $validated['data_source'],
            'columns' => $validated['columns'],
            'chart_type' => $validated['chart_type'] ?? 'none',
            'group_by' => $validated['group_by'] ?? null,
            'filters' => array_filter([
                'project_id' => $validated['filter_project_id'] ?? null,
                'date_from' => $validated['filter_date_from'] ?? null,
                'date_to' => $validated['filter_date_to'] ?? null,
            ]),
        ];

        $reportTemplate->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'report_type' => $validated['report_type'],
            'configuration' => $configuration,
        ]);

        return redirect()->route('admin.reports.report-templates.index')
            ->with('success', 'Report template updated.');
    }

    public function preview(ReportTemplate $reportTemplate)
    {
        $config = $reportTemplate->configuration ?? [];
        $dataSource = $config['data_source'] ?? 'projects';
        $columns = $config['columns'] ?? [];
        $filters = $config['filters'] ?? [];
        $chartType = $config['chart_type'] ?? 'none';

        $data = $this->fetchPreviewData($dataSource, $filters, 20);

        return view('admin.reports.report-templates.preview', compact('reportTemplate', 'data', 'columns', 'dataSource', 'chartType'));
    }

    private function fetchPreviewData(string $dataSource, array $filters, int $limit): \Illuminate\Support\Collection
    {
        switch ($dataSource) {
            case 'projects':
                $query = \App\Models\Project::query();
                if (!empty($filters['project_id'])) {
                    $query->where('id', $filters['project_id']);
                }
                return $query->select('id', 'name', 'status', 'start_date', 'end_date', 'budget')->limit($limit)->get();

            case 'invoices':
                $query = \App\Models\Invoice::with('project');
                if (!empty($filters['project_id'])) {
                    $query->where('project_id', $filters['project_id']);
                }
                if (!empty($filters['date_from'])) {
                    $query->where('invoice_date', '>=', $filters['date_from']);
                }
                if (!empty($filters['date_to'])) {
                    $query->where('invoice_date', '<=', $filters['date_to']);
                }
                return $query->select('id', 'invoice_number', 'project_id', 'amount', 'status', 'invoice_date')->limit($limit)->get();

            case 'budgets':
                $query = \App\Models\Budget::with('project');
                if (!empty($filters['project_id'])) {
                    $query->where('project_id', $filters['project_id']);
                }
                return $query->select('id', 'project_id', 'cost_code', 'description', 'budgeted_amount', 'actual_amount')->limit($limit)->get();

            case 'expenses':
                $query = \App\Models\Expense::with('project');
                if (!empty($filters['project_id'])) {
                    $query->where('project_id', $filters['project_id']);
                }
                return $query->select('id', 'project_id', 'category', 'amount', 'expense_date', 'description')->limit($limit)->get();

            case 'stocks':
                return \App\Models\Stock::with('warehouse')->select('id', 'item_name', 'quantity', 'unit', 'warehouse_id')->limit($limit)->get();

            case 'employees':
                return \App\Models\Employee::select('id', 'first_name', 'last_name', 'designation', 'department', 'status')->limit($limit)->get();

            case 'hse_incidents':
                $query = \App\Models\IncidentReport::query();
                if (!empty($filters['date_from'])) {
                    $query->where('incident_date', '>=', $filters['date_from']);
                }
                return $query->select('id', 'incident_date', 'incident_type', 'severity', 'status', 'location')->limit($limit)->get();

            default:
                return collect();
        }
    }

    public function destroy(ReportTemplate $reportTemplate)
    {
        $reportTemplate->delete();
        return redirect()->route('admin.reports.report-templates.index')
            ->with('success', 'Report template deleted.');
    }
}
