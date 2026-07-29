<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <td colspan="17" style="font-size: 16px; font-weight: bold; text-align: center;">BAR BENDING SCHEDULE &amp; CONCRETE ESTIMATION REPORT</td>
        </tr>
        <tr>
            <td colspan="17" style="font-size: 12px; font-weight: bold; text-align: center;">{{ $rodCalculation->title }} ({{ $rodCalculation->reference_no }})</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td style="font-weight: bold;">Project:</td>
            <td>{{ $rodCalculation->project->name ?? 'N/A' }}</td>
            <td style="font-weight: bold;">Steel Grade:</td>
            <td>{{ $rodCalculation->steel_grade ?? 'Grade 60' }}</td>
            <td style="font-weight: bold;">Concrete Grade:</td>
            <td>{{ $summary['concrete_grade'] ? App\Constants\ConcreteRatioConstants::GRADE_LABELS[$summary['concrete_grade']] ?? $summary['concrete_grade'] : 'N/A' }}</td>
            <td style="font-weight: bold;">Status:</td>
            <td>{{ ucfirst($rodCalculation->status) }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Revision:</td>
            <td>{{ $rodCalculation->revision ?? '-' }}</td>
            <td style="font-weight: bold;">Created By:</td>
            <td>{{ $rodCalculation->creator->name ?? '-' }}</td>
            <td style="font-weight: bold;">Waste Allowance:</td>
            <td>Steel {{ $rodCalculation->waste_percent ?? 0 }}% | Conc {{ $summary['concrete_waste_percent'] }}%</td>
            <td style="font-weight: bold;">Approved By:</td>
            <td>{{ $rodCalculation->approver->name ?? '-' }}</td>
        </tr>
        <tr><td></td></tr>

        {{-- Steel Rebar By-Diameter Summary --}}
        <tr>
            <td colspan="7" style="font-weight: bold; background-color: #E2EFDA;">SUMMARY BY REBAR DIAMETER &amp; STOCK ORDERING</td>
        </tr>
        <tr style="background-color: #F2F2F2; font-weight: bold;">
            <td>Diameter (mm)</td>
            <td>Bar Count</td>
            <td>Total Length (mm)</td>
            <td>12m Commercial Sticks (pcs)</td>
            <td>Weight (kg)</td>
            <td>Weight (Metric Tons)</td>
            @if(($summary['total_steel_cost'] ?? 0) > 0)
            <td>Est. Cost (৳)</td>
            @endif
        </tr>
        @foreach($summary['by_diameter'] as $d)
            <tr>
                <td>Ø{{ $d['diameter'] }} mm</td>
                <td>{{ $d['bars_count'] }}</td>
                <td>{{ number_format($d['total_mm'], 0, '.', '') }}</td>
                <td style="font-weight: bold; color: #1F4E78;">{{ $d['commercial_12m_sticks'] }}</td>
                <td style="font-weight: bold;">{{ number_format($d['total_kg'], 2, '.', '') }}</td>
                <td style="font-weight: bold; color: #1F4E78;">{{ number_format($d['total_tons'], 3, '.', '') }}</td>
                @if(($summary['total_steel_cost'] ?? 0) > 0)
                <td style="font-weight: bold; color: #375623;">৳{{ number_format($d['estimated_cost'], 2, '.', '') }}</td>
                @endif
            </tr>
        @endforeach
        <tr style="font-weight: bold; background-color: #E2EFDA;">
            <td>TOTAL STEEL (NET)</td>
            <td>{{ collect($summary['by_diameter'])->sum('bars_count') }}</td>
            <td>{{ number_format(collect($summary['by_diameter'])->sum('total_mm'), 0, '.', '') }}</td>
            <td>{{ $summary['total_12m_sticks'] ?? 0 }} pcs</td>
            <td>{{ number_format($summary['total_kg'], 2, '.', '') }} kg</td>
            <td>{{ number_format($summary['total_tons'], 3, '.', '') }} T</td>
            @if(($summary['total_steel_cost'] ?? 0) > 0)
            <td>৳{{ number_format(collect($summary['by_diameter'])->sum('estimated_cost'), 2, '.', '') }}</td>
            @endif
        </tr>
        @if(($summary['waste_percent'] ?? 0) > 0)
            <tr style="font-weight: bold; background-color: #C6E0B4;">
                <td>GROSS STEEL TOTAL (incl. {{ $summary['waste_percent'] }}% waste)</td>
                <td colspan="3"></td>
                <td>{{ number_format($summary['gross_total_kg'], 2, '.', '') }} kg</td>
                <td>{{ number_format($summary['gross_total_tons'], 3, '.', '') }} T</td>
                @if(($summary['total_steel_cost'] ?? 0) > 0)
                <td>৳{{ number_format($summary['total_steel_cost'], 2, '.', '') }}</td>
                @endif
            </tr>
        @endif

        <tr><td></td></tr>

        {{-- Concrete Mix & Material Summary --}}
        <tr>
            <td colspan="7" style="font-weight: bold; background-color: #D9E1F2;">CONCRETE MIX &amp; MATERIAL ESTIMATION SUMMARY</td>
        </tr>
        <tr style="background-color: #F2F2F2; font-weight: bold;">
            <td>Material Item</td>
            <td>Net Qty</td>
            <td>Gross Qty (w/ Waste)</td>
            <td>Unit Price (৳)</td>
            <td colspan="3">Est. Material Cost (৳)</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Concrete Volume</td>
            <td>{{ number_format($summary['total_volume_m3'], 4) }} m³ ({{ number_format($summary['total_volume_cft'], 2) }} CFT)</td>
            <td style="font-weight: bold;">{{ number_format($summary['gross_volume_m3'], 4) }} m³ ({{ number_format($summary['gross_volume_cft'], 2) }} CFT)</td>
            <td>-</td>
            <td colspan="3">-</td>
        </tr>
        <tr>
            <td>Cement (50kg bags)</td>
            <td>{{ number_format($summary['total_cement_bags'], 2) }} bags</td>
            <td style="font-weight: bold;">{{ number_format($summary['gross_cement_bags'], 2) }} bags</td>
            <td>৳{{ number_format($summary['cement_price_per_bag'], 2) }}</td>
            <td colspan="3" style="font-weight: bold;">৳{{ number_format($summary['cement_cost'], 2) }}</td>
        </tr>
        <tr>
            <td>Sand (Fine Aggregate)</td>
            <td>{{ number_format($summary['total_sand_cft'], 2) }} CFT</td>
            <td style="font-weight: bold;">{{ number_format($summary['gross_sand_cft'], 2) }} CFT</td>
            <td>৳{{ number_format($summary['sand_price_per_cft'], 2) }}</td>
            <td colspan="3" style="font-weight: bold;">৳{{ number_format($summary['sand_cost'], 2) }}</td>
        </tr>
        <tr>
            <td>Aggregate / Stone</td>
            <td>{{ number_format($summary['total_aggregate_cft'], 2) }} CFT</td>
            <td style="font-weight: bold;">{{ number_format($summary['gross_aggregate_cft'], 2) }} CFT</td>
            <td>৳{{ number_format($summary['aggregate_price_per_cft'], 2) }}</td>
            <td colspan="3" style="font-weight: bold;">৳{{ number_format($summary['aggregate_cost'], 2) }}</td>
        </tr>
        <tr>
            <td>Water</td>
            <td>{{ number_format($summary['total_water_liters'], 1) }} L</td>
            <td>{{ number_format($summary['gross_water_liters'], 1) }} L</td>
            <td>-</td>
            <td colspan="3">-</td>
        </tr>
        <tr style="font-weight: bold; background-color: #D9E1F2;">
            <td colspan="4">TOTAL CONCRETE COST</td>
            <td colspan="3">৳{{ number_format($summary['total_concrete_cost'], 2) }}</td>
        </tr>

        <tr><td></td></tr>
        <tr style="font-weight: bold; background-color: #263a79; color: #ffffff;">
            <td colspan="4">COMBINED SHEET GRAND TOTAL COST (STEEL + CONCRETE)</td>
            <td colspan="3">৳{{ number_format($summary['grand_total_sheet_cost'], 2) }}</td>
        </tr>
        <tr><td></td></tr>

        {{-- Structural Members Table --}}
        <tr>
            <td colspan="17" style="font-weight: bold; background-color: #D9E1F2;">STRUCTURAL MEMBERS &amp; REBAR SCHEDULE</td>
        </tr>
        <tr style="background-color: #F2F2F2; font-weight: bold;">
            <td>Member Code</td>
            <td>Member Type</td>
            <td>Member Qty</td>
            <td>Cover (mm)</td>
            <td>Bar Name</td>
            <td>Direction</td>
            <td>Dia (mm)</td>
            <td>Actual Size (mm)</td>
            <td>Spacing (mm)</td>
            <td>Hook (mm)</td>
            <td>Bend (mm)</td>
            <td>Lap (mm)</td>
            <td>Cutting Length (mm)</td>
            <td>Bar Count</td>
            <td>Total Length (mm)</td>
            <td>Unit Weight (kg/m)</td>
            <td>Total Weight (kg)</td>
        </tr>
        @foreach($rodCalculation->members as $member)
            @foreach($member->bars as $bar)
                <tr>
                    <td>{{ $member->member_code }}</td>
                    <td>{{ ucfirst($member->type) }}</td>
                    <td>{{ $member->quantity }}</td>
                    <td>{{ $member->cover }}</td>
                    <td>{{ $bar->bar_name }}</td>
                    <td>{{ $bar->direction }}</td>
                    <td>{{ $bar->diameter }}</td>
                    <td>{{ $bar->actual_size }}</td>
                    <td>{{ $bar->spacing ?? '-' }}</td>
                    <td>{{ $bar->hook_length }}</td>
                    <td>{{ $bar->bend_length }}</td>
                    <td>{{ $bar->lap_length }}</td>
                    <td>{{ number_format($bar->cutting_length, 0, '.', '') }}</td>
                    <td>{{ $bar->bars_count }}</td>
                    <td>{{ number_format($bar->total_length, 0, '.', '') }}</td>
                    <td>{{ number_format($bar->unit_weight, 4, '.', '') }}</td>
                    <td style="font-weight: bold;">{{ number_format($bar->total_weight, 2, '.', '') }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>
</body>
</html>
