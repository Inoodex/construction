@extends('admin.reports.financial.pdf._master')
@section('title', 'Resource Utilisation Report')
@section('content')
<table class="stats">
    <tr>
        <td><div class="value" style="color:#4361ee">{{ $summary['total_resources'] }}</div><div class="label">Total Resources</div></td>
        <td><div class="value" style="color:#e2a03f">{{ number_format($summary['total_labour_cost'], 2) }}</div><div class="label">Labour Cost</div></td>
        <td><div class="value" style="color:#2196f3">{{ number_format($summary['total_equipment_cost'], 2) }}</div><div class="label">Equipment Cost</div></td>
        <td><div class="value" style="color:#805dca">{{ number_format($summary['total_material_cost'], 2) }}</div><div class="label">Material Cost</div></td>
        <td><div class="value" style="color:#00ab55">{{ number_format($summary['grand_total'], 2) }}</div><div class="label">Grand Total</div></td>
    </tr>
</table>

<h5>By Resource Type</h5>
<table class="data">
    <thead>
        <tr><th>Type</th><th class="text-center">Entries</th><th class="text-right">Total Qty</th><th class="text-right">Total Cost</th></tr>
    </thead>
    <tbody>
        @forelse($byType as $t)
        <tr>
            <td class="font-semibold capitalize">{{ $t['type'] }}</td>
            <td class="text-center">{{ $t['count'] }}</td>
            <td class="text-right">{{ number_format($t['total_qty'], 2) }}</td>
            <td class="text-right">{{ number_format($t['total_cost'], 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center" style="color:#888ea8">No resource data</td></tr>
        @endforelse
    </tbody>
</table>

<h5>By Project</h5>
<table class="data">
    <thead>
        <tr>
            <th>Project</th>
            <th class="text-right">Labour Cost</th>
            <th class="text-right">Labour Qty</th>
            <th class="text-right">Equipment Cost</th>
            <th class="text-right">Equipment Qty</th>
            <th class="text-right">Material Cost</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($byProject as $bp)
        <tr>
            <td class="font-semibold">{{ $bp['project_name'] }}</td>
            <td class="text-right">{{ number_format($bp['labour_cost'], 2) }}</td>
            <td class="text-right">{{ number_format($bp['labour_qty'], 2) }}</td>
            <td class="text-right">{{ number_format($bp['equipment_cost'], 2) }}</td>
            <td class="text-right">{{ number_format($bp['equipment_qty'], 2) }}</td>
            <td class="text-right">{{ number_format($bp['material_cost'], 2) }}</td>
            <td class="text-right font-semibold">৳{{ number_format($bp['total_cost'], 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center" style="color:#888ea8">No data</td></tr>
        @endforelse
    </tbody>
</table>
@endsection