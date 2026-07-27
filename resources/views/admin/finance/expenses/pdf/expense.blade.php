<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense - {{ $expense->title }}</title>
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
        .amount-table { width: 100%; margin-top: 10px; font-size: 11px; }
        .amount-table th { background-color: #263a79; color: white; padding: 6px 10px; text-align: left; font-weight: bold; border: 1px solid #263a79; }
        .amount-table td { padding: 6px 10px; border: 1px solid #ddd; }
        .amount-table tr:nth-child(even) { background-color: #f5f7fa; }
        .summary-total td { padding: 8px 10px; border: 1px solid #263a79; font-weight: bold; color: #fff; background-color: #263a79; font-size: 12px; }
        .text-right { text-align: right; }
        .notes-box { margin-top: 20px; padding: 10px 14px; border-left: 4px solid #263a79; background-color: #eaecf2; font-size: 11px; line-height: 1.5; }
        .notes-box strong { color: #263a79; }
        .status-badge { display: inline-block; padding: 4px 16px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-left: 10px; vertical-align: middle; }
        .status-draft { background-color: #fff3cd; color: #856404; border: 1px solid #ffc107; }
        .status-paid { background-color: #d4edda; color: #155724; border: 1px solid #28a745; }
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif
        <div class="content">
            <div style="margin-bottom:15px;">
                <div class="doc-number">EXPENSE VOUCHER</div>
                {{-- <span class="status-badge status-{{ $expense->status }}">{{ $expense->status }}</span> --}}
            </div>

            <table class="meta-table">
                <tr>
                    <th>Title</th>
                    <td colspan="3">{{ $expense->title }}</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>{{ $expense->category->label ?? 'N/A' }}</td>
                    <th>Date</th>
                    <td>{{ $expense->expense_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <th>Vendor</th>
                    <td>{{ $expense->vendor->name ?? 'N/A' }}</td>
                    <th>Project</th>
                    <td>{{ $expense->project->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Payment Method</th>
                    <td>{{ $expense->payment_method ? str_replace('_', ' ', ucfirst($expense->payment_method)) : 'N/A' }}</td>
                    <th>Reference #</th>
                    <td>{{ $expense->reference_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $expense->creator->name ?? 'N/A' }}</td>
                    <th>Status</th>
                    <td><span class="status-badge status-{{ $expense->status }}">{{ $expense->status }}</span></td>
                </tr>
            </table>

            <table class="amount-table">
                <thead>
                    <tr>
                        <th style="width:60%;">Description</th>
                        <th class="text-right" style="width:20%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $expense->title }}</strong>
                            @if($expense->description)
                                <br/><span style="font-size:10px; color:#666;">{{ $expense->description }}</span>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($expense->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" style="font-weight:bold;">Tax ({{ $expense->tax_rate }}%)</td>
                        <td class="text-right">{{ number_format($expense->tax_amount, 2) }}</td>
                    </tr>
                    <tr class="summary-total">
                        <td style="text-align:right;">TOTAL AMOUNT</td>
                        <td class="text-right">{{ number_format($expense->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- @if($expense->notes)
                <div class="notes-box" style="margin-top:20px;">
                    <strong>Notes:</strong><br/>
                    {{ $expense->notes }}
                </div>
            @endif --}}

            <div class="notes-box" style="margin-top:10px;">
                <strong>Notes:</strong><br/>
                1. This is a system-generated expense voucher.<br/>
                2. All amounts are in local currency unless otherwise stated.
            </div>
        </div>
    </div>
</body>
</html>
