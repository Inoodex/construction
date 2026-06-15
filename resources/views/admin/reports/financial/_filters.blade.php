<div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div>
        <label class="mb-1 block text-xs font-semibold text-gray-500">Project</label>
        <select name="project_id" class="form-select text-xs" onchange="this.form.submit()">
            <option value="">All Projects</option>
            @foreach($projects as $p)
                <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold text-gray-500">From Date</label>
        <input type="date" name="from" class="form-input text-xs" value="{{ request('from') }}" onchange="this.form.submit()">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold text-gray-500">To Date</label>
        <input type="date" name="to" class="form-input text-xs" value="{{ request('to') }}" onchange="this.form.submit()">
    </div>
    <div class="flex items-end">
        <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary text-xs">Reset</a>
    </div>
</div>
