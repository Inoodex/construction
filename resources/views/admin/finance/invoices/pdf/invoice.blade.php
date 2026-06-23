<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $inv->invoice_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            color: #333;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .wrapper {
            position: relative;
            width: 100%;
        }
        .bg-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
        }
         .content {
            position: relative;
            padding: 80px 25px 0 25px;
            box-sizing: border-box;
        }
        .info-box {
            padding: 8px;
        }
        .info-box th {
            text-align: left;
            font-size: 15px;
            color: #263a79;
            padding-bottom: 4px;
        }
        .info-box td {
            font-size: 12px;
            line-height: 1.5;
        }
        .invoice-meta {
            text-align: right;
            font-size: 12px;
        }
        .invoice-meta p {
            margin: 4px 0;
        }
        .items-table {
            margin-top: 18px;
            border: 1px solid #263a79;
            font-size: 10px;
        }
        .items-table th {
            color: #fff;
            padding: 7px 5px;
            font-weight: normal;
            text-align: center;
        }
        .items-table th:nth-child(odd) {
            background-color: #263a79;
        }
        .items-table th:nth-child(even) {
            background-color: #c09f5a;
        }
        .items-table td {
            padding: 5px;
            border: 1px solid #263a79;
            text-align: center;
        }
        .items-table .text-left {
            text-align: left;
        }
        .summary-row td {
            padding: 5px;
            border: 1px solid #263a79;
            font-weight: bold;
            font-size: 11px;
        }
        .items-table .summary-label {
            text-align: right;
        }
        .total-label {
            text-align: right;
            font-weight: bold;
        }
        .table-shade {
            background-color: #eaecf2;
        }
        .terms-box {
            margin-top: 20px;
            padding: 8px 12px;
            border-left: 4px solid #263a79;
            background-color: #eaecf2;
            font-size: 11px;
            color: #322014;
            line-height: 1.5;
        }
        .terms-box strong {
            color: #263a79;
        }
        .text-success {
            color: #00ab55;
        }
        .text-danger {
            color: #e7515a;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif

        <div class="content">
            <table style="margin-top: 50px;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <table class="info-box">
                            <tr>
                                <th colspan="2">Bill To,</th>
                            </tr>
                            <tr>
                                <td>{{ $inv->project->name ?? 'N/A' }}</td>
                            </tr>
                            @if($inv->project && $inv->project->description)
                            <tr>
                                <td style="font-size:10px;color:#666;">{{ $inv->project->description }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top; text-align: right;">
                        <div style="display: inline-block; background-color: #263a79; color: white; padding: 7px 12px; margin-bottom: 6px; font-size: 13px; font-weight: bold;">INV No: {{ $inv->invoice_number }}</div>
                        <p style="margin: 4px 0; font-size: 12px;"><strong>Issue Date:</strong> {{ $inv->issue_date->format('Y-m-d') }}</p>
                        <p style="margin: 4px 0; font-size: 12px;"><strong>Due Date:</strong> {{ $inv->due_date->format('Y-m-d') }}</p>
                    </td>
                </tr>
            </table>

            @if($inv->title)
            <div style="margin-top:10px;font-size:13px;font-weight:bold;color:#263a79;">{{ $inv->title }}</div>
            @endif

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 7%;">SL NO.</th>
                        <th style="width: 40%;" class="text-center">DESCRIPTION</th>
                        <th style="width: 13%;">QUANTITY</th>
                        <th style="width: 15%;">UNIT PRICE</th>
                        <th style="width: 15%;">TOTAL (BDT)</th>
                        {{-- <th style="width: 10%;">CUR</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inv->items as $item)
                        <tr>
                            <td class="table-shade">{{ $loop->index + 1 }}</td>
                            <td class="text-center">{{ $item->description }}</td>
                            <td>{{ number_format($item->quantity, 2) }}</td>
                            <td class="table-shade">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="table-shade">{{ number_format($item->total_price, 2) }}</td>
                            {{-- <td>BDT</td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:10px;color:#888;">No items</td>
                        </tr>
                    @endforelse

                    <tr class="summary-row">
                        <td colspan="4" class="summary-label">SUB TOTAL:</td>
                        <td class="table-shade">{{ number_format($inv->subtotal, 2) }}</td>
                        {{-- <td>BDT</td> --}}
                    </tr>
                    <tr class="summary-row">
                        <td colspan="4" class="summary-label">TAX ({{ $inv->tax_rate }}%):</td>
                        <td class="table-shade">{{ number_format($inv->tax_amount, 2) }}</td>
                        {{-- <td>BDT</td> --}}
                    </tr>
                    <tr class="summary-row">
                        <td colspan="4" class="summary-label">RETENTION ({{ $inv->retention_rate }}%):</td>
                        <td class="table-shade"> - {{ number_format($inv->retention_amount, 2) }}</td>
                        {{-- <td>BDT</td> --}}
                    </tr>
                    <tr class="summary-row" style="font-size:13px;color:#fff;">
                        <td colspan="4" class="summary-label" style="color:#263a79;">TOTAL:</td>
                        <td style="color:#fff; background-color:#263a79;">{{ number_format($inv->total_amount, 2) }}</td>
                        {{-- <td style="color:#fff;">BDT</td> --}}
                    </tr>
                    <tr class="summary-row">
                        <td colspan="4" class="summary-label">PAID:</td>
                        <td class="table-shade" style="color:#00ab55;">{{ number_format($inv->paid_amount, 2) }}</td>
                        {{-- <td>BDT</td> --}}
                    </tr>
                    <tr class="summary-row">
                        <td colspan="4" class="summary-label">DUE:</td>
                        <td class="table-shade" style="color:{{ $inv->due_amount > 0 ? '#e7515a' : '#00ab55' }};">{{ number_format($inv->due_amount, 2) }}</td>
                        {{-- <td>BDT</td> --}}
                    </tr>
                </tbody>
            </table>

            @if($inv->description)
            <div class="terms-box">
                <strong>Notes:</strong><br/>
                {{ $inv->description }}
            </div>
            @endif

            <div class="terms-box" style="margin-top:10px;">
                <strong>Terms &amp; Conditions:</strong><br/>
                1. Payment is due within the agreed terms from the invoice date.<br/>
                2. Please reference the invoice number when making payment.<br/>
                3. Late payments may be subject to applicable interest charges.
            </div>
        </div>
    </div>

</body>

</html>