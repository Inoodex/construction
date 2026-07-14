<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proposal - {{ $proposal->proposal_number }}</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: sans-serif; color: #333; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        .wrapper { position: relative; width: 100%; }
        .bg-img { position: absolute; top: 0; left: 0; width: 210mm; height: 297mm; }
        .content { position: relative; padding: 120px 25px 0 25px; box-sizing: border-box; }
        .doc-number { display: inline-block; background-color: #263a79; color: white; padding: 6px 14px; font-size: 14px; font-weight: bold; margin-bottom: 8px; }
        .meta-label { font-size: 10px; color: #888; text-transform: uppercase; margin-bottom: 2px; }
        .meta-value { font-size: 12px; font-weight: bold; }
        .items-table { margin-top: 15px; border: 1px solid #263a79; font-size: 10px; }
        .items-table th { color: #fff; padding: 7px 5px; font-weight: normal; text-align: center; }
        .items-table th:nth-child(odd) { background-color: #263a79; }
        .items-table th:nth-child(even) { background-color: #c09f5a; }
        .items-table td { padding: 6px 5px; border: 1px solid #263a79; text-align: center; }
        .items-table .text-left { text-align: left; }
        .items-table .table-shade { background-color: #eaecf2; }
        .summary-row td { padding: 6px 5px; border: 1px solid #263a79; font-weight: bold; font-size: 11px; }
        .summary-label { text-align: right; }
        .total-row td { background-color: #263a79; color: white; font-size: 13px; }
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
                <div class="doc-number">{{ $proposal->proposal_number }}</div>
            </div>

            <table style="width:100%;margin-bottom:15px;">
                <tr>
                    <td style="width:50%;vertical-align:top;">
                        <div class="meta-label">Prepared For</div>
                        <div class="meta-value">{{ $proposal->client?->company_name ?? $proposal->lead?->company_name ?? 'N/A' }}</div>
                        @if($proposal->client?->email)
                            <div style="font-size:10px;color:#666;">{{ $proposal->client->email }}</div>
                        @endif
                    </td>
                    <td style="width:50%;vertical-align:top;text-align:right;">
                        <p style="margin:2px 0;"><strong>Status:</strong> {{ strtoupper($proposal->status) }}</p>
                        @if($proposal->valid_until)
                            <p style="margin:2px 0;"><strong>Valid Until:</strong> {{ $proposal->valid_until->format('d M Y') }}</p>
                        @endif
                    </td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:6%;">SL</th>
                        <th style="width:40%;" class="text-center">DESCRIPTION</th>
                        <th style="width:10%;">UNIT</th>
                        <th style="width:12%;">QTY</th>
                        <th style="width:15%;">UNIT PRICE</th>
                        <th style="width:17%;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposal->items as $item)
                    <tr>
                        <td class="table-shade">{{ $loop->index + 1 }}</td>
                        <td class="text-left">{{ $item->description }}</td>
                        <td>{{ $item->unit ?? '—' }}</td>
                        <td>{{ number_format($item->quantity, 2) }}</td>
                        <td class="table-shade">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="table-shade">{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:10px;color:#888;">No items</td></tr>
                    @endforelse
                    <tr class="summary-row">
                        <td colspan="5" class="summary-label">SUB TOTAL:</td>
                        <td class="table-shade">{{ number_format($proposal->subtotal, 2) }}</td>
                    </tr>
                    <tr class="summary-row">
                        <td colspan="5" class="summary-label">TAX ({{ $proposal->tax_rate }}%):</td>
                        <td class="table-shade">{{ number_format($proposal->tax_amount, 2) }}</td>
                    </tr>
                    <tr class="summary-row total-row">
                        <td colspan="5" class="summary-label" style="color:white;">TOTAL:</td>
                        <td>{{ number_format($proposal->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            @if($proposal->notes)
            <div class="notes-box">
                <strong>Notes:</strong><br/>
                {{ $proposal->notes }}
            </div>
            @endif

            <div class="notes-box" style="margin-top:10px;">
                <strong>Terms &amp; Conditions:</strong><br/>
                1. This proposal is valid until the date specified above.<br/>
                2. Prices are subject to change after the validity period.<br/>
                3. Payment terms as per separate agreement.
            </div>
        </div>
    </div>
</body>
</html>
