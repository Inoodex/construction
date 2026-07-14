<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Material Submittal - {{ $materialSubmittal->submittal_number }}</title>
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
        .notes-box { margin-top: 20px; padding: 10px 14px; border-left: 4px solid #263a79; background-color: #eaecf2; font-size: 11px; line-height: 1.5; }
        .notes-box strong { color: #263a79; }
        .detail-box { margin-top: 15px; padding: 12px 14px; border: 1px solid #ddd; background-color: #f9f9f9; }
        .detail-box h3 { margin: 0 0 8px 0; color: #263a79; font-size: 13px; }
        .detail-box p { margin: 0; line-height: 1.5; }
        .value-box { margin-top: 15px; padding: 15px; border: 2px solid #263a79; background-color: #f0f3f9; text-align: center; }
        .value-box .amount { font-size: 24px; font-weight: bold; color: #263a79; }
        .value-box .label { font-size: 10px; color: #666; text-transform: uppercase; margin-top: 4px; }
        .summary-table { width: 100%; margin-top: 15px; font-size: 11px; }
        .summary-table td { padding: 8px 10px; border: 1px solid #ddd; }
        .summary-table .summary-label { background-color: #eaecf2; color: #263a79; font-weight: bold; width: 50%; }
        .summary-table .summary-value { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif
        <div class="content">
            <div class="doc-number">{{ $materialSubmittal->submittal_number }}</div>

            <table class="meta-table">
                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $materialSubmittal->title }}</td>
                </tr>
                <tr>
                    <th>Material Name</th>
                    <td>{{ $materialSubmittal->material_name }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize">{{ str_replace('_', ' ', $materialSubmittal->status) }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $materialSubmittal->project?->name ?? '—' }}</td>
                    <th>Submitted By</th>
                    <td>{{ $materialSubmittal->submitter?->name ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Manufacturer</th>
                    <td>{{ $materialSubmittal->manufacturer ?? '—' }}</td>
                    <th>Brand</th>
                    <td>{{ $materialSubmittal->brand ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Model/Reference</th>
                    <td>{{ $materialSubmittal->model_reference ?? '—' }}</td>
                    <th>Quantity/Unit</th>
                    <td>{{ $materialSubmittal->quantity_unit ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Submitted Date</th>
                    <td>{{ $materialSubmittal->submitted_date?->format('d M Y') ?? '—' }}</td>
                    <th>Reviewed By</th>
                    <td>{{ $materialSubmittal->reviewer?->name ?? '—' }}</td>
                </tr>
            </table>

            @if(!empty($materialSubmittal->description))
                <div class="detail-box">
                    <h3>Description</h3>
                    <p>{{ $materialSubmittal->description }}</p>
                </div>
            @endif

            @if(!empty($materialSubmittal->specification_details))
                <div class="detail-box">
                    <h3>Specification Details</h3>
                    <p>{{ $materialSubmittal->specification_details }}</p>
                </div>
            @endif

            @if(!empty($materialSubmittal->review_comments))
                <div class="notes-box">
                    <strong>Review Comments:</strong><br/>
                    {{ $materialSubmittal->review_comments }}
                </div>
            @endif

            @if($materialSubmittal->resubmission_deadline)
                <div class="notes-box">
                    <strong>Resubmission Deadline:</strong> {{ $materialSubmittal->resubmission_deadline->format('d M Y') }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
