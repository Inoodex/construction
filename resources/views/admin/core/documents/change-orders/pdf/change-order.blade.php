<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Order - {{ $changeOrder->change_order_number }}</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: sans-serif; color: #333; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        .wrapper { position: relative; width: 100%; }
        .bg-img { position: absolute; top: 0; left: 0; width: 210mm; height: 297mm; }
        .content { position: relative; padding: 120px 25px 0 25px; box-sizing: border-box; }
        .doc-number { display: inline-block; background-color: #263a79; color: white; padding: 6px 14px; font-size: 14px; font-weight: bold; margin-bottom: 8px; }
        .meta-table { width: 100%; margin-bottom: 15px; font-size: 11px; }
        .meta-table th { text-align: left; padding: 6px 10px; background-color: #eaecf2; color: #263a79; width: 30%; font-weight: bold; border: 1px solid #ddd; }
        .meta-table td { padding: 6px 10px; border: 1px solid #ddd; }
        .detail-box { margin-top: 15px; padding: 12px 14px; border: 1px solid #ddd; background-color: #f9f9f9; }
        .detail-box h3 { margin: 0 0 8px 0; color: #263a79; font-size: 13px; }
        .detail-box p { margin: 0; line-height: 1.5; }
        .notes-box { margin-top: 20px; padding: 10px 14px; border-left: 4px solid #263a79; background-color: #eaecf2; font-size: 11px; line-height: 1.5; }
        .notes-box strong { color: #263a79; }
        .sig-line { padding-top: 60px; border-top: 1px solid #333; vertical-align: top; }
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif
        <div class="content">
            <div style="margin-bottom:15px;">
                <div class="doc-number">{{ $changeOrder->change_order_number }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Project</th>
                    <td>{{ $changeOrder->project->name ?? 'N/A' }}</td>
                    <th>Type</th>
                    <td style="text-transform:capitalize;">{{ str_replace('_', ' ', $changeOrder->type) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ str_replace('_', ' ', $changeOrder->status) }}</td>
                    <th>Requested By</th>
                    <td>{{ $changeOrder->requester->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Cost Impact</th>
                    <td>{{ $changeOrder->cost_impact ? number_format($changeOrder->cost_impact, 2) : 'Nil' }}</td>
                    <th>Time Impact</th>
                    <td>{{ $changeOrder->time_impact_days ? $changeOrder->time_impact_days . ' days' : 'Nil' }}</td>
                </tr>
                @if($changeOrder->rfi)
                <tr>
                    <th>Related RFI</th>
                    <td>{{ $changeOrder->rfi->rfi_number }}</td>
                    <th>Approved By</th>
                    <td>{{ $changeOrder->approver->name ?? '—' }}</td>
                </tr>
                @endif
                @if($changeOrder->approved_date)
                <tr>
                    <th>Approved Date</th>
                    <td>{{ $changeOrder->approved_date->format('d M Y') }}</td>
                    <th></th>
                    <td></td>
                </tr>
                @endif
            </table>

            <div class="detail-box">
                <h3>Description</h3>
                <p>{{ $changeOrder->description }}</p>
            </div>

            @if($changeOrder->notes)
            <div class="notes-box" style="margin-top:15px;">
                <strong>Notes:</strong><br/>
                {{ $changeOrder->notes }}
            </div>
            @endif

            <table style="width:100%;margin-top:30px;">
                <tr>
                    <td class="sig-line" style="width:45%;padding-right:20px;">
                        <strong>Requested By</strong><br/>
                        {{ $changeOrder->requester->name ?? '_______________' }}
                    </td>
                    <td style="width:10%;"></td>
                    <td class="sig-line" style="width:45%;text-align:right;">
                        <strong>Approved By</strong><br/>
                        {{ $changeOrder->approver->name ?? '_______________' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
