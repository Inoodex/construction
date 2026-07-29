<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BBS &amp; Concrete Sheet - {{ $rodCalculation->reference_no }}</title>
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
        .detail-box { margin-top: 12px; padding: 10px 12px; border: 1px solid #ddd; background-color: #f9f9f9; }
        .detail-box h3 { margin: 0 0 6px 0; color: #263a79; font-size: 12px; }
        .detail-box p { margin: 0; line-height: 1.4; }
        .notes-box { margin-top: 15px; padding: 10px 14px; border-left: 4px solid #263a79; background-color: #eaecf2; font-size: 10px; line-height: 1.5; }
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
                    <td colspan="3"><strong>{{ $rodCalculation->title }}</strong></td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $rodCalculation->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ $rodCalculation->status }}</td>
                </tr>
                <tr>
                    <th>Steel Grade</th>
                    <td>{{ $rodCalculation->steel_grade ?? 'Grade 60' }}</td>
                    <th>Concrete Grade</th>
                    <td>{{ $rodCalculation->concrete_grade ? App\Constants\ConcreteRatioConstants::GRADE_LABELS[$rodCalculation->concrete_grade] ?? $rodCalculation->concrete_grade : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Revision</th>
                    <td>{{ $rodCalculation->revision ?? '-' }}</td>
                    <th>Created By</th>
                    <td>{{ $rodCalculation->creator->name ?? 'N/A' }}</td>
                </tr>
            </table>

            @if($rodCalculation->description)
                <div class="detail-box">
                    <h3>Description / Notes</h3>
                    <p>{{ $rodCalculation->description }}</p>
                </div>
            @endif

            {{-- Members Table --}}
            @foreach($rodCalculation->members as $member)
                @php $cbd = $member->concrete_breakdown; @endphp
                <table class="items-table" style="margin-top:12px;">
                    <thead>
                        <tr class="member-header">
                            <td colspan="13">
                                {{ $member->member_code }} — {{ \App\Constants\RodMemberType::LABELS[$member->type] ?? $member->type }}
                                | Qty: {{ $member->quantity }} | Cover: {{ $member->cover }}mm
                                @if($member->length) | L: {{ $member->length }}mm @endif
                                @if($member->width) | W: {{ $member->width }}mm @endif
                                @if($member->height) | H: {{ $member->height }}mm @endif
                                @if($member->depth) | D: {{ $member->depth }}mm @endif
                                @if($member->thickness) | T: {{ $member->thickness }}mm @endif
                                | Conc Vol: {{ number_format($cbd['volume_m3'], 3) }} m³ ({{ number_format($cbd['volume_cft'], 1) }} CFT)
                                [Cement: {{ number_format($cbd['cement_bags'], 1) }} bags | Sand: {{ number_format($cbd['sand_cft'], 1) }} cft | Agg: {{ number_format($cbd['aggregate_cft'], 1) }} cft]
                            </td>
                        </tr>
                        <tr>
                            <th style="width:12%;">Bar Name</th>
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
                                <td class="text-center">Ø{{ $bar->diameter }}</td>
                                <td class="text-right">{{ number_format($bar->actual_size, 0) }}</td>
                                <td class="text-right">{{ $bar->spacing ? number_format($bar->spacing, 0) : 'manual' }}</td>
                                <td class="text-right">{{ number_format($bar->hook_length, 0) }}</td>
                                <td class="text-right">{{ number_format($bar->bend_length, 0) }}</td>
                                <td class="text-right">{{ number_format($bar->lap_length, 0) }}</td>
                                <td class="text-right">{{ number_format($bar->cutting_length, 0) }}</td>
                                <td class="text-center">{{ $bar->bars_count }}</td>
                                <td class="text-right">{{ number_format($bar->total_length, 0) }}</td>
                                <td class="text-right">{{ number_format($bar->unit_weight, 4) }}</td>
                                <td class="text-right" style="font-weight:bold;">{{ number_format($bar->total_weight, 2) }} kg</td>
                            </tr>
                        @endforeach
                        <tr class="summary-row">
                            <td colspan="12" class="text-right">Subtotal Steel Weight ({{ $member->member_code }})</td>
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
                        <th colspan="6" style="text-align:center; font-size:11px;">REBAR SUMMARY BY DIAMETER</th>
                    </tr>
                    <tr>
                        <th>Diameter (mm)</th>
                        <th class="text-right">Bars Count</th>
                        <th class="text-right">Total Length (mm)</th>
                        <th class="text-right">12m Stock Sticks</th>
                        <th class="text-right">Weight (kg)</th>
                        <th class="text-right">Weight (Tons)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary['by_diameter'] as $d)
                        <tr>
                            <td style="font-weight:bold;">Ø{{ $d['diameter'] }} mm</td>
                            <td class="text-right">{{ number_format($d['bars_count']) }}</td>
                            <td class="text-right">{{ number_format($d['total_mm'], 0) }}</td>
                            <td class="text-right">{{ number_format($d['commercial_12m_sticks']) }} pcs</td>
                            <td class="text-right">{{ number_format($d['total_kg'], 2) }} kg</td>
                            <td class="text-right" style="font-weight:bold;">{{ number_format($d['total_tons'], 3) }} T</td>
                        </tr>
                    @endforeach
                    <tr class="summary-total">
                        <td>NET STEEL TOTAL</td>
                        <td class="text-right">{{ number_format(collect($summary['by_diameter'])->sum('bars_count')) }}</td>
                        <td class="text-right">{{ number_format(collect($summary['by_diameter'])->sum('total_mm'), 0) }}</td>
                        <td class="text-right">{{ number_format($summary['total_12m_sticks'] ?? 0) }} pcs</td>
                        <td class="text-right">{{ number_format($summary['total_kg'], 2) }} kg</td>
                        <td class="text-right">{{ number_format($summary['total_tons'], 3) }} T</td>
                    </tr>
                    @if(($summary['waste_percent'] ?? 0) > 0)
                    <tr style="background-color:#e8f5e9; font-weight:bold; color:#1b5e20;">
                        <td colspan="4">GROSS STEEL TOTAL (incl. {{ $summary['waste_percent'] }}% waste)</td>
                        <td class="text-right">{{ number_format($summary['gross_total_kg'], 2) }} kg</td>
                        <td class="text-right">{{ number_format($summary['gross_total_tons'], 3) }} T</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            @endif

            {{-- Concrete Mix Summary Table --}}
            <table class="items-table" style="margin-top:15px;">
                <thead>
                    <tr>
                        <th colspan="5" style="text-align:center; font-size:11px;">CONCRETE MIX DESIGN &amp; MATERIAL ESTIMATION SUMMARY</th>
                    </tr>
                    <tr>
                        <th>Material Item</th>
                        <th class="text-right">Net Quantity</th>
                        <th class="text-right">Gross Qty (incl. {{ $summary['concrete_waste_percent'] }}% waste)</th>
                        <th class="text-right">Unit Rate (৳)</th>
                        <th class="text-right">Estimated Cost (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight:bold;">Concrete Volume</td>
                        <td class="text-right">{{ number_format($summary['total_volume_m3'], 4) }} m³ ({{ number_format($summary['total_volume_cft'], 2) }} CFT)</td>
                        <td class="text-right" style="font-weight:bold;">{{ number_format($summary['gross_volume_m3'], 4) }} m³ ({{ number_format($summary['gross_volume_cft'], 2) }} CFT)</td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                    </tr>
                    <tr>
                        <td>Cement (50kg bags)</td>
                        <td class="text-right">{{ number_format($summary['total_cement_bags'], 2) }} bags</td>
                        <td class="text-right" style="font-weight:bold;">{{ number_format($summary['gross_cement_bags'], 2) }} bags</td>
                        <td class="text-right">৳{{ number_format($summary['cement_price_per_bag'], 2) }}</td>
                        <td class="text-right" style="font-weight:bold; color:#263a79;">৳{{ number_format($summary['cement_cost'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Sand (Fine Aggregate)</td>
                        <td class="text-right">{{ number_format($summary['total_sand_cft'], 2) }} CFT</td>
                        <td class="text-right" style="font-weight:bold;">{{ number_format($summary['gross_sand_cft'], 2) }} CFT</td>
                        <td class="text-right">৳{{ number_format($summary['sand_price_per_cft'], 2) }}</td>
                        <td class="text-right" style="font-weight:bold; color:#263a79;">৳{{ number_format($summary['sand_cost'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Aggregate / Stone</td>
                        <td class="text-right">{{ number_format($summary['total_aggregate_cft'], 2) }} CFT</td>
                        <td class="text-right" style="font-weight:bold;">{{ number_format($summary['gross_aggregate_cft'], 2) }} CFT</td>
                        <td class="text-right">৳{{ number_format($summary['aggregate_price_per_cft'], 2) }}</td>
                        <td class="text-right" style="font-weight:bold; color:#263a79;">৳{{ number_format($summary['aggregate_cost'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Water</td>
                        <td class="text-right">{{ number_format($summary['total_water_liters'], 1) }} L</td>
                        <td class="text-right">{{ number_format($summary['gross_water_liters'], 1) }} L</td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                    </tr>
                    <tr class="summary-total">
                        <td colspan="4">TOTAL CONCRETE COST</td>
                        <td class="text-right">৳{{ number_format($summary['total_concrete_cost'], 2) }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Combined Cost Box --}}
            <div style="margin-top:15px; padding:10px 14px; border:2px solid #263a79; background-color:#eaecf2;">
                <table style="width:100%; font-size:11px;">
                    <tr>
                        <td style="font-weight:bold; color:#263a79;">COMBINED SHEET TOTAL COST:</td>
                        <td>Steel: <strong>৳{{ number_format($summary['total_steel_cost'], 2) }}</strong> | Concrete: <strong>৳{{ number_format($summary['total_concrete_cost'], 2) }}</strong></td>
                        <td class="text-right" style="font-size:14px; font-weight:bold; color:#263a79;">GRAND TOTAL: ৳{{ number_format($summary['grand_total_sheet_cost'], 2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="notes-box" style="margin-top:15px;">
                <strong>Notes &amp; Formulas:</strong><br/>
                1. Steel unit weight calculated as D&sup2;/162 kg/m. Commercial 12m sticks = &lceil;Total Length &divide; 12,000&rceil;.<br/>
                2. Concrete volume calculated per member dimensions. Concrete Mix Grade = {{ $summary['concrete_grade'] ?? 'M20' }}.<br/>
                3. Gross quantities include {{ $rodCalculation->waste_percent ?? 0 }}% steel scrap and {{ $summary['concrete_waste_percent'] }}% concrete pour wastage allowance.
            </div>
        </div>
    </div>
</body>
</html>
