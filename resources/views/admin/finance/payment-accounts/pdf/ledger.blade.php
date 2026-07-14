<!DOCTYPE html>
@php
    $bgPath = public_path('assets/images/inoodex_invoice.jpg');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Ledger - {{ $paymentAccount->name }}</title>
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
        .items-table { width: 100%; margin-top: 15px; font-size: 10px; }
        .items-table th { background-color: #263a79; color: white; padding: 6px 8px; text-align: left; font-weight: bold; border: 1px solid #263a79; }
        .items-table td { padding: 6px 8px; border: 1px solid #263a79; }
        .items-table tr:nth-child(even) { background-color: #f5f7fa; }
        .text-right { text-align: right; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>
    <div class="wrapper">
        @if (file_exists($bgPath))
            <img class="bg-img" src="{{ $bgPath }}" alt="" />
        @endif
        <div class="content">
            <div style="margin-bottom:15px;">
                <div class="doc-number">LEDGER — {{ $paymentAccount->name }}</div>
            </div>

            <table class="meta-table">
                <tr>
                    <th>Account Name</th>
                    <td>{{ $paymentAccount->name }}</td>
                    <th>Type</th>
                    <td>{{ $paymentAccount->type_label }}</td>
                </tr>
                <tr>
                    <th>Account Number</th>
                    <td>{{ $paymentAccount->account_number ?? '—' }}</td>
                    <th>Bank / Provider</th>
                    <td>{{ $paymentAccount->bank_name ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Opening Balance</th>
                    <td>{{ number_format($paymentAccount->opening_balance, 2) }}</td>
                    <th>Current Balance</th>
                    <td style="font-weight:bold;">{{ number_format($paymentAccount->current_balance, 2) }}</td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Reference</th>
                        <th class="text-right">Credit (In)</th>
                        <th class="text-right">Debit (Out)</th>
                        <th class="text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td>{{ $t->transaction_date->format('d M Y H:i') }}</td>
                            <td>{{ $t->description }}</td>
                            <td>{{ $t->reference ?? '—' }}</td>
                            <td class="text-right {{ $t->type === 'credit' ? 'text-success' : '' }}">
                                {{ $t->type === 'credit' ? number_format($t->amount, 2) : '' }}
                            </td>
                            <td class="text-right {{ $t->type === 'debit' ? 'text-danger' : '' }}">
                                {{ $t->type === 'debit' ? number_format($t->amount, 2) : '' }}
                            </td>
                            <td class="text-right" style="font-weight:bold;">{{ number_format($t->balance_after, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;">No transactions.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
