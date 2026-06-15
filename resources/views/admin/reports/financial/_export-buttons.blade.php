@php
    $reportRoute = request()->route()->getName();
    $reportKey = match(true) {
        str_contains($reportRoute, 'budget-vs-actual') => 'budget-vs-actual',
        str_contains($reportRoute, 'project-cost-summary') => 'project-cost-summary',
        str_contains($reportRoute, 'procurement-spend') => 'procurement-spend',
        str_contains($reportRoute, 'invoice-status') => 'invoice-status',
        str_contains($reportRoute, 'cash-flow') => 'cash-flow',
        str_contains($reportRoute, 'retention-tracker') => 'retention-tracker',
        str_contains($reportRoute, 'progress-schedule') => 'progress-schedule',
        str_contains($reportRoute, 'resource-utilisation') => 'resource-utilisation',
        default => 'budget-vs-actual',
    };
    $queryString = http_build_query(request()->except('_token'));
@endphp
<div class="flex gap-2">
    <a href="{{ route('admin.reports.financial.export.pdf', $reportKey) }}{{ $queryString ? '?' . $queryString : '' }}" class="btn btn-sm btn-outline-danger gap-1" target="_blank">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 004.561 21h14.878a2 2 0 001.94-1.515L22 17"/></svg>
        PDF
    </a>
    <a href="{{ route('admin.reports.financial.export.excel', $reportKey) }}{{ $queryString ? '?' . $queryString : '' }}" class="btn btn-sm btn-outline-success gap-1">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Excel
    </a>
</div>
