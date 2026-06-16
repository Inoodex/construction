@extends('admin.layouts.master')

@section('title', 'Interim Payment Applications')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Interim Payment Applications</h2>
        <a href="{{ route('admin.finance.ipas.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New IPA
        </a>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.finance.ipas.index') }}" method="GET" class="flex items-center gap-3 w-full">
                <select name="project_id" class="form-select flex-1">
                    <option value="">Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select flex-1">
                    <option value="">Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="certified" {{ request('status') == 'certified' ? 'selected' : '' }}>Certified</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request()->anyFilled(['project_id', 'status']))
                    <a href="{{ route('admin.finance.ipas.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>IPA #</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Period</th>
                            <th>Applied</th>
                            <th>Certified</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ipas as $ipa)
                            <tr>
                                <td><span class="font-mono text-xs font-semibold text-primary">{{ $ipa->ipa_number }}</span></td>
                                <td class="font-semibold">{{ $ipa->title }}</td>
                                <td class="text-xs">{{ $ipa->project->name ?? 'N/A' }}</td>
                                <td class="text-xs">{{ $ipa->period_start->format('d/m') }} - {{ $ipa->period_end->format('d/m/Y') }}</td>
                                <td class="font-semibold">{{ number_format($ipa->applied_amount) }}</td>
                                <td class="font-semibold">{{ number_format($ipa->certified_amount) }}</td>
                                <td>
                                    @php $sc = ['draft' => 'badge-outline-secondary', 'submitted' => 'badge-outline-info', 'certified' => 'badge-outline-primary', 'approved' => 'badge-outline-success', 'rejected' => 'badge-outline-danger', 'paid' => 'badge-outline-dark']; @endphp
                                    <span class="badge {{ $sc[$ipa->status] ?? 'badge-outline-secondary' }} capitalize">{{ $ipa->status }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.finance.ipas.show', $ipa->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        @if($ipa->status === 'draft')
                                            <a href="{{ route('admin.finance.ipas.edit', $ipa->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                            <form action="{{ route('admin.finance.ipas.destroy', $ipa->id) }}" method="POST" onsubmit="return confirm('Delete this IPA?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No IPAs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $ipas->links() }}</div>
        </div>
    </div>
@endsection
