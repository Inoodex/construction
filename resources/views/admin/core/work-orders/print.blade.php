<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Work Order — {{ $workOrder->work_order_number }}</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 13px; color: #000; padding: 40px; margin: 0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .header h1 { font-size: 20px; margin: 0 0 5px; text-transform: uppercase; letter-spacing: 2px; }
        .header h2 { font-size: 16px; margin: 0; font-weight: normal; }
        .wo-number { font-size: 14px; font-weight: bold; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 14px; font-weight: bold; border-bottom: 1px solid #999; padding-bottom: 5px; margin-bottom: 10px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px 8px; vertical-align: top; }
        td.label { width: 150px; font-weight: bold; }
        .instructions { white-space: pre-wrap; line-height: 1.6; }
        .footer { margin-top: 50px; border-top: 1px solid #999; padding-top: 15px; display: flex; justify-content: space-between; }
        .footer div { text-align: center; }
        .footer .line { display: inline-block; width: 150px; border-top: 1px solid #000; margin-top: 30px; padding-top: 5px; font-size: 11px; }
        @media print { body { padding: 20px; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:right;margin-bottom:20px;">
        <button onclick="window.print()" style="padding:8px 20px;font-size:14px;cursor:pointer;">Print</button>
        <button onclick="window.close()" style="padding:8px 20px;font-size:14px;cursor:pointer;">Close</button>
    </div>

    <div class="header">
        <h1>Work Order</h1>
        <h2>{{ $workOrder->project->name ?? '' }}</h2>
        <div class="wo-number">{{ $workOrder->work_order_number }}</div>
    </div>

    <div class="section">
        <table>
            <tr><td class="label">Date Issued:</td><td>{{ $workOrder->issue_date?->format('d M Y') ?: '—' }}</td></tr>
            <tr><td class="label">Due Date:</td><td>{{ $workOrder->due_date?->format('d M Y') ?: '—' }}</td></tr>
            <tr><td class="label">Issued To:</td><td>{{ $workOrder->assignee->name ?? '—' }}</td></tr>
            <tr><td class="label">Site:</td><td>{{ $workOrder->site->name ?? '—' }}</td></tr>
            @if($workOrder->task)
                <tr><td class="label">Related Task:</td><td>{{ $workOrder->task->name }}</td></tr>
            @endif
            <tr><td class="label">Status:</td><td>{{ str_replace('_', ' ', ucwords($workOrder->status)) }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Title</div>
        <p>{{ $workOrder->title }}</p>
    </div>

    <div class="section">
        <div class="section-title">Work Instructions</div>
        <p class="instructions">{{ $workOrder->instructions ?: 'No specific instructions provided.' }}</p>
    </div>

    @if($workOrder->notes)
        <div class="section">
            <div class="section-title">Notes</div>
            <p class="instructions">{{ $workOrder->notes }}</p>
        </div>
    @endif

    <div class="footer">
        <div>
            <div class="line">Issued By (Signature)</div>
            <p style="margin-top:5px;font-size:11px;">{{ $workOrder->issuer->name ?? '—' }}</p>
        </div>
        <div>
            <div class="line">Received By (Signature)</div>
        </div>
        <div>
            <div class="line">Date</div>
        </div>
    </div>
</body>
</html>
