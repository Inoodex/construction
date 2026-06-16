@extends('admin.layouts.master')

@section('title', 'Cost Overrun Alerts')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Cost Overrun Alerts</h2>
        <a href="{{ route('admin.finance.budgets.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            View Budgets
        </a>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="panel">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-lg font-bold text-primary">{{ $stats['total'] }}</h5>
                    <p class="text-xs text-white-dark">Total Alerts</p>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-lg font-bold text-warning">{{ $stats['open'] }}</h5>
                    <p class="text-xs text-white-dark">Open</p>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-lg font-bold text-info">{{ $stats['warning'] }}</h5>
                    <p class="text-xs text-white-dark">Warning (≥80%)</p>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-lg font-bold text-warning">{{ $stats['danger'] }}</h5>
                    <p class="text-xs text-white-dark">Danger (≥100%)</p>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-lg font-bold text-danger">{{ $stats['critical'] }}</h5>
                    <p class="text-xs text-white-dark">Critical (≥120%)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.cost-overrun-alerts.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="severity" class="form-select flex-1">
                    <option value="">Severity</option>
                    <option value="warning" {{ request('severity') == 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="danger" {{ request('severity') == 'danger' ? 'selected' : '' }}>Danger</option>
                    <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'severity', 'status']))
                    <a href="{{ route('admin.finance.cost-overrun-alerts.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Cost Code</th>
                            <th>Budgeted</th>
                            <th>Actual</th>
                            <th>Variance</th>
                            <th>%</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alerts as $alert)
                            <tr>
                                <td class="text-xs">{{ $alert->project->name ?? 'N/A' }}</td>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $alert->cost_code ?? '-' }}</span></td>
                                <td class="font-semibold">৳{{ number_format($alert->budgeted_amount) }}</td>
                                <td class="font-semibold">৳{{ number_format($alert->actual_amount) }}</td>
                                <td class="font-semibold {{ $alert->variance >= 0 ? 'text-success' : 'text-danger' }}">
                                    @if($alert->variance >= 0)+@endif{{ number_format($alert->variance) }}
                                </td>
                                <td class="font-semibold {{ $alert->variance_percentage >= 100 ? 'text-danger' : 'text-warning' }}">
                                    {{ number_format($alert->variance_percentage, 1) }}%
                                </td>
                                <td>
                                    @php $sc = ['warning' => 'badge-outline-warning', 'danger' => 'badge-outline-danger', 'critical' => 'badge-outline-dark']; @endphp
                                    <span class="badge {{ $sc[$alert->severity] ?? 'badge-outline-secondary' }} capitalize">{{ $alert->severity }}</span>
                                </td>
                                <td>
                                    @php $st = ['open' => 'badge-outline-danger', 'acknowledged' => 'badge-outline-warning', 'resolved' => 'badge-outline-success']; @endphp
                                    <span class="badge {{ $st[$alert->status] ?? 'badge-outline-secondary' }} capitalize">{{ $alert->status }}</span>
                                </td>
                                <td class="text-xs">{{ $alert->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        @if($alert->status === 'open')
                                            <form action="{{ route('admin.finance.cost-overrun-alerts.acknowledge', $alert->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Acknowledge">Ack</button>
                                            </form>
                                        @endif
                                        @if(in_array($alert->status, ['open', 'acknowledged']))
                                            <form action="{{ route('admin.finance.cost-overrun-alerts.resolve', $alert->id) }}" method="POST" class="inline" onsubmit="return confirm('Mark as resolved?');">
                                                @csrf
                                                <input type="hidden" name="notes" value="Resolved manually" />
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Resolve">Resolve</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @if($alert->message)
                                <tr class="bg-white-light/30 dark:bg-dark/30">
                                    <td colspan="10" class="px-8 text-xs italic text-white-dark">{{ $alert->message }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr><td colspan="10" class="text-center">No alerts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $alerts->links() }}</div>
        </div>
    </div>
@endsection
