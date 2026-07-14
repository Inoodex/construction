<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Punch List - {{ $punchList->punch_list_number }}</title>
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
                <div class="doc-number">{{ $punchList->punch_list_number }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $punchList->title }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $punchList->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize">{{ $punchList->status }}</td>
                </tr>
                <tr>
                    <th>Inspection Date</th>
                    <td>{{ $punchList->inspection_date->format('d M Y') }}</td>
                    <th>Due Date</th>
                    <td>{{ $punchList->due_date?->format('d M Y') ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $punchList->creator->name ?? 'N/A' }}</td>
                    <th>Completion</th>
                    <td>{{ $punchList->completion_percent }}%</td>
                </tr>
            </table>

            @if($punchList->description)
                <div class="detail-box">
                    <h3>Description</h3>
                    <p>{{ $punchList->description }}</p>
                </div>
            @endif

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th style="width:25%">Description</th>
                        <th style="width:15%">Location</th>
                        <th style="width:12%">Trade</th>
                        <th style="width:10%">Priority</th>
                        <th style="width:14%">Status</th>
                        <th style="width:20%">Assigned To</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($punchList->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->location ?? '—' }}</td>
                            <td>{{ ucfirst($item->trade) }}</td>
                            <td @if($item->priority === 'critical') style="color:red;" @endif>{{ ucfirst($item->priority) }}</td>
                            <td>{{ str_replace('_', ' ', ucfirst($item->status)) }}</td>
                            <td>{{ $item->assigned_to ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; color:#999;">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
