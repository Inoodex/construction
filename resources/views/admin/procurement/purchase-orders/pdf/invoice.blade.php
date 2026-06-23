<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Purchase Order - {{ $po->po_number }}</title>
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
            /* width: 210mm;
            height: 297mm; */
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
        /* .content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 80px 25px 0 25px;
            box-sizing: border-box;
        } */
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
                                <th colspan="2">Vendor,</th>
                            </tr>
                            <tr>
                                <td>{{ $po->vendor->name ?? 'N/A' }}</td>
                            </tr>
                            @if($po->vendor && $po->vendor->address)
                            <tr>
                                <td>{{ $po->vendor->address }}</td>
                            </tr>
                            @endif
                            @if($po->vendor && $po->vendor->phone)
                            <tr>
                                <td>{{ $po->vendor->phone }}</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top; text-align: right;">
                        <div style="display: inline-block; background-color: #263a79; color: white; padding: 7px 12px; margin-bottom: 6px; font-size: 13px; font-weight: bold;">PO No: {{ $po->po_number }}</div>
                        <p style="margin: 4px 0; font-size: 12px;"><strong>Date:</strong> {{ $po->order_date->format('Y-m-d') }}</p>
                        @if($po->requisition && $po->requisition->requisition_number)
                        <p style="margin: 4px 0; font-size: 12px;"><strong>Requisition:</strong> {{ $po->requisition->requisition_number }}</p>
                        @endif
                    </td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 7%;">SL NO.</th>
                        <th style="width: 20%;" class="text-center">MATERIAL</th>
                        <th style="width: 13%;">QUANTITY</th>
                        <th style="width: 15%;">UNIT PRICE</th>
                        <th style="width: 15%;">TOTAL</th>
                        {{-- <th style="width: 10%;">CUR</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($po->items as $item)
                        <tr>
                            <td class="table-shade">{{ $loop->index + 1 }}</td>
                            <td class="text-center">{{ $item->material->name ?? 'Unknown' }}</td>
                            <td>{{ number_format($item->quantity, 2) }} {{ $item->material->unit ?? '-' }}</td>
                            <td class="table-shade">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="table-shade">{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                            {{-- <td>BDT</td> --}}
                        </tr>
                    @endforeach

                    <tr class="summary-row">
                        <td colspan="3"></td>
                        <td class="summary-label">SUB TOTAL:</td>
                        <td class="table-shade">{{ number_format($po->total_amount, 2) }}</td>
                        {{-- <td>BDT</td> --}}
                    </tr>
                    <tr class="summary-row" style="font-size:13px;color:#fff;">
                        <td colspan="3"></td>
                        <td class="summary-label" style="color:#263a79;">TOTAL:</td>
                        <td style="color:#fff; background-color:#263a79;">{{ number_format($po->total_amount, 2) }}</td>
                        {{-- <td style="color:#fff;">BDT</td> --}}
                    </tr>
                </tbody>
            </table>

            <div class="terms-box">
                <strong>Terms &amp; Conditions:</strong><br/>
                1. Delivery must be completed within the agreed timeline.<br/>
                2. All materials must meet the specified quality standards.<br/>
                3. This PO is subject to the company's standard procurement terms.<br/>
                4. Invoice must reference this PO number for processing.
            </div>
        </div>
    </div>

</body>

</html>