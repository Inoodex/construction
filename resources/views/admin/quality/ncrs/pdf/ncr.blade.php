<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Non-Conformance Report - {{ $ncr->ncr_number }}</title>
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
                <div class="doc-number">{{ $ncr->ncr_number }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $ncr->title }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $ncr->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ str_replace('_', ' ', $ncr->status) }}</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>{{ ucfirst($ncr->category) }}</td>
                    <th>Severity</th>
                    <td>
                        @if($ncr->severity === 'critical')
                            <span style="color:red; font-weight:bold;">{{ ucfirst($ncr->severity) }}</span>
                        @else
                            {{ ucfirst($ncr->severity) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Identified Date</th>
                    <td>{{ $ncr->identified_date->format('d M Y') }}</td>
                    <th>Due Date</th>
                    <td>{{ $ncr->due_date?->format('d M Y') ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Identified By</th>
                    <td>{{ $ncr->identifier->name ?? 'N/A' }}</td>
                    <th>Location</th>
                    <td>{{ $ncr->location ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $ncr->creator->name ?? 'N/A' }}</td>
                    <th>Closed Date</th>
                    <td>{{ $ncr->closed_date?->format('d M Y') ?? '—' }}</td>
                </tr>
            </table>

            @if($ncr->description)
                <div class="detail-box">
                    <h3>Description</h3>
                    <p>{{ $ncr->description }}</p>
                </div>
            @endif

            @if($ncr->root_cause)
                <div class="detail-box">
                    <h3>Root Cause</h3>
                    <p>{{ $ncr->root_cause }}</p>
                </div>
            @endif

            @if($ncr->corrective_action)
                <div class="detail-box">
                    <h3>Corrective Action</h3>
                    <p>{{ $ncr->corrective_action }}</p>
                </div>
            @endif

            @if($ncr->preventive_action)
                <div class="detail-box">
                    <h3>Preventive Action</h3>
                    <p>{{ $ncr->preventive_action }}</p>
                </div>
            @endif

            @if($ncr->notes)
                <div class="notes-box">
                    <strong>Notes:</strong><br/>
                    {{ $ncr->notes }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
