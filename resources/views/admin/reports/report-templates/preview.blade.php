@extends('admin.layouts.master')

@section('title', 'Report Preview — ' . $reportTemplate->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Preview: {{ $reportTemplate->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.report-templates.edit', $reportTemplate) }}" class="btn btn-outline-warning">Edit Template</a>
            <a href="{{ route('admin.reports.report-templates.index') }}" class="btn btn-secondary">Back to Templates</a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-4 flex flex-wrap gap-4 text-sm">
            <span class="rounded bg-primary/10 px-3 py-1 text-primary font-semibold">{{ ucfirst($reportTemplate->report_type) }}</span>
            <span class="text-gray-500">Source: {{ ucwords(str_replace('_', ' ', $dataSource)) }}</span>
            <span class="text-gray-500">Columns: {{ count($columns) }}</span>
            @if($reportTemplate->description)
                <span class="text-gray-400">{{ $reportTemplate->description }}</span>
            @endif
        </div>
    </div>

    <div class="panel mt-6">
        <h5 class="mb-4 text-lg font-semibold">Results ({{ $data->count() }} records)</h5>
        @if($data->count())
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            @foreach($columns as $col)
                                <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                            <tr>
                                @foreach($columns as $col)
                                    <td>{{ $row->$col ?? '-' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-400 py-8">No data found for this report configuration.</p>
        @endif
    </div>
@endsection
