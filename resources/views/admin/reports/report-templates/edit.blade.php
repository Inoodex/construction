@extends('admin.layouts.master')

@section('title', 'Edit Report Template')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Report Builder — Edit</h2>
        <a href="{{ route('admin.reports.report-templates.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to List
        </a>
    </div>

    @php $config = $reportTemplate->configuration ?? []; @endphp

    <form action="{{ route('admin.reports.report-templates.update', $reportTemplate) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="xl:col-span-1 space-y-6">
                <div class="panel">
                    <h5 class="mb-4 text-lg font-semibold">Template Info</h5>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold">Template Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-input mt-1" required value="{{ old('name', $reportTemplate->name) }}" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold">Report Type <span class="text-danger">*</span></label>
                            <select name="report_type" class="form-select mt-1" required>
                                @foreach($reportTypes as $type)
                                    <option value="{{ $type }}" {{ old('report_type', $reportTemplate->report_type) == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold">Description</label>
                            <textarea name="description" class="form-input mt-1" rows="2">{{ old('description', $reportTemplate->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <h5 class="mb-4 text-lg font-semibold">Data Source</h5>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold">Source <span class="text-danger">*</span></label>
                            <select name="data_source" class="form-select mt-1" required>
                                @php $sources = ['projects','invoices','budgets','expenses','stocks','employees','hse_incidents']; @endphp
                                @foreach($sources as $s)
                                    <option value="{{ $s }}" {{ old('data_source', $config['data_source'] ?? '') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold">Chart Type</label>
                            <select name="chart_type" class="form-select mt-1">
                                @foreach(['none','bar','line','pie','area'] as $ct)
                                    <option value="{{ $ct }}" {{ old('chart_type', $config['chart_type'] ?? 'none') == $ct ? 'selected' : '' }}>{{ $ct == 'none' ? 'No Chart' : ucfirst($ct).' Chart' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold">Group By</label>
                            <select name="group_by" class="form-select mt-1">
                                @foreach(['','project','status','date','type'] as $gb)
                                    <option value="{{ $gb }}" {{ old('group_by', $config['group_by'] ?? '') == $gb ? 'selected' : '' }}>{{ $gb == '' ? 'None' : ucfirst($gb) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <h5 class="mb-4 text-lg font-semibold">Filters</h5>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold">Project</label>
                            <select name="filter_project_id" class="form-select mt-1">
                                <option value="">All Projects</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}" {{ old('filter_project_id', $config['filters']['project_id'] ?? '') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold">Date From</label>
                            <input type="date" name="filter_date_from" class="form-input mt-1" value="{{ old('filter_date_from', $config['filters']['date_from'] ?? '') }}" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold">Date To</label>
                            <input type="date" name="filter_date_to" class="form-input mt-1" value="{{ old('filter_date_to', $config['filters']['date_to'] ?? '') }}" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-2 space-y-6">
                <div class="panel">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold">Columns to Display</h5>
                        <button type="button" id="addColumnBtn" class="btn btn-outline-primary btn-sm">+ Add Column</button>
                    </div>
                    <div id="columnsContainer" class="space-y-2">
                        @php $cols = old('columns', $config['columns'] ?? []); @endphp
                        @foreach($cols as $col)
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400 cursor-move">:::</span>
                            <select name="columns[]" class="form-select flex-1 column-select" required>
                                <option value="">Select column...</option>
                                <optgroup label="Common"><option value="id" {{ $col=='id'?'selected':'' }}>ID</option><option value="name" {{ $col=='name'?'selected':'' }}>Name</option><option value="status" {{ $col=='status'?'selected':'' }}>Status</option><option value="created_at" {{ $col=='created_at'?'selected':'' }}>Created Date</option></optgroup>
                                <optgroup label="Projects"><option value="budget" {{ $col=='budget'?'selected':'' }}>Budget</option><option value="start_date" {{ $col=='start_date'?'selected':'' }}>Start Date</option><option value="end_date" {{ $col=='end_date'?'selected':'' }}>End Date</option></optgroup>
                                <optgroup label="Financial"><option value="amount" {{ $col=='amount'?'selected':'' }}>Amount</option><option value="invoice_number" {{ $col=='invoice_number'?'selected':'' }}>Invoice #</option><option value="invoice_date" {{ $col=='invoice_date'?'selected':'' }}>Invoice Date</option><option value="cost_code" {{ $col=='cost_code'?'selected':'' }}>Cost Code</option><option value="description" {{ $col=='description'?'selected':'' }}>Description</option><option value="budgeted_amount" {{ $col=='budgeted_amount'?'selected':'' }}>Budgeted Amount</option><option value="actual_amount" {{ $col=='actual_amount'?'selected':'' }}>Actual Amount</option><option value="category" {{ $col=='category'?'selected':'' }}>Category</option><option value="expense_date" {{ $col=='expense_date'?'selected':'' }}>Expense Date</option></optgroup>
                                <optgroup label="Inventory"><option value="item_name" {{ $col=='item_name'?'selected':'' }}>Item Name</option><option value="quantity" {{ $col=='quantity'?'selected':'' }}>Quantity</option><option value="unit" {{ $col=='unit'?'selected':'' }}>Unit</option></optgroup>
                                <optgroup label="HR"><option value="first_name" {{ $col=='first_name'?'selected':'' }}>First Name</option><option value="last_name" {{ $col=='last_name'?'selected':'' }}>Last Name</option><option value="designation" {{ $col=='designation'?'selected':'' }}>Designation</option><option value="department" {{ $col=='department'?'selected':'' }}>Department</option></optgroup>
                                <optgroup label="Safety"><option value="incident_date" {{ $col=='incident_date'?'selected':'' }}>Incident Date</option><option value="incident_type" {{ $col=='incident_type'?'selected':'' }}>Incident Type</option><option value="severity" {{ $col=='severity'?'selected':'' }}>Severity</option><option value="location" {{ $col=='location'?'selected':'' }}>Location</option></optgroup>
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-column" title="Remove">X</button>
                        </div>
                        @endforeach
                        @if(empty($cols))
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400 cursor-move">:::</span>
                            <select name="columns[]" class="form-select flex-1 column-select" required>
                                <option value="">Select column...</option>
                                <optgroup label="Common"><option value="id">ID</option><option value="name">Name</option><option value="status">Status</option><option value="created_at">Created Date</option></optgroup>
                                <optgroup label="Projects"><option value="budget">Budget</option><option value="start_date">Start Date</option><option value="end_date">End Date</option></optgroup>
                                <optgroup label="Financial"><option value="amount">Amount</option><option value="invoice_number">Invoice #</option><option value="invoice_date">Invoice Date</option><option value="cost_code">Cost Code</option><option value="description">Description</option><option value="budgeted_amount">Budgeted Amount</option><option value="actual_amount">Actual Amount</option><option value="category">Category</option><option value="expense_date">Expense Date</option></optgroup>
                                <optgroup label="Inventory"><option value="item_name">Item Name</option><option value="quantity">Quantity</option><option value="unit">Unit</option></optgroup>
                                <optgroup label="HR"><option value="first_name">First Name</option><option value="last_name">Last Name</option><option value="designation">Designation</option><option value="department">Department</option></optgroup>
                                <optgroup label="Safety"><option value="incident_date">Incident Date</option><option value="incident_type">Incident Type</option><option value="severity">Severity</option><option value="location">Location</option></optgroup>
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-column" title="Remove">X</button>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn btn-primary px-10">Update Template</button>
                    <a href="{{ route('admin.reports.report-templates.show', $reportTemplate) }}" class="btn btn-outline-info">Preview</a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        document.getElementById('addColumnBtn').addEventListener('click', function() {
            var container = document.getElementById('columnsContainer');
            var html = '<div class="flex items-center gap-2"><span class="text-gray-400 cursor-move">:::</span><select name="columns[]" class="form-select flex-1 column-select" required><option value="">Select column...</option><optgroup label="Common"><option value="id">ID</option><option value="name">Name</option><option value="status">Status</option><option value="created_at">Created Date</option></optgroup><optgroup label="Projects"><option value="budget">Budget</option><option value="start_date">Start Date</option><option value="end_date">End Date</option></optgroup><optgroup label="Financial"><option value="amount">Amount</option><option value="invoice_number">Invoice #</option><option value="invoice_date">Invoice Date</option><option value="cost_code">Cost Code</option><option value="description">Description</option><option value="budgeted_amount">Budgeted Amount</option><option value="actual_amount">Actual Amount</option><option value="category">Category</option><option value="expense_date">Expense Date</option></optgroup><optgroup label="Inventory"><option value="item_name">Item Name</option><option value="quantity">Quantity</option><option value="unit">Unit</option></optgroup><optgroup label="HR"><option value="first_name">First Name</option><option value="last_name">Last Name</option><option value="designation">Designation</option><option value="department">Department</option></optgroup><optgroup label="Safety"><option value="incident_date">Incident Date</option><option value="incident_type">Incident Type</option><option value="severity">Severity</option><option value="location">Location</option></optgroup></select><button type="button" class="btn btn-sm btn-outline-danger remove-column" title="Remove">X</button></div>';
            container.insertAdjacentHTML('beforeend', html);
            bindRemoveButtons();
        });
        function bindRemoveButtons() {
            document.querySelectorAll('.remove-column').forEach(function(btn) {
                btn.onclick = function() {
                    var c = document.getElementById('columnsContainer');
                    if (c.children.length > 1) btn.closest('.flex').remove();
                    else alert('At least one column is required.');
                };
            });
        }
        bindRemoveButtons();
    </script>
    @endpush
@endsection
