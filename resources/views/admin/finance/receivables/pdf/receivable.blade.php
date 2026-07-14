<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receivable - {{ $receivable->receivable_number }}</title>
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
        .meta-box {
            text-align: right;
            font-size: 12px;
        }
        .meta-box p {
            margin: 4px 0;
        }
        .doc-number {
            display: inline-block;
            background-color: #263a79;
            color: white;
            padding: 7px 12px;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: bold;
        }
        .detail-table {
            margin-top: 18px;
            border: 1px solid #263a79;
            font-size: 11px;
        }
        .detail-table th {
            background-color: #263a79;
            color: #fff;
            padding: 8px 10px;
            font-weight: normal;
            text-align: left;
            width: 35%;
        }
        .detail-table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        .detail-table tr:nth-child(even) td {
            background-color: #eaecf2;
        }
        .summary-table {
            margin-top: 18px;
            border: 1px solid #263a79;
            font-size: 11px;
        }
        .summary-table th {
            color: #fff;
            padding: 7px 5px;
            font-weight: normal;
            text-align: center;
        }
        .summary-table th:nth-child(odd) {
            background-color: #263a79;
        }
        .summary-table th:nth-child(even) {
            background-color: #c09f5a;
        }
        .summary-table td {
            padding: 5px;
            border: 1px solid #263a79;
            text-align: center;
        }
        .summary-table .text-left {
            text-align: left;
        }
        .summary-row td {
            padding: 5px;
            border: 1px solid #263a79;
            font-weight: bold;
            font-size: 11px;
        }
        .summary-table .summary-label {
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
        .badge {
            padding: 3px 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pending { background-color: #f1c40f; color: #333; }
        .badge-partial { background-color: #e67e22; color: #fff; }
        .badge-paid { background-color: #00ab55; color: #fff; }
        .badge-overdue { background-color: #e7515a; color: #fff; }
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
                                <td style="font-weight:bold;">{{ $receivable->payer_name }}</td>
                            </tr>
                            @if($receivable->project)
                            <tr>
                                <td>{{ $receivable->project->name }}</td>
                            </tr>
                            @if($receivable->project->description)
                            <tr>
                                <td style="font-size:10px;color:#666;">{{ $receivable->project->description }}</td>
                            </tr>
                            @endif
                            @endif
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top; text-align: right;">
                        <div class="doc-number">AR No: {{ $receivable->receivable_number }}</div>
                        <p style="margin: 4px 0; font-size: 12px;"><strong>Due Date:</strong> {{ $receivable->due_date->format('Y-m-d') }}</p>
                        <p style="margin: 4px 0; font-size: 12px;"><strong>Status:</strong> {{ strtoupper($receivable->status) }}</p>
                    </td>
                </tr>
            </table>

            <table class="detail-table">
                <tr>
                    <th>Receivable Number</th>
                    <td>{{ $receivable->receivable_number }}</td>
                </tr>
                <tr>
                    <th>Payer</th>
                    <td>{{ $receivable->payer_name }}</td>
                </tr>
                @if($receivable->description)
                <tr>
                    <th>Description</th>
                    <td>{{ $receivable->description }}</td>
                </tr>
                @endif
            </table>

            <table class="summary-table">
                <thead>
                    <tr>
                        <th style="width: 40%;" class="text-center">DESCRIPTION</th>
                        <th style="width: 20%;">AMOUNT (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left">Total Amount</td>
                        <td>{{ number_format($receivable->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Paid Amount</td>
                        <td style="color:#00ab55;">{{ number_format($receivable->paid_amount, 2) }}</td>
                    </tr>

                    <tr class="summary-row" style="font-size:13px;color:#fff;">
                        <td class="summary-label" style="color:#263a79;">DUE AMOUNT:</td>
                        <td style="color:#fff; background-color:#263a79;">{{ number_format($receivable->due_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            @if($receivable->payments->count())
            <table class="summary-table" style="margin-top:14px;">
                <thead>
                    <tr>
                        <th style="width:12%;">#</th>
                        <th style="width:22%;">DATE</th>
                        <th style="width:18%;">METHOD</th>
                        <th style="width:22%;">REFERENCE</th>
                        <th style="width:26%;">AMOUNT (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receivable->payments as $p)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $p->payment_date->format('Y-m-d') }}</td>
                        <td>{{ $p->payment_method ?? '—' }}</td>
                        <td>{{ $p->reference ?? '—' }}</td>
                        <td class="text-success">{{ number_format($p->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            @if($receivable->notes)
            <div class="terms-box" style="margin-top:10px;">
                <strong>Notes:</strong><br/>
                {{ $receivable->notes }}
            </div>
            @endif

            <div class="terms-box" style="margin-top:10px;">
                <strong>Terms &amp; Conditions:</strong><br/>
                1. Payment is due within the agreed terms from the receivable date.<br/>
                2. Please reference the receivable number when making payment.<br/>
                3. Late payments may be subject to applicable interest charges.
            </div>
        </div>
    </div>

</body>

</html>
