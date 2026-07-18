<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BBS - {{ $rodCalculation->reference_no }}</title>
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
        .member-header td { background-color: #eaecf2; color: #263a79; font-weight: bold; font-size: 11px; padding: 6px 10px; border: 1px solid #ddd; }
        .summary-row td { padding: 5px 8px; border: 1px solid #ddd; font-weight: bold; background-color: #eaecf2; }
        .summary-total td { padding: 6px 10px; border: 1px solid #263a79; font-weight: bold; color: #fff; background-color: #263a79; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .table-shade { background-color: #eaecf2; }
        .detail-box { margin-top: 15px; padding: 12px 14px; border: 1px solid #ddd; background-color: #f9f9f9; }
        .detail-box h3 { margin: 0 0 8px 0; color: #263a79; font-size: 13px; }
        .detail-box p { margin: 0; line-height: 1.5; }
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
                <div class="doc-number">{{ $rodCalculation->reference_no }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $rodCalculation->title }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $rodCalculation->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ $rodCalculation->status }}</td>
                </tr>
                <tr>
                    <th>Steel Grade</th>
                    <td>{{ $rodCalculation->steel_grade ?? '-' }}</td>
                    <th>Revision</th>
                    <td>{{ $rodCalculation->revision ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $rodCalculation->creator->name ?? 'N/A' }}</td>
                    <th>Formula Version</th>
                    <td>{{ $rodCalculation->formula_version }}</td>
                </tr>
                @if($rodCalculation->approved_by)
                <tr>
                    <th>Approved By</th>
                    <td>{{ $rodCalculation->approver->name ?? '-' }}</td>
                    <th>Approved At</th>
                    <td>{{ $rodCalculation->approved_at?->format('d M Y H:i') ?? '-' }}</td>
                </tr>
                @endif
            </table>

            @if($rodCalculation->description)
                <div class="detail-box">
                    <h3>Description</h3>
                    <p>{{ $rodCalculation->description }}</p>
                </div>
            @endif

            @foreach($rodCalculation->members as $member)
                <table class="items-table" style="margin-top:12px;">
                    <thead>
                        <tr class="member-header">
                            <td colspan="13">
                                {{ $member->member_code }} — {{ \App\Constants\RodMemberType::LABELS[$member->type] ?? $member->type }}
                                | Qty: {{ $member->quantity }}
                                | Cover: {{ $member->cover }}mm
                                @if($member->length) | L: {{ $member->length }}mm @endif
                                @if($member->width) | W: {{ $member->width }}mm @endif
                                @if($member->height) | H: {{ $member->height }}mm @endif
                                @if($member->depth) | D: {{ $member->depth }}mm @endif
                                @if($member->thickness) | T: {{ $member->thickness }}mm @endif
                                @if($member->remarks) | {{ $member->remarks }} @endif
                            </td>
                        </tr>
                        <tr>
                            <th style="width:12%;">Bar</th>
                            <th style="width:6%;">Dir</th>
                            <th style="width:6%;">Dia</th>
                            <th style="width:8%;">Actual</th>
                            <th style="width:8%;">Spacing</th>
                            <th style="width:7%;">Hook</th>
                            <th style="width:7%;">Bend</th>
                            <th style="width:7%;">Lap</th>
                            <th style="width:10%;">Cut Length</th>
                            <th style="width:7%;">Count</th>
                            <th style="width:10%;">Total Length</th>
                            <th style="width:10%;">Unit Wt</th>
                            <th style="width:12%;">Total Wt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($member->bars as $bar)
                            <tr>
                                <td>{{ $bar->bar_name }}</td>
                                <td class="text-center">{{ $bar->direction }}</td>
                                <td class="text-center">{{ $bar->diameter }}</td>
                                <td class="text-right">{{ $bar->actual_size }}</td>
                                <td class="text-right">{{ $bar->spacing ?? 'manual' }}</td>
                                <td class="text-right">{{ $bar->hook_length }}</td>
                                <td class="text-right">{{ $bar->bend_length }}</td>
                                <td class="text-right">{{ $bar->lap_length }}</td>
                                <td class="text-right">{{ number_format($bar->cutting_length, 0) }}</td>
                                <td class="text-center">{{ $bar->bars_count }}</td>
                                <td class="text-right">{{ number_format($bar->total_length, 0) }}</td>
                                <td class="text-right">{{ number_format($bar->unit_weight, 4) }}</td>
                                <td class="text-right" style="font-weight:bold;">{{ number_format($bar->total_weight, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="summary-row">
                            <td colspan="12" class="text-right">Subtotal ({{ $member->member_code }})</td>
                            <td class="text-right">{{ number_format($member->bars->sum('total_weight'), 2) }} kg</td>
                        </tr>
                    </tbody>
                </table>
            @endforeach

            {{-- Summary by Diameter --}}
            @if(count($summary['by_diameter']))
            <table class="items-table" style="margin-top:15px;">
                <thead>
                    <tr>
                        <th colspan="4" style="text-align:center; font-size:12px;">SUMMARY BY DIAMETER</th>
                    </tr>
                    <tr>
                        <th>Diameter (mm)</th>
                        <th class="text-right">Bars Count</th>
                        <th class="text-right">Total Length (mm)</th>
                        <th class="text-right">Total Weight (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary['by_diameter'] as $d)
                        <tr>
                            <td style="font-weight:bold;">{{ $d['diameter'] }}</td>
                            <td class="text-right">{{ $d['bars_count'] }}</td>
                            <td class="text-right">{{ number_format($d['total_mm'], 0) }}</td>
                            <td class="text-right">{{ number_format($d['total_kg'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="summary-total">
                        <td>GRAND TOTAL</td>
                        <td class="text-right">{{ collect($summary['by_diameter'])->sum('bars_count') }}</td>
                        <td class="text-right">{{ number_format(collect($summary['by_diameter'])->sum('total_mm'), 0) }}</td>
                        <td class="text-right">{{ number_format($summary['total_kg'], 2) }} kg</td>
                    </tr>
                </tbody>
            </table>
            @endif

            <div class="notes-box" style="margin-top:20px;">
                <strong>Notes:</strong><br/>
                1. All dimensions are in millimetres (mm) unless otherwise noted.<br/>
                2. Unit weight calculated as D&sup2;/162 kg/m.<br/>
                3. Total weight = Cutting Length &times; Bar Count &times; Member Quantity &times; Unit Weight.
            </div>
        </div>
    </div>
</body>
</html>
