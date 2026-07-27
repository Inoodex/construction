<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Analysis - {{ $rateAnalysis->ra_number }}</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: sans-serif; color: #333; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        .wrapper { position: relative; width: 100%; }
        .bg-img { position: absolute; top: 0; left: 0; width: 210mm; height: 297mm; }
        .content { position: relative; padding: 120px 25px 0 25px; box-sizing: border-box; }
        .doc-number { display: inline-block; background-color: #263a79; color: white; padding: 6px 14px; font-size: 14px; font-weight: bold; margin-bottom: 8px; }
        .status-badge { display: inline-block; padding: 4px 16px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-left: 10px; vertical-align: middle; }
        .status-draft { background-color: #fff3cd; color: #856404; border: 1px solid #ffc107; }
        .status-approved { background-color: #d4edda; color: #155724; border: 1px solid #28a745; }
        .status-revised { background-color: #fff3cd; color: #856404; border: 1px solid #ffc107; }
        .meta-table { width: 100%; margin-bottom: 15px; font-size: 11px; }
        .meta-table th { text-align: left; padding: 6px 10px; background-color: #eaecf2; color: #263a79; width: 22%; font-weight: bold; border: 1px solid #ddd; }
        .meta-table td { padding: 6px 10px; border: 1px solid #ddd; }
        .items-table { width: 100%; margin-top: 10px; font-size: 10px; }
        .items-table th { background-color: #263a79; color: white; padding: 6px 5px; text-align: left; font-weight: bold; border: 1px solid #263a79; }
        .items-table td { padding: 5px; border: 1px solid #ddd; }
        .items-table tr:nth-child(even) { background-color: #f5f7fa; }
        .summary-total td { padding: 6px 10px; border: 1px solid #263a79; font-weight: bold; color: #fff; background-color: #263a79; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .notes-box { margin-top: 20px; padding: 10px 14px; border-left: 4px solid #263a79; background-color: #eaecf2; font-size: 11px; line-height: 1.5; }
        .notes-box strong { color: #263a79; }
        .resource-type { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; text-transform: capitalize; }
        .rt-labour { background-color: #d1ecf1; color: #0c5460; }
        .rt-material { background-color: #cce5ff; color: #004085; }
        .rt-equipment { background-color: #fff3cd; color: #856404; }
        .rt-subcontract { background-color: #e2e3e5; color: #383d41; }
        .rt-overhead { background-color: #d6d8db; color: #1b1e21; }
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif
        <div class="content">
            <div style="margin-bottom:15px;">
                <div class="doc-number">{{ $rateAnalysis->ra_number }}</div>
                <span class="status-badge status-{{ $rateAnalysis->status }}">{{ $rateAnalysis->status }}</span>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $rateAnalysis->title }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $rateAnalysis->project->name ?? 'N/A' }}</td>
                    <th>Created By</th>
                    <td>{{ $rateAnalysis->creator->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td colspan="3">{{ $rateAnalysis->description ?? '—' }}</td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:14%;">Type</th>
                        <th style="width:32%;">Description</th>
                        <th style="width:8%;">Unit</th>
                        <th class="text-right" style="width:10%;">Qty</th>
                        <th class="text-right" style="width:14%;">Unit Rate</th>
                        <th class="text-right" style="width:14%;">Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rateAnalysis->items as $item)
                        <tr>
                            <td><span class="resource-type rt-{{ $item->resource_type }}">{{ $item->resource_type }}</span></td>
                            <td>{{ $item->resource_description }}</td>
                            <td>{{ $item->unit }}</td>
                            <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                            <td class="text-right">{{ number_format($item->unit_rate, 2) }}</td>
                            <td class="text-right" style="font-weight:bold;">{{ number_format($item->total_cost, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center" style="text-align:center;">No items added.</td></tr>
                    @endforelse
                    <tr class="summary-total">
                        <td colspan="5" style="text-align:right;">TOTAL RATE</td>
                        <td class="text-right">{{ number_format($rateAnalysis->total_rate, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="notes-box">
                <strong>Notes:</strong><br/>
                1. This is a system-generated rate analysis document.<br/>
                2. Total Cost = Quantity &times; Unit Rate for each resource item.<br/>
                3. Generated on {{ now()->format('d M Y H:i') }}.
            </div>
        </div>
    </div>
</body>
</html>
