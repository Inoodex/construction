<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tender - {{ $tender->tender_number }}</title>
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
        .value-box { margin-top: 15px; padding: 15px; border: 2px solid #263a79; background-color: #f0f3f9; text-align: center; }
        .value-box .amount { font-size: 24px; font-weight: bold; color: #263a79; }
        .value-box .label { font-size: 10px; color: #666; text-transform: uppercase; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif
        <div class="content">
            <div style="margin-bottom:15px;">
                <div class="doc-number">{{ $tender->tender_number }}</div>
            </div>
            <table class="meta-table">
                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $tender->title }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $tender->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td>{{ ucfirst($tender->status) }}</td>
                </tr>
                <tr>
                    <th>Issue Date</th>
                    <td>{{ $tender->issue_date->format('d M Y') }}</td>
                    <th>Close Date</th>
                    <td>{{ $tender->close_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $tender->creator->name ?? 'N/A' }}</td>
                    <th>Total Bids</th>
                    <td>{{ $tender->bids->count() }}</td>
                </tr>
            </table>

            @if($tender->description)
                <div class="detail-box">
                    <h3>Description</h3>
                    <p>{!! nl2br(e($tender->description)) !!}</p>
                </div>
            @endif

            <table class="items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vendor</th>
                        <th>Bid Amount</th>
                        <th>Tech Score</th>
                        <th>Financial Score</th>
                        <th>Total Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tender->bids as $index => $bid)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $bid->vendor->name ?? 'N/A' }}</td>
                            <td>{{ number_format($bid->bid_amount, 2) }}</td>
                            <td>{{ $bid->technical_score ?? '—' }}</td>
                            <td>{{ $bid->financial_score ?? '—' }}</td>
                            <td>{{ $bid->total_score ?? '—' }}</td>
                            <td>{{ ucfirst($bid->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No bids found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
