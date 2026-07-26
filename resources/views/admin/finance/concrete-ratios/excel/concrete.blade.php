<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <tr>
            <td colspan="11" style="font-size: 16px; font-weight: bold; text-align: center;">CONCRETE RATIO REPORT</td>
        </tr>
        <tr>
            <td colspan="11" style="font-size: 12px; font-weight: bold; text-align: center;">{{ $concreteRatio->title }} ({{ $concreteRatio->reference_no }})</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td style="font-weight: bold;">Project:</td>
            <td>{{ $concreteRatio->project->name ?? 'N/A' }}</td>
            <td style="font-weight: bold;">Grade:</td>
            <td>{{ $concreteRatio->grade ?? '-' }}</td>
            <td style="font-weight: bold;">Status:</td>
            <td>{{ ucfirst($concreteRatio->status) }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Source BBS:</td>
            <td>{{ $concreteRatio->rodCalculation->reference_no ?? '-' }}</td>
            <td style="font-weight: bold;">Waste %:</td>
            <td>{{ $concreteRatio->waste_percent ?? 0 }}%</td>
            <td style="font-weight: bold;">Created By:</td>
            <td>{{ $concreteRatio->creator->name ?? '-' }}</td>
        </tr>
        @if($concreteRatio->approved_by)
        <tr>
            <td style="font-weight: bold;">Approved By:</td>
            <td>{{ $concreteRatio->approver->name ?? '-' }}</td>
            <td style="font-weight: bold;">Approved At:</td>
            <td>{{ $concreteRatio->approved_at?->format('d M Y H:i') ?? '-' }}</td>
        </tr>
        @endif
        <tr><td></td></tr>

        {{-- Summary --}}
        <tr>
            <td colspan="5" style="font-weight: bold; background-color: #E2EFDA;">MATERIAL SUMMARY</td>
        </tr>
        <tr style="background-color: #F2F2F2; font-weight: bold;">
            <td>Item</td>
            <td>Net Total</td>
            <td>Unit</td>
            @if($summary['waste_percent'] > 0)
            <td>Gross Total (incl. {{ $summary['waste_percent'] }}% waste)</td>
            <td>Unit</td>
            @endif
        </tr>
        <tr>
            <td>Concrete Volume</td>
            <td>{{ number_format($summary['total_volume_m3'], 4, '.', '') }}</td>
            <td>m³</td>
            @if($summary['waste_percent'] > 0)
            <td style="font-weight: bold; color: #1F4E78;">{{ number_format($summary['gross_volume_m3'], 4, '.', '') }}</td>
            <td>m³</td>
            @endif
        </tr>
        <tr>
            <td>Cement</td>
            <td>{{ number_format($summary['total_cement_bags'], 2, '.', '') }}</td>
            <td>bags</td>
            @if($summary['waste_percent'] > 0)
            <td style="font-weight: bold; color: #1F4E78;">{{ number_format($summary['gross_cement_bags'], 2, '.', '') }}</td>
            <td>bags</td>
            @endif
        </tr>
        <tr>
            <td>Sand</td>
            <td>{{ number_format($summary['total_sand_m3'], 4, '.', '') }}</td>
            <td>m³</td>
            @if($summary['waste_percent'] > 0)
            <td style="font-weight: bold; color: #1F4E78;">{{ number_format($summary['gross_sand_m3'], 4, '.', '') }}</td>
            <td>m³</td>
            @endif
        </tr>
        <tr>
            <td>Aggregate (Coarse)</td>
            <td>{{ number_format($summary['total_aggregate_m3'], 4, '.', '') }}</td>
            <td>m³</td>
            @if($summary['waste_percent'] > 0)
            <td style="font-weight: bold; color: #1F4E78;">{{ number_format($summary['gross_aggregate_m3'], 4, '.', '') }}</td>
            <td>m³</td>
            @endif
        </tr>
        <tr>
            <td>Water</td>
            <td>{{ number_format($summary['total_water_liters'], 2, '.', '') }}</td>
            <td>liters</td>
            @if($summary['waste_percent'] > 0)
            <td style="font-weight: bold; color: #1F4E78;">{{ number_format($summary['gross_water_liters'], 2, '.', '') }}</td>
            <td>liters</td>
            @endif
        </tr>
        <tr><td></td></tr>

        {{-- Member Schedule --}}
        <tr>
            <td colspan="11" style="font-weight: bold; background-color: #D9E1F2;">STRUCTURAL MEMBER CONCRETE SCHEDULE</td>
        </tr>
        <tr style="background-color: #F2F2F2; font-weight: bold;">
            <td>Member Type</td>
            <td>Code</td>
            <td>Qty</td>
            <td>Length (mm)</td>
            <td>Width (mm)</td>
            <td>Height (mm)</td>
            <td>Depth (mm)</td>
            <td>Thickness (mm)</td>
            <td>Volume (m³)</td>
            <td>Cement (bags)</td>
            <td>Sand (m³)</td>
            <td>Aggregate (m³)</td>
            <td>Water (L)</td>
            <td>Remarks</td>
        </tr>
        @foreach($concreteRatio->members->sortBy('sort_order') as $member)
        <tr>
            <td>{{ \App\Constants\RodMemberType::LABELS[$member->type] ?? $member->type }}</td>
            <td>{{ $member->member_code }}</td>
            <td>{{ $member->quantity }}</td>
            <td>{{ $member->length ?? '-' }}</td>
            <td>{{ $member->width ?? '-' }}</td>
            <td>{{ $member->height ?? '-' }}</td>
            <td>{{ $member->depth ?? '-' }}</td>
            <td>{{ $member->thickness ?? '-' }}</td>
            <td>{{ number_format($member->volume_m3, 4, '.', '') }}</td>
            <td>{{ number_format($member->cement_bags, 2, '.', '') }}</td>
            <td>{{ number_format($member->sand_m3, 4, '.', '') }}</td>
            <td>{{ number_format($member->aggregate_m3, 4, '.', '') }}</td>
            <td>{{ number_format($member->water_liters, 2, '.', '') }}</td>
            <td>{{ $member->remarks ?? '' }}</td>
        </tr>
        @endforeach
        <tr style="font-weight: bold; background-color: #E2EFDA;">
            <td colspan="8">NET TOTAL</td>
            <td>{{ number_format($summary['total_volume_m3'], 4, '.', '') }} m³</td>
            <td>{{ number_format($summary['total_cement_bags'], 2, '.', '') }} bags</td>
            <td>{{ number_format($summary['total_sand_m3'], 4, '.', '') }} m³</td>
            <td>{{ number_format($summary['total_aggregate_m3'], 4, '.', '') }} m³</td>
            <td>{{ number_format($summary['total_water_liters'], 2, '.', '') }} L</td>
            <td></td>
        </tr>
        @if($summary['waste_percent'] > 0)
        <tr style="font-weight: bold; background-color: #C6E0B4;">
            <td colspan="8">GROSS TOTAL (incl. {{ $summary['waste_percent'] }}% waste)</td>
            <td>{{ number_format($summary['gross_volume_m3'], 4, '.', '') }} m³</td>
            <td>{{ number_format($summary['gross_cement_bags'], 2, '.', '') }} bags</td>
            <td>{{ number_format($summary['gross_sand_m3'], 4, '.', '') }} m³</td>
            <td>{{ number_format($summary['gross_aggregate_m3'], 4, '.', '') }} m³</td>
            <td>{{ number_format($summary['gross_water_liters'], 2, '.', '') }} L</td>
            <td></td>
        </tr>
        @endif
    </table>
</body>
</html>
