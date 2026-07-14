<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work Order - {{ $workOrder->work_order_number }}</title>
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
                <div class="doc-number">{{ $workOrder->work_order_number }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Project</th>
                    <td>{{ $workOrder->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ str_replace('_', ' ', $workOrder->status) }}</td>
                </tr>
                <tr>
                    <th>Site</th>
                    <td>{{ $workOrder->site->name ?? 'N/A' }}</td>
                    <th>Related Task</th>
                    <td>{{ $workOrder->task->title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Issued By</th>
                    <td>{{ $workOrder->issuer->name ?? 'N/A' }}</td>
                    <th>Assigned To</th>
                    <td>{{ $workOrder->assignee->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Issue Date</th>
                    <td>{{ $workOrder->issue_date?->format('d M Y') ?? 'N/A' }}</td>
                    <th>Due Date</th>
                    <td>{{ $workOrder->due_date?->format('d M Y') ?? 'N/A' }}</td>
                </tr>
                @if($workOrder->completed_date)
                <tr>
                    <th>Completed Date</th>
                    <td>{{ $workOrder->completed_date->format('d M Y') }}</td>
                    <th></th>
                    <td></td>
                </tr>
                @endif
            </table>

            @if($workOrder->instructions)
            <div class="detail-box">
                <h3>Instructions</h3>
                <p>{{ $workOrder->instructions }}</p>
            </div>
            @endif

            @if($workOrder->notes)
            <div class="notes-box" style="margin-top:15px;">
                <strong>Notes:</strong><br/>
                {{ $workOrder->notes }}
            </div>
            @endif

            <table style="width:100%;margin-top:40px;">
                <tr>
                    <td class="sig-line" style="width:45%;padding-right:20px;">
                        <strong>Issued By</strong><br/>
                        {{ $workOrder->issuer->name ?? '_______________' }}
                    </td>
                    <td style="width:10%;"></td>
                    <td class="sig-line" style="width:45%;text-align:right;">
                        <strong>Acknowledged By</strong><br/>
                        {{ $workOrder->assignee->name ?? '_______________' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
