@extends('admin.layouts.master')

@section('title', 'Approval Workflows')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Approval Workflows</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.approvals.workflows.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Workflow
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Document Type</th>
                            <th>Levels</th>
                            <th>Status</th>
                            <th>Pending Approvals</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workflows as $workflow)
                            <tr>
                                <td>
                                    <span class="font-semibold">{{ $workflow->name }}</span>
                                    @if($workflow->description)
                                        <br><span class="text-xs text-gray-500">{{ $workflow->description }}</span>
                                    @endif
                                </td>
                                <td><code class="text-xs font-mono text-primary">{{ $workflow->document_type }}</code></td>
                                <td class="text-xs">{{ $workflow->matrices->max('approval_level') }} level(s)</td>
                                <td>
                                    @php $sc = ['1' => 'badge-outline-success', '0' => 'badge-outline-secondary']; @endphp
                                    <span class="badge {{ $sc[(string)$workflow->is_active] ?? 'badge-outline-secondary' }}">{{ $workflow->is_active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td class="text-xs">{{ $workflow->approvals()->where('status', 'pending')->count() }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.approvals.workflows.edit', $workflow) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.approvals.workflows.destroy', $workflow) }}" method="POST" onsubmit="return confirm('Delete this workflow?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No approval workflows found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
