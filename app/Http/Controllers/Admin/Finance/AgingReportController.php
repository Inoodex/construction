<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Bill;
use App\Models\Project;
use Illuminate\Http\Request;

class AgingReportController extends Controller
{
    public function arAging(Request $request)
    {
        $query = Invoice::with('project')->whereIn('status', ['sent', 'partially_paid', 'overdue']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $invoices = $query->get();
        $now = now();

        $buckets = [
            'current'       => ['label' => 'Current',       'invoices' => collect(), 'total' => 0],
            'days1_30'      => ['label' => '1-30 Days',     'invoices' => collect(), 'total' => 0],
            'days31_60'     => ['label' => '31-60 Days',    'invoices' => collect(), 'total' => 0],
            'days61_90'     => ['label' => '61-90 Days',    'invoices' => collect(), 'total' => 0],
            'days90_plus'   => ['label' => '90+ Days',      'invoices' => collect(), 'total' => 0],
        ];

        foreach ($invoices as $inv) {
            $due = $inv->due_amount;
            $daysOverdue = $inv->due_date ? max(0, $now->diffInDays($inv->due_date, false)) : 0;

            $key = $daysOverdue <= 0 ? 'current'
                : ($daysOverdue <= 30 ? 'days1_30'
                : ($daysOverdue <= 60 ? 'days31_60'
                : ($daysOverdue <= 90 ? 'days61_90'
                : 'days90_plus')));

            $buckets[$key]['invoices']->push($inv);
            $buckets[$key]['total'] += $due;
        }

        $grandTotal = array_sum(array_column($buckets, 'total'));
        $projects = Project::all();

        return view('admin.finance.aging.ar-aging', compact('buckets', 'grandTotal', 'projects'));
    }

    public function apAging(Request $request)
    {
        $query = Bill::with('project', 'vendor')->whereIn('status', ['approved', 'overdue']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $bills = $query->get();
        $now = now();

        $buckets = [
            'current'       => ['label' => 'Current',       'bills' => collect(), 'total' => 0],
            'days1_30'      => ['label' => '1-30 Days',     'bills' => collect(), 'total' => 0],
            'days31_60'     => ['label' => '31-60 Days',    'bills' => collect(), 'total' => 0],
            'days61_90'     => ['label' => '61-90 Days',    'bills' => collect(), 'total' => 0],
            'days90_plus'   => ['label' => '90+ Days',      'bills' => collect(), 'total' => 0],
        ];

        foreach ($bills as $bill) {
            $due = $bill->due_amount;
            $daysOverdue = $bill->due_date ? max(0, $now->diffInDays($bill->due_date, false)) : 0;

            $key = $daysOverdue <= 0 ? 'current'
                : ($daysOverdue <= 30 ? 'days1_30'
                : ($daysOverdue <= 60 ? 'days31_60'
                : ($daysOverdue <= 90 ? 'days61_90'
                : 'days90_plus')));

            $buckets[$key]['bills']->push($bill);
            $buckets[$key]['total'] += $due;
        }

        $grandTotal = array_sum(array_column($buckets, 'total'));
        $projects = Project::all();
        $vendors = \App\Models\Vendor::all();

        return view('admin.finance.aging.ap-aging', compact('buckets', 'grandTotal', 'projects', 'vendors'));
    }
}
