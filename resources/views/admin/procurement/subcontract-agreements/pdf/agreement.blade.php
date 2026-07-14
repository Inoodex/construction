<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subcontract Agreement - {{ $subcontractAgreement->agreement_number }}</title>
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
                <div class="doc-number">{{ $subcontractAgreement->agreement_number }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Subcontractor</th>
                    <td>{{ $subcontractAgreement->subcontractor->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td style="text-transform:capitalize;">{{ $subcontractAgreement->status }}</td>
                </tr>
                <tr>
                    <th>Project</th>
                    <td>{{ $subcontractAgreement->project->name ?? 'N/A' }}</td>
                    <th>Agreement Date</th>
                    <td>{{ $subcontractAgreement->agreement_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <th>Start Date</th>
                    <td>{{ $subcontractAgreement->start_date->format('d M Y') }}</td>
                    <th>End Date</th>
                    <td>{{ $subcontractAgreement->end_date?->format('d M Y') ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Retention</th>
                    <td>{{ $subcontractAgreement->retention_percentage }}%</td>
                    <th>Retention Amount</th>
                    <td>{{ number_format($subcontractAgreement->retentionAmount(), 2) }}</td>
                </tr>
            </table>

            <div class="value-box">
                <div class="label">Contract Value</div>
                <div class="amount">{{ number_format($subcontractAgreement->contract_value, 2) }}</div>
            </div>

            @if($subcontractAgreement->scope_of_work)
            <div class="detail-box" style="margin-top:15px;">
                <h3>Scope of Work</h3>
                <p>{{ $subcontractAgreement->scope_of_work }}</p>
            </div>
            @endif

            @if($subcontractAgreement->payment_terms)
            <div class="detail-box" style="margin-top:10px;">
                <h3>Payment Terms</h3>
                <p>{{ $subcontractAgreement->payment_terms }}</p>
            </div>
            @endif

            @if($subcontractAgreement->special_conditions)
            <div class="detail-box" style="margin-top:10px;">
                <h3>Special Conditions</h3>
                <p>{{ $subcontractAgreement->special_conditions }}</p>
            </div>
            @endif

            @if($subcontractAgreement->insurance_requirements)
            <div class="notes-box" style="margin-top:15px;">
                <strong>Insurance Requirements:</strong><br/>
                {{ $subcontractAgreement->insurance_requirements }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>
