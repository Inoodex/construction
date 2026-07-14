<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill of Quantities - {{ $boq->boq_number }}</title>
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
        .items-table { width: 100%; margin-top: 15px; font-size: 11px; }
        .items-table th { background-color: #263a79; color: white; padding: 8px 10px; text-align: left; font-weight: bold; border: 1px solid #263a79; }
        .items-table td { padding: 8px 10px; border: 1px solid #263a79; }
        .items-table tr:nth-child(even) { background-color: #f5f7fa; }
        .detail-box { margin-top: 15px; padding: 12px 14px; border: 1px solid #ddd; background-color: #f9f9f9; }
        .detail-box h3 { margin: 0 0 8px 0; color: #263a79; font-size: 13px; }
        .detail-box p { margin: 0; line-height: 1.5; }
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
                <div class="doc-number">{{ $boq->boq_number }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $boq->title }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $boq->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ $boq->status }}</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $boq->creator->name ?? 'N/A' }}</td>
                    <th>Total Amount</th>
                    <td>{{ number_format($boq->total_amount, 2) }}</td>
                </tr>
            </table>

            @if($boq->description)
                <div class="detail-box">
                    <h3>Description</h3>
                    <p>{{ $boq->description }}</p>
                </div>
            @endif

            <table class="items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item No.</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boq->items as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item->item_number }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; color:#999;">No items added.</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="6" style="text-align:right; font-weight:bold; background-color:#eaecf2;">Total</td>
                        <td style="text-align:right; font-weight:bold; background-color:#eaecf2;">{{ number_format($boq->items->sum('total_price'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
