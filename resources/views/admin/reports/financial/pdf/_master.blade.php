<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>@yield('title')</title>
<style>
@page { margin: 15mm 10mm; }
body { font-family: sans-serif; font-size: 10pt; color: #333; line-height: 1.4; }
h1 { font-size: 16pt; color: #4361ee; margin-bottom: 2px; }
h2 { font-size: 12pt; color: #3b3f5c; margin-bottom: 4px; }
h5 { font-size: 10pt; color: #3b3f5c; margin-bottom: 6px; }
.header { border-bottom: 2px solid #4361ee; padding-bottom: 6px; margin-bottom: 12px; }
.header .subtitle { font-size: 8pt; color: #888ea8; }
.stats { width: 100%; margin-bottom: 12px; }
.stats td { padding: 6px 8px; border: 1px solid #e0e6ed; text-align: center; font-size: 9pt; }
.stats .value { font-size: 14pt; font-weight: bold; }
.stats .label { font-size: 7pt; color: #888ea8; text-transform: uppercase; letter-spacing: 0.5px; }
table.data { width: 100%; border-collapse: collapse; font-size: 8pt; margin-bottom: 10px; }
table.data th { background: #4361ee; color: #fff; padding: 5px 4px; text-align: left; font-weight: 600; font-size: 7.5pt; text-transform: uppercase; }
table.data td { padding: 4px; border-bottom: 1px solid #e0e6ed; }
table.data tr:nth-child(even) td { background: #f8f9fc; }
table.data .text-right { text-align: right; }
table.data .text-center { text-align: center; }
table.data .text-danger { color: #e7515a; }
table.data .text-success { color: #00ab55; }
table.data .text-warning { color: #e2a03f; }
table.data .font-mono { font-family: monospace; }
table.data .font-semibold { font-weight: 600; }
.footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 7pt; color: #888ea8; border-top: 1px solid #e0e6ed; padding-top: 4px; }
.progress-outer { background: #e0e6ed; border-radius: 3px; height: 12px; width: 100px; display: inline-block; }
.progress-inner { height: 12px; border-radius: 3px; }
</style>
</head>
<body>
<div class="header">
    <h1>@yield('title')</h1>
    <div class="subtitle">Generated: {{ now()->format('d M Y, h:i A') }}</div>
</div>
@yield('content')
<div class="footer">Construction Management System — Financial Report</div>
</body>
</html>