<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contract - {{ $contract->contract_number }}</title>
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
        .value-box { margin-top: 15px; padding: 15px; border: 2px solid #263a79; background-color: #f0f3f9; text-align: center; }
        .value-box .amount { font-size: 24px; font-weight: bold; color: #263a79; }
        .value-box .label { font-size: 10px; color: #666; text-transform: uppercase; margin-top: 4px; }
        .clauses { margin-top: 20px; }
        .clauses h3 { color: #263a79; font-size: 13px; margin: 0 0 8px 0; }
        .clauses table { font-size: 10px; }
        .clauses table th { text-align: left; padding: 5px 8px; background-color: #eaecf2; border: 1px solid #ddd; color: #263a79; }
        .clauses table td { padding: 5px 8px; border: 1px solid #ddd; }
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
                <div class="doc-number">{{ $contract->contract_number }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Client</th>
                    <td>{{ $contract->client_name }}</td>
                    <th>Contract Type</th>
                    <td style="text-transform:capitalize;">{{ str_replace('_', ' ', $contract->contract_type) }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $contract->project->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ $contract->status }}</td>
                </tr>
                <tr>
                    <th>Signing Date</th>
                    <td>{{ $contract->signing_date?->format('d M Y') ?? 'N/A' }}</td>
                    <th>Currency</th>
                    <td>{{ $contract->currency ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Commencement Date</th>
                    <td>{{ $contract->commencement_date?->format('d M Y') ?? 'N/A' }}</td>
                    <th>Completion Date</th>
                    <td>{{ $contract->completion_date?->format('d M Y') ?? 'N/A' }}</td>
                </tr>
                @if($contract->extended_completion_date)
                <tr>
                    <th>Extended Completion</th>
                    <td>{{ $contract->extended_completion_date->format('d M Y') }}</td>
                    <th></th>
                    <td></td>
                </tr>
                @endif
            </table>

            <div class="value-box">
                <div class="label">Contract Value</div>
                <div class="amount">{{ number_format($contract->contract_value, 2) }}</div>
            </div>

            <div class="clauses" style="margin-top:20px;">
                <h3>Key Terms</h3>
                <table>
                    <tr>
                        <th style="width:35%;">Retention</th>
                        <td>{{ $contract->retention_percentage ? $contract->retention_percentage . '%' : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Liquidated Damages Rate</th>
                        <td>{{ $contract->liquidated_damages_rate ? number_format($contract->liquidated_damages_rate, 2) . ' per day' : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Advance Payment</th>
                        <td>{{ $contract->advance_payment_percentage ? $contract->advance_payment_percentage . '%' : 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            @if($contract->notes)
            <div class="notes-box" style="margin-top:15px;">
                <strong>Notes:</strong><br/>
                {{ $contract->notes }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>
