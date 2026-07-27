<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Material Takeoff - {{ $materialTakeoff->description }}</title>
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
        .detail-table { width: 100%; margin-top: 10px; font-size: 11px; }
        .detail-table th { background-color: #263a79; color: white; padding: 6px 10px; text-align: left; font-weight: bold; border: 1px solid #263a79; }
        .detail-table td { padding: 6px 10px; border: 1px solid #ddd; }
        .detail-table tr:nth-child(even) { background-color: #f5f7fa; }
        .total-row td { padding: 8px 10px; border: 1px solid #263a79; font-weight: bold; color: #fff; background-color: #263a79; font-size: 12px; }
        .text-right { text-align: right; }
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
                <div class="doc-number">MATERIAL TAKEOFF</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Description</th>
                    <td colspan="3">{{ $materialTakeoff->description }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $materialTakeoff->project->name ?? 'N/A' }}</td>
                    <th>Source Drawing</th>
                    <td>{{ $materialTakeoff->source_drawing ?? '—' }}</td>
                </tr>
                <tr>
                    <th>BOQ Item</th>
                    <td>{{ $materialTakeoff->boqItem?->item_number ?? '—' }}{{ $materialTakeoff->boqItem ? ' - ' . $materialTakeoff->boqItem->description : '' }}</td>
                    <th>Unit</th>
                    <td>{{ $materialTakeoff->unit ?? '—' }}</td>
                </tr>
            </table>

            <table class="detail-table">
                <thead>
                    <tr>
                        <th style="width:40%;">Item</th>
                        <th class="text-right" style="width:15%;">Quantity</th>
                        <th class="text-right" style="width:15%;">Unit Price</th>
                        <th class="text-right" style="width:15%;">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $materialTakeoff->description }}</td>
                        <td class="text-right">{{ number_format($materialTakeoff->quantity, 2) }} {{ $materialTakeoff->unit }}</td>
                        <td class="text-right">{{ number_format($materialTakeoff->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($materialTakeoff->total_price, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" style="text-align:right;">TOTAL</td>
                        <td class="text-right">{{ number_format($materialTakeoff->total_price, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            @if($materialTakeoff->notes)
                <div class="notes-box">
                    <strong>Notes:</strong><br/>
                    {{ $materialTakeoff->notes }}
                </div>
            @endif

            <div class="notes-box" style="margin-top:10px;">
                <strong>Notes:</strong><br/>
                1. This is a system-generated material takeoff document.<br/>
                2. Total Price = Quantity &times; Unit Price.<br/>
                3. Generated on {{ now()->format('d M Y H:i') }}.
            </div>
        </div>
    </div>
</body>
</html>
