<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IPA - {{ $ipa->ipa_number }}</title>
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
        .items-table { margin-top: 15px; border: 1px solid #263a79; font-size: 9px; }
        .items-table th { color: #fff; padding: 6px 4px; font-weight: normal; text-align: center; }
        .items-table th:nth-child(odd) { background-color: #263a79; }
        .items-table th:nth-child(even) { background-color: #c09f5a; }
        .items-table td { padding: 5px 4px; border: 1px solid #263a79; text-align: center; }
        .items-table .text-left { text-align: left; }
        .items-table .table-shade { background-color: #eaecf2; }
        .summary-row td { padding: 6px 5px; border: 1px solid #263a79; font-weight: bold; font-size: 11px; }
        .summary-label { text-align: right; }
        .summary-card-label { font-size: 9px; color: #666; text-transform: uppercase; text-align: center; }
        .summary-value { font-size: 16px; font-weight: bold; color: #263a79; margin-top: 4px; }
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
                <div class="doc-number">{{ $ipa->ipa_number }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Project</th>
                    <td>{{ $ipa->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ $ipa->status }}</td>
                </tr>
                <tr>
                    <th>Application Date</th>
                    <td>{{ $ipa->application_date->format('d M Y') }}</td>
                    <th>Retention Rate</th>
                    <td>{{ $ipa->retention_rate }}%</td>
                </tr>
                <tr>
                    <th>Period From</th>
                    <td>{{ $ipa->period_start->format('d M Y') }}</td>
                    <th>Period To</th>
                    <td>{{ $ipa->period_end->format('d M Y') }}</td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:6%;">#</th>
                        <th style="width:30%;" class="text-left">DESCRIPTION</th>
                        <th style="width:6%;">UNIT</th>
                        <th style="width:8%;">PREV QTY</th>
                        <th style="width:8%;">CUR QTY</th>
                        <th style="width:8%;">CUM QTY</th>
                        <th style="width:10%;">UNIT PRICE</th>
                        <th style="width:10%;">PREV AMT</th>
                        <th style="width:10%;">CUR AMT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ipa->items as $item)
                    <tr>
                        <td class="table-shade">{{ $item->item_number }}</td>
                        <td class="text-left">{{ Str::limit($item->description, 30) }}</td>
                        <td>{{ $item->unit }}</td>
                        <td class="table-shade">{{ number_format($item->previous_quantity, 2) }}</td>
                        <td>{{ number_format($item->current_quantity, 2) }}</td>
                        <td class="table-shade">{{ number_format($item->cumulative_quantity, 2) }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td class="table-shade">{{ number_format($item->previous_amount, 2) }}</td>
                        <td class="table-shade">{{ number_format($item->current_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>                        <td colspan="9" style="text-align:center;padding:10px;color:#888;">No items</td></tr>
                    @endforelse
                    <tr class="summary-row">
                        <td colspan="8" class="summary-label">PREVIOUS CUMULATIVE:</td>
                        <td class="table-shade">{{ number_format($ipa->previous_cumulative_amount, 2) }}</td>
                    </tr>
                    <tr class="summary-row">
                        <td colspan="8" class="summary-label">APPLIED AMOUNT:</td>
                        <td class="table-shade">{{ number_format($ipa->applied_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <table style="width:100%;margin-top:15px;">
                <tr>
                    <td style="width:33%;padding:12px;text-align:center;border:1px solid #ddd;">
                        <div class="summary-card-label">Certified Amount</div>
                        <div class="summary-value">{{ number_format($ipa->certified_amount, 2) }}</div>
                    </td>
                    <td style="width:33%;padding:12px;text-align:center;border:1px solid #ddd;border-left:none;border-right:none;">
                        <div class="summary-card-label">Retention ({{ $ipa->retention_rate }}%)</div>
                        <div class="summary-value" style="color:#e7515a;">{{ number_format($ipa->retention_amount, 2) }}</div>
                    </td>
                    <td style="width:34%;padding:12px;text-align:center;border:1px solid #ddd;">
                        <div class="summary-card-label">Net Amount</div>
                        <div class="summary-value" style="color:#00ab55;">{{ number_format($ipa->net_amount, 2) }}</div>
                    </td>
                </tr>
            </table>

            @if($ipa->notes)
            <div class="notes-box" style="margin-top:15px;">
                <strong>Notes:</strong><br/>
                {{ $ipa->notes }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>
