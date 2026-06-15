<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Services\FinancialReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FinancialReportController extends Controller
{
    protected $reportService;

    public function __construct(FinancialReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    protected function getReportData(string $report, Request $request): array
    {
        return match ($report) {
            'budget-vs-actual' => [
                'data' => $this->reportService->budgetVsActual($request->project_id, $request->from, $request->to),
                'view' => 'admin.reports.financial.budget-vs-actual',
                'title' => 'Budget vs Actual Report',
                'filename' => 'budget-vs-actual',
            ],
            'project-cost-summary' => [
                'data' => $this->reportService->projectCostSummary($request->project_id),
                'view' => 'admin.reports.financial.project-cost-summary',
                'title' => 'Project Cost Summary',
                'filename' => 'project-cost-summary',
            ],
            'procurement-spend' => [
                'data' => $this->reportService->procurementSpend($request->project_id, $request->from, $request->to),
                'view' => 'admin.reports.financial.procurement-spend',
                'title' => 'Procurement Spend Report',
                'filename' => 'procurement-spend',
            ],
            'invoice-status' => [
                'data' => $this->reportService->invoiceStatus($request->project_id, $request->status),
                'view' => 'admin.reports.financial.invoice-status',
                'title' => 'Invoice Status Report',
                'filename' => 'invoice-status',
            ],
            'cash-flow' => [
                'data' => $this->reportService->cashFlow($request->project_id, $request->months ?? 12),
                'view' => 'admin.reports.financial.cash-flow',
                'title' => 'Cash Flow Report',
                'filename' => 'cash-flow',
            ],
            'retention-tracker' => [
                'data' => $this->reportService->retentionTracker($request->project_id),
                'view' => 'admin.reports.financial.retention-tracker',
                'title' => 'Retention Tracker Report',
                'filename' => 'retention-tracker',
            ],
            'progress-schedule' => [
                'data' => $this->reportService->progressSchedule($request->project_id),
                'view' => 'admin.reports.financial.progress-schedule',
                'title' => 'Progress Schedule Report',
                'filename' => 'progress-schedule',
            ],
            'resource-utilisation' => [
                'data' => $this->reportService->resourceUtilisation($request->project_id, $request->resource_type),
                'view' => 'admin.reports.financial.resource-utilisation',
                'title' => 'Labour & Equipment Utilisation Report',
                'filename' => 'resource-utilisation',
            ],
            default => abort(404),
        };
    }

    public function exportPdf(Request $request, string $report)
    {
        $info = $this->getReportData($report, $request);
        $data = $info['data'];
        $data['projects'] = $this->reportService->getProjects();

        $pdf = Pdf::loadView($info['view'], $data)
            ->setPaper('a4', 'landscape')
            ->setOption('defaultFont', 'sans-serif');

        return $pdf->download($info['filename'] . '-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request, string $report)
    {
        $info = $this->getReportData($report, $request);
        $data = $info['data'];
        $data['projects'] = $this->reportService->getProjects();

        $rows = $this->buildExcelRows($report, $data);
        $headings = $rows['headings'] ?? [];
        $exportData = $rows['data'] ?? [];

        return Excel::download(
            new ReportExport($exportData, $headings, $info['title']),
            $info['filename'] . '-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    protected function buildExcelRows(string $report, array $data): array
    {
        return match ($report) {
            'budget-vs-actual' => [
                'headings' => ['Cost Code', 'Budgeted', 'Actual', 'Variance', 'Variance %', 'Items'],
                'data' => collect($data['costCodes'] ?? [])->map(fn($cc, $code) => [
                    $code,
                    $cc['budgeted'],
                    $cc['actual'],
                    $cc['variance'],
                    $cc['variance_pct'] . '%',
                    $cc['count'],
                ])->values()->toArray(),
            ],
            'project-cost-summary' => [
                'headings' => ['Project', 'Budget', 'Actual Cost', 'PO Spend', 'Resource Cost', 'Total Spend', 'Remaining', 'Utilization %'],
                'data' => collect($data['summaries'] ?? [])->map(fn($s) => [
                    $s['project']->name,
                    $s['budget'],
                    $s['actual_cost'],
                    $s['po_total'],
                    $s['resource_cost'],
                    $s['total_spend'],
                    $s['remaining'],
                    $s['utilization_pct'] . '%',
                ])->toArray(),
            ],
            'procurement-spend' => [
                'headings' => ['PO #', 'Vendor', 'Project', 'Amount', 'Date', 'Status'],
                'data' => collect($data['orders'] ?? [])->map(fn($po) => [
                    $po->po_number,
                    $po->vendor->name ?? 'N/A',
                    $po->project->name ?? 'N/A',
                    $po->total_amount,
                    $po->order_date->format('Y-m-d'),
                    $po->status,
                ])->toArray(),
            ],
            'invoice-status' => [
                'headings' => ['Invoice #', 'Project', 'Total', 'Paid', 'Due', 'Retention', 'Due Date', 'Status'],
                'data' => collect($data['invoices'] ?? [])->map(fn($inv) => [
                    $inv->invoice_number,
                    $inv->project->name ?? 'N/A',
                    $inv->total_amount,
                    $inv->paid_amount,
                    $inv->due_amount,
                    $inv->retention_amount,
                    $inv->due_date->format('Y-m-d'),
                    $inv->status,
                ])->toArray(),
            ],
            'cash-flow' => [
                'headings' => ['Month', 'Inflow', 'Outflow', 'Net'],
                'data' => collect($data['allMonths'] ?? [])->map(fn($m) => [
                    \Carbon\Carbon::createFromFormat('Y-m', $m['month'])->format('M Y'),
                    $m['inflow'],
                    $m['outflow'],
                    $m['net'],
                ])->values()->toArray(),
            ],
            'retention-tracker' => [
                'headings' => ['Invoice #', 'Project', 'Total', 'Retention', 'Paid', 'Due', 'Status'],
                'data' => collect($data['invoices'] ?? [])->map(fn($inv) => [
                    $inv->invoice_number,
                    $inv->project->name ?? 'N/A',
                    $inv->total_amount,
                    $inv->retention_amount,
                    $inv->paid_amount,
                    $inv->due_amount,
                    $inv->status,
                ])->toArray(),
            ],
            'resource-utilisation' => [
                'headings' => ['Project', 'Labour Cost', 'Labour Qty', 'Equipment Cost', 'Equipment Qty', 'Material Cost', 'Total'],
                'data' => collect($data['byProject'] ?? [])->map(fn($bp) => [
                    $bp['project_name'],
                    $bp['labour_cost'],
                    $bp['labour_qty'],
                    $bp['equipment_cost'],
                    $bp['equipment_qty'],
                    $bp['material_cost'],
                    $bp['total_cost'],
                ])->toArray(),
            ],
            'progress-schedule' => [
                'headings' => ['Report data not available in tabular format. Please use PDF export.'],
                'data' => [],
            ],
            default => ['headings' => [], 'data' => []],
        };
    }

    public function budgetVsActual(Request $request)
    {
        $data = $this->reportService->budgetVsActual(
            $request->project_id,
            $request->from,
            $request->to
        );
        $data['projects'] = $this->reportService->getProjects();
        return view('admin.reports.financial.budget-vs-actual', $data);
    }

    public function projectCostSummary(Request $request)
    {
        $data = $this->reportService->projectCostSummary($request->project_id);
        $data['projects'] = $this->reportService->getProjects();
        return view('admin.reports.financial.project-cost-summary', $data);
    }

    public function procurementSpend(Request $request)
    {
        $data = $this->reportService->procurementSpend(
            $request->project_id,
            $request->from,
            $request->to
        );
        $data['projects'] = $this->reportService->getProjects();
        return view('admin.reports.financial.procurement-spend', $data);
    }

    public function invoiceStatus(Request $request)
    {
        $data = $this->reportService->invoiceStatus(
            $request->project_id,
            $request->status
        );
        $data['projects'] = $this->reportService->getProjects();
        return view('admin.reports.financial.invoice-status', $data);
    }

    public function cashFlow(Request $request)
    {
        $data = $this->reportService->cashFlow(
            $request->project_id,
            $request->months ?? 12
        );
        $data['projects'] = $this->reportService->getProjects();
        return view('admin.reports.financial.cash-flow', $data);
    }

    public function retentionTracker(Request $request)
    {
        $data = $this->reportService->retentionTracker($request->project_id);
        $data['projects'] = $this->reportService->getProjects();
        return view('admin.reports.financial.retention-tracker', $data);
    }

    public function progressSchedule(Request $request)
    {
        $data = $this->reportService->progressSchedule($request->project_id);
        $data['projects'] = $this->reportService->getProjects();
        return view('admin.reports.financial.progress-schedule', $data);
    }

    public function resourceUtilisation(Request $request)
    {
        $data = $this->reportService->resourceUtilisation(
            $request->project_id,
            $request->resource_type
        );
        $data['projects'] = $this->reportService->getProjects();
        return view('admin.reports.financial.resource-utilisation', $data);
    }
}
