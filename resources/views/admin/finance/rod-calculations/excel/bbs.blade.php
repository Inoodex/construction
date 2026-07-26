<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <td colspan="13" style="font-size: 16px; font-weight: bold; text-align: center;">BAR BENDING SCHEDULE (BBS) REPORT</td>
        </tr>
        <tr>
            <td colspan="13" style="font-size: 12px; font-weight: bold; text-align: center;">{{ $rodCalculation->title }} ({{ $rodCalculation->reference_no }})</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td style="font-weight: bold;">Project:</td>
            <td>{{ $rodCalculation->project->name ?? 'N/A' }}</td>
            <td style="font-weight: bold;">Steel Grade:</td>
            <td>{{ $rodCalculation->steel_grade ?? '-' }}</td>
            <td style="font-weight: bold;">Status:</td>
            <td>{{ ucfirst($rodCalculation->status) }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Revision:</td>
            <td>{{ $rodCalculation->revision ?? '-' }}</td>
            <td style="font-weight: bold;">Created By:</td>
            <td>{{ $rodCalculation->creator->name ?? '-' }}</td>
            <td style="font-weight: bold;">Approved By:</td>
            <td>{{ $rodCalculation->approver->name ?? '-' }}</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="5" style="font-weight: bold; background-color: #E2EFDA;">SUMMARY BY REBAR DIAMETER &amp; STOCK ORDERING</td>
        </tr>
        <tr style="background-color: #F2F2F2; font-weight: bold;">
            <td>Diameter (mm)</td>
            <td>Bar Count</td>
            <td>Total Length (mm)</td>
            <td>12m Commercial Sticks (pcs)</td>
            <td>Net Weight (kg)</td>
        </tr>
        @foreach($summary['by_diameter'] as $d)
            <tr>
                <td>Ø{{ $d['diameter'] }} mm</td>
                <td>{{ $d['bars_count'] }}</td>
                <td>{{ number_format($d['total_mm'], 0, '.', '') }}</td>
                <td style="font-weight: bold; color: #1F4E78;">{{ $d['commercial_12m_sticks'] }}</td>
                <td style="font-weight: bold;">{{ number_format($d['total_kg'], 2, '.', '') }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold; background-color: #E2EFDA;">
            <td>TOTAL (NET)</td>
            <td>{{ collect($summary['by_diameter'])->sum('bars_count') }}</td>
            <td>{{ number_format(collect($summary['by_diameter'])->sum('total_mm'), 0, '.', '') }}</td>
            <td>{{ $summary['total_12m_sticks'] ?? 0 }} pcs</td>
            <td>{{ number_format($summary['total_kg'], 2, '.', '') }} kg</td>
        </tr>
        @if(($summary['waste_percent'] ?? 0) > 0)
            <tr>
                <td style="font-weight: bold;">Wastage ({{ $summary['waste_percent'] }}%)</td>
                <td colspan="3"></td>
                <td style="font-weight: bold;">+{{ number_format($summary['waste_kg'], 2, '.', '') }} kg</td>
            </tr>
            <tr style="font-weight: bold; background-color: #C6E0B4;">
                <td>GROSS TOTAL WEIGHT</td>
                <td colspan="3"></td>
                <td>{{ number_format($summary['gross_total_kg'], 2, '.', '') }} kg</td>
            </tr>
        @endif
        <tr><td></td></tr>
        <tr>
            <td colspan="13" style="font-weight: bold; background-color: #D9E1F2;">STRUCTURAL MEMBERS &amp; REBAR SCHEDULE</td>
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
