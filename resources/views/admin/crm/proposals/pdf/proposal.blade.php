<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
    $clientName = $proposal->client?->company_name ?? $proposal->lead?->company_name ?? 'Valued Client';
    $contactPerson = $proposal->client?->contact_person ?? $proposal->lead?->contact_name ?? null;
    $email = $proposal->client?->email ?? $proposal->lead?->email ?? null;
    $phone = $proposal->client?->phone ?? $proposal->lead?->phone ?? null;
    $address = $proposal->client?->address ?? null;
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Commercial Proposal - {{ $proposal->proposal_number }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            color: #1e293b;
            font-size: 10px;
            line-height: 1.4;
            background-color: #ffffff;
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
            z-index: -1;
        }
        .content {
            position: relative;
            padding: 120px 30px 40px 30px;
            box-sizing: border-box;
        }
        
        /* Header & Title Styling */
        .proposal-header-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .doc-number-pill {
            display: inline-block;
            background-color: #263a79;
            color: #ffffff;
            padding: 5px 12px;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.5px;
            border-radius: 3px;
        }
        .proposal-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 6px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 12px;
        }
        .status-draft { background-color: #e2e8f0; color: #475569; }
        .status-sent { background-color: #e0f2fe; color: #0369a1; }
        .status-accepted { background-color: #dcfce7; color: #15803d; }
        .status-rejected { background-color: #fee2e2; color: #b91c1c; }
        .status-expired { background-color: #f1f5f9; color: #64748b; }

        /* Meta Information Cards */
        .meta-card-table {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }
        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px 12px;
            vertical-align: top;
        }
        .meta-box-header {
            font-size: 9px;
            font-weight: 800;
            color: #263a79;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .meta-box-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .meta-box-text {
            font-size: 9.5px;
            color: #475569;
            margin-top: 2px;
        }

        /* Items Table Styling */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 9.5px;
        }
        .items-table th {
            background-color: #263a79;
            color: #ffffff;
            padding: 7px 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #263a79;
        }
        .items-table td {
            padding: 7px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        .items-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        /* Summary & Totals Block */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .summary-label {
            text-align: right;
            font-weight: bold;
            font-size: 10px;
            color: #334155;
            padding: 5px 10px;
            border: 1px solid #cbd5e1;
            background-color: #f1f5f9;
        }
        .summary-value {
            text-align: right;
            font-weight: bold;
            font-size: 10px;
            color: #0f172a;
            padding: 5px 10px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            width: 22%;
        }
        .total-label {
            background-color: #263a79 !important;
            color: #ffffff !important;
            font-size: 11px !important;
            border-color: #263a79 !important;
        }
        .total-value {
            background-color: #263a79 !important;
            color: #ffffff !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            border-color: #263a79 !important;
        }

        /* Section Cards / Notes & Terms */
        .info-box {
            margin-top: 15px;
            padding: 10px 12px;
            border-left: 3.5px solid #263a79;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 0 4px 4px 0;
            page-break-inside: avoid;
        }
        .info-box-title {
            font-size: 10px;
            font-weight: 800;
            color: #263a79;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .info-box-body {
            font-size: 9.5px;
            color: #334155;
            line-height: 1.5;
        }

        /* Signatures Section */
        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-cell {
            width: 45%;
            vertical-align: bottom;
            text-align: center;
        }
        .signature-line {
            border-top: 1px dashed #64748b;
            margin-top: 35px;
            margin-bottom: 4px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        .signature-title {
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
        }
        .signature-sub {
            font-size: 8.5px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="Background Letterhead" />
        @endif
        <div class="content">
            
            {{-- Header Row --}}
            <table class="proposal-header-table">
                <tr>
                    <td style="width: 65%; vertical-align: top;">
                        <div class="doc-number-pill">PROPOSAL # {{ $proposal->proposal_number }}</div>
                        <div class="proposal-title">{{ $proposal->title }}</div>
                    </td>
                    <td style="width: 35%; vertical-align: top; text-align: right;">
                        @php
                            $status = strtolower($proposal->status);
                            $statusClass = 'status-' . (in_array($status, ['draft','sent','accepted','rejected','expired']) ? $status : 'draft');
                        @endphp
                        <div>
                            <span class="status-badge {{ $statusClass }}">{{ strtoupper($proposal->status) }}</span>
                        </div>
                        <div style="margin-top: 6px; font-size: 9.5px; color: #64748b;">
                            <strong>Date:</strong> {{ $proposal->created_at ? $proposal->created_at->format('d M Y') : date('d M Y') }}
                        </div>
                        @if($proposal->valid_until)
                            <div style="font-size: 9.5px; color: #b91c1c; font-weight: bold; margin-top: 2px;">
                                <strong>Valid Until:</strong> {{ $proposal->valid_until->format('d M Y') }}
                            </div>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Client & Overview Meta Cards --}}
            <table class="meta-card-table">
                <tr>
                    <td style="width: 49%; vertical-align: top;" class="meta-box">
                        <div class="meta-box-header">PREPARED FOR</div>
                        <div class="meta-box-title">{{ $clientName }}</div>
                        @if($contactPerson)
                            <div class="meta-box-text"><strong>Attn:</strong> {{ $contactPerson }}</div>
                        @endif
                        @if($email)
                            <div class="meta-box-text"><strong>Email:</strong> {{ $email }}</div>
                        @endif
                        @if($phone)
                            <div class="meta-box-text"><strong>Phone:</strong> {{ $phone }}</div>
                        @endif
                        @if($address)
                            <div class="meta-box-text"><strong>Address:</strong> {{ $address }}</div>
                        @endif
                    </td>
                    <td style="width: 2%;"></td>
                    <td style="width: 49%; vertical-align: top;" class="meta-box">
                        <div class="meta-box-header">PROPOSAL SPECIFICATIONS</div>
                        <div class="meta-box-text"><strong>Proposal Ref:</strong> {{ $proposal->proposal_number }}</div>
                        <div class="meta-box-text"><strong>Issued Date:</strong> {{ $proposal->created_at ? $proposal->created_at->format('F d, Y') : date('F d, Y') }}</div>
                        <div class="meta-box-text"><strong>Validity Period:</strong> {{ $proposal->valid_until ? $proposal->valid_until->format('F d, Y') : 'Standard 30 Days' }}</div>
                        @if($proposal->creator)
                            <div class="meta-box-text"><strong>Prepared By:</strong> {{ $proposal->creator->name }}</div>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Scope & Line Items Table --}}
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">SL</th>
                        <th style="width: 45%;" class="text-left">DESCRIPTION &amp; SCOPE OF WORK</th>
                        <th style="width: 10%;" class="text-center">UNIT</th>
                        <th style="width: 10%;" class="text-right">QTY</th>
                        <th style="width: 14%;" class="text-right">UNIT PRICE</th>
                        <th style="width: 16%;" class="text-right">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposal->items as $item)
                        <tr>
                            <td class="text-center" style="background-color: #f1f5f9; font-weight: bold; color: #475569;">{{ $loop->iteration }}</td>
                            <td class="text-left" style="font-weight: 500;">{{ $item->description }}</td>
                            <td class="text-center">{{ $item->unit ?? '—' }}</td>
                            <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right" style="font-weight: bold; color: #0f172a;">{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 15px; color: #94a3b8; italic">No scope items added to this proposal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Financial Totals Summary --}}
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; vertical-align: top;"></td>
                    <td style="width: 50%; vertical-align: top;">
                        <table class="summary-table">
                            <tr>
                                <td class="summary-label">SUBTOTAL:</td>
                                <td class="summary-value">{{ number_format($proposal->subtotal, 2) }}</td>
                            </tr>
                            @if($proposal->tax_rate > 0 || $proposal->tax_amount > 0)
                                <tr>
                                    <td class="summary-label">TAX ({{ number_format($proposal->tax_rate, 2) }}%):</td>
                                    <td class="summary-value">{{ number_format($proposal->tax_amount, 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="summary-label total-label">GRAND TOTAL:</td>
                                <td class="summary-value total-value">{{ number_format($proposal->total_amount, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- Notes Section --}}
            @if($proposal->notes)
                <div class="info-box">
                    <div class="info-box-title">SPECIAL NOTES &amp; INSTRUCTIONS</div>
                    <div class="info-box-body">{!! nl2br(e($proposal->notes)) !!}</div>
                </div>
            @endif

            {{-- Standard Terms & Conditions --}}
            <div class="info-box" style="margin-top: 10px;">
                <div class="info-box-title">TERMS &amp; CONDITIONS</div>
                <div class="info-box-body">
                    1. <strong>Validity:</strong> This commercial proposal is valid until <strong>{{ $proposal->valid_until ? $proposal->valid_until->format('F d, Y') : 'the specified validity date' }}</strong>. Prices may be subject to revision thereafter.<br/>
                    2. <strong>Payment Terms:</strong> Payments shall be processed according to the milestones agreed upon in the master agreement.<br/>
                    3. <strong>Scope Variations:</strong> Any extra work or variations outside the described scope will be billed separately upon mutual consent.
                </div>
            </div>

            {{-- Dual Signature Block --}}
            <table class="signature-table">
                <tr>
                    <td class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-title">{{ $clientName }}</div>
                        <div class="signature-sub">Accepted &amp; Agreed By (Client Signature)</div>
                    </td>
                    <td style="width: 10%;"></td>
                    <td class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-title">{{ config('app.name', 'Inoodex Engineering') }}</div>
                        <div class="signature-sub">Authorized Representative Signature</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>
</html>
