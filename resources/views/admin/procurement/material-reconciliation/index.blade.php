@extends('admin.layouts.master')

@section('title', 'Material Reconciliation')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Material Reconciliation</h2>
    </div>

    <div class="panel mt-6">
        <form method="GET" class="flex items-end gap-3 flex-wrap">
            <div class="form-group flex-1 min-w-[140px]">
                <label>Warehouse</label>
                <select name="warehouse_id" class="form-select" onchange="document.getElementById('site_id').value=''">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" {{ (string)$warehouseId === (string)$w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group flex-1 min-w-[140px]">
                <label>Site</label>
                <select name="site_id" id="site_id" class="form-select" onchange="document.getElementsByName('warehouse_id')[0].value=''">
                    <option value="">All Sites</option>
                    @foreach($sites as $s)
                        <option value="{{ $s->id }}" {{ (string)$siteId === (string)$s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group flex-1 min-w-[140px]">
                <label>From</label>
                <input type="date" name="start_date" class="form-input" value="{{ $startDate }}" />
            </div>
            <div class="form-group flex-1 min-w-[140px]">
                <label>To</label>
                <input type="date" name="end_date" class="form-input" value="{{ $endDate }}" />
            </div>
            <div class="form-group shrink-0">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </form>
    </div>

    @if(count($rows) > 0)
        <div class="panel mt-6">
            <div class="table-responsive overflow-x-auto">
                <table class="table table-bordered table-striped text-sm">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle">Material</th>
                            <th rowspan="2" class="align-middle">Location</th>
                            <th rowspan="2" class="align-middle">Opening</th>
                            <th colspan="5" class="text-center border-b-2">Movements ({{ $startDate }} - {{ $endDate }})</th>
                            <th rowspan="2" class="align-middle">Expected</th>
                            <th rowspan="2" class="align-middle">Actual</th>
                            <th rowspan="2" class="align-middle">Variance</th>
                        </tr>
                        <tr>
                            <th class="text-xs">Received</th>
                            <th class="text-xs">Issued</th>
                            <th class="text-xs">Trf In</th>
                            <th class="text-xs">Trf Out</th>
                            <th class="text-xs">Wastage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                            @php
                                $varianceClass = match(true) {
                                    $row['variance'] < 0 => 'text-danger',
                                    $row['variance'] > 0 => 'text-success',
                                    default => '',
                                };
                            @endphp
                            <tr>
                                <td class="font-semibold">{{ $row['material'] }}</td>
                                <td>{{ $row['location'] }}</td>
                                <td class="text-center">{{ number_format($row['opening'], 2) }}</td>
                                <td class="text-center">{{ number_format($row['received'], 2) }}</td>
                                <td class="text-center">{{ number_format($row['issued'], 2) }}</td>
                                <td class="text-center">{{ number_format($row['transferred_in'], 2) }}</td>
                                <td class="text-center">{{ number_format($row['transferred_out'], 2) }}</td>
                                <td class="text-center">{{ number_format($row['wastage'], 2) }}</td>
                                <td class="text-center">{{ number_format($row['expected'], 2) }}</td>
                                <td class="text-center font-semibold">{{ number_format($row['actual'], 2) }}</td>
                                <td class="text-center font-semibold {{ $varianceClass }}">{{ number_format($row['variance'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @php
                            $totals = ['opening' => 0, 'received' => 0, 'issued' => 0, 'transferred_in' => 0, 'transferred_out' => 0, 'wastage' => 0, 'expected' => 0, 'actual' => 0, 'variance' => 0];
                            foreach ($rows as $r) {
                                $totals['opening'] += $r['opening'];
                                $totals['received'] += $r['received'];
                                $totals['issued'] += $r['issued'];
                                $totals['transferred_in'] += $r['transferred_in'];
                                $totals['transferred_out'] += $r['transferred_out'];
                                $totals['wastage'] += $r['wastage'];
                                $totals['expected'] += $r['expected'];
                                $totals['actual'] += $r['actual'];
                                $totals['variance'] += $r['variance'];
                            }
                        @endphp
                        <tr class="font-bold">
                            <td colspan="2" class="text-right">Totals</td>
                            <td class="text-center">{{ number_format($totals['opening'], 2) }}</td>
                            <td class="text-center">{{ number_format($totals['received'], 2) }}</td>
                            <td class="text-center">{{ number_format($totals['issued'], 2) }}</td>
                            <td class="text-center">{{ number_format($totals['transferred_in'], 2) }}</td>
                            <td class="text-center">{{ number_format($totals['transferred_out'], 2) }}</td>
                            <td class="text-center">{{ number_format($totals['wastage'], 2) }}</td>
                            <td class="text-center">{{ number_format($totals['expected'], 2) }}</td>
                            <td class="text-center">{{ number_format($totals['actual'], 2) }}</td>
                            <td class="text-center {{ $totals['variance'] < 0 ? 'text-danger' : ($totals['variance'] > 0 ? 'text-success' : '') }}">{{ number_format($totals['variance'], 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            {{-- <p class="text-xs text-white-dark mt-3">
                <strong>Formula:</strong> Opening = Actual - Received + Issued + Transferred Out - Transferred In + Wastage.
                Expected = Opening + Received - Issued - Transferred Out + Transferred In - Wastage.
                Variance = Actual - Expected.
                Non-zero variance indicates data discrepancies or unreported movements.
            </p> --}}
        </div>
    @elseif(request()->hasAny(['warehouse_id', 'site_id']))
        <div class="panel mt-6">
            <p class="text-center py-6 text-white-dark">No stock records found for the selected location.</p>
        </div>
    @endif
@endsection
