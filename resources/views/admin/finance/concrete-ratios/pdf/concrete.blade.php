<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Concrete Ratio - {{ $concreteRatio->reference_no }}</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: sans-serif; color: #333; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        .wrapper { position: relative; width: 100%; }
        .bg-img { position: absolute; top: 0; left: 0; width: 210mm; height: 297mm; }
        .content { position: relative; padding: 120px 25px 0 25px; box-sizing: border-box; }
        .doc-number { display: inline-block; background-color: #263a79; color: white; padding: 6px 14px; font-size: 14px; font-weight: bold; margin-bottom: 8px; }
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
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif
        <div class="content">
            <div style="margin-bottom:15px;">
                <div class="doc-number">{{ $concreteRatio->reference_no }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $concreteRatio->title }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $concreteRatio->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ $concreteRatio->status }}</td>
                </tr>
                <tr>
                    <th>Grade</th>
                    <td>{{ $concreteRatio->grade ?? '-' }}</td>
                    <th>Waste %</th>
                    <td>{{ $concreteRatio->waste_percent ?? '0' }}%</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $concreteRatio->creator->name ?? 'N/A' }}</td>
                    <th>Source BBS</th>
                    <td>{{ $concreteRatio->rodCalculation->reference_no ?? '-' }}</td>
                </tr>
                @if($concreteRatio->approved_by)
                <tr>
                    <th>Approved By</th>
                    <td>{{ $concreteRatio->approver->name ?? '-' }}</td>
                    <th>Approved At</th>
                    <td>{{ $concreteRatio->approved_at?->format('d M Y H:i') ?? '-' }}</td>
                </tr>
                @endif
            </table>

            @if($concreteRatio->description)
                <div style="margin-top:10px; padding:10px 14px; border:1px solid #ddd; background:#f9f9f9;">
                    <strong style="color:#263a79;">Description:</strong> {{ $concreteRatio->description }}
                </div>
            @endif

            <table class="items-table" style="margin-top:15px;">
                <thead>
                    <tr>
                        <th style="width:14%;">Type</th>
                        <th style="width:10%;">Code</th>
                        <th class="text-center" style="width:6%;">Qty</th>
                        <th class="text-right" style="width:8%;">L (mm)</th>
                        <th class="text-right" style="width:8%;">W (mm)</th>
                        <th class="text-right" style="width:8%;">H (mm)</th>
                        <th class="text-right" style="width:10%;">Volume (m³)</th>
                        <th class="text-right" style="width:10%;">Cement</th>
                        <th class="text-right" style="width:10%;">Sand (m³)</th>
                        <th class="text-right" style="width:10%;">Agg (m³)</th>
                        <th class="text-right" style="width:10%;">Water (L)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($concreteRatio->members as $member)
                        <tr>
                            <td>{{ \App\Constants\RodMemberType::LABELS[$member->type] ?? $member->type }}</td>
                            <td>{{ $member->member_code }}</td>
                            <td class="text-center">{{ $member->quantity }}</td>
                            <td class="text-right">{{ $member->length ?? '-' }}</td>
                            <td class="text-right">{{ $member->width ?? '-' }}</td>
                            <td class="text-right">{{ $member->height ?? '-' }}</td>
                            <td class="text-right">{{ number_format($member->volume_m3, 4) }}</td>
                            <td class="text-right">{{ number_format($member->cement_bags, 2) }}</td>
                            <td class="text-right">{{ number_format($member->sand_m3, 4) }}</td>
                            <td class="text-right">{{ number_format($member->aggregate_m3, 4) }}</td>
                            <td class="text-right" style="font-weight:bold;">{{ number_format($member->water_liters, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="summary-total">
                        <td colspan="6">GRAND TOTAL</td>
                        <td class="text-right">{{ number_format($summary['total_volume_m3'], 4) }} m³</td>
                        <td class="text-right">{{ number_format($summary['total_cement_bags'], 2) }} bags</td>
                        <td class="text-right">{{ number_format($summary['total_sand_m3'], 4) }} m³</td>
                        <td class="text-right">{{ number_format($summary['total_aggregate_m3'], 4) }} m³</td>
                        <td class="text-right">{{ number_format($summary['total_water_liters'], 2) }} L</td>
                    </tr>
                </tbody>
            </table>

            <div class="notes-box" style="margin-top:20px;">
                <strong>Notes:</strong><br/>
                1. All dimensions are in millimetres (mm) unless otherwise noted.<br/>
                2. Volume calculated as L × W × H × Qty (converted to m³).<br/>
                3. Material quantities based on IS 456 standard mix proportions for {{ $concreteRatio->grade ?? 'N/A' }} grade.<br/>
                4.1 bag cement = 50 kg.
            </div>
        </div>
    </div>
</body>
</html>
