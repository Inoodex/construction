@extends('admin.layouts.master')

@section('title', 'Drawing Details')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">{{ $drawing->drawing_number }} — {{ $drawing->title }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.documents.drawings.index') }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back
            </a>
            @if(!auth()->user()->hasRole('client'))
                <a href="{{ route('admin.core.documents.drawings.edit', $drawing) }}" class="btn btn-primary gap-2">Edit</a>
            @endif
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="panel lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h5 class="text-base font-semibold">Drawing Details</h5>
                @php $colors = ['draft' => 'badge-outline-secondary', 'issued' => 'badge-outline-success', 'superseded' => 'badge-outline-warning', 'obsolete' => 'badge-outline-danger']; @endphp
                <span class="badge {{ $colors[$drawing->status] ?? 'badge-outline-secondary' }} capitalize">{{ $drawing->status }}</span>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label class="text-xs text-white-dark">Drawing Number</label><p class="font-semibold">{{ $drawing->drawing_number }}</p></div>
                <div><label class="text-xs text-white-dark">Project</label><p class="font-semibold">{{ $drawing->project->name ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Type</label><p class="font-semibold capitalize">{{ str_replace('_', ' ', $drawing->drawing_type) }}</p></div>
                <div><label class="text-xs text-white-dark">Discipline</label><p class="font-semibold">{{ $drawing->discipline ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Current Revision</label><p class="font-semibold">{{ $drawing->current_revision ?? '—' }}</p></div>
                <div><label class="text-xs text-white-dark">Created By</label><p class="font-semibold">{{ $drawing->creator->name ?? '—' }}</p></div>
            </div>

            @if($drawing->description)
                <hr class="my-4 border-white-light dark:border-gray-700">
                <div><label class="text-xs text-white-dark">Description</label><p class="mt-1 whitespace-pre-wrap">{{ $drawing->description }}</p></div>
            @endif
        </div>

        <div class="panel">
            <h5 class="mb-4 text-base font-semibold">Info</h5>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Created</span>
                    <span class="text-xs font-semibold">{{ $drawing->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">Revisions</span>
                    <span class="text-xs font-semibold">{{ $drawing->revisions->count() }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                    <span class="text-xs">RFIs</span>
                    <span class="text-xs font-semibold">{{ $drawing->rfis->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-4 flex items-center justify-between">
            <h5 class="text-base font-semibold">Revision History</h5>
        </div>

        @if($drawing->revisions->count())
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Revision</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Uploaded By</th>
                            <th>File</th>
                            <th>Current</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drawing->revisions as $rev)
                            <tr>
                                <td class="text-xs font-semibold">{{ $rev->revision }}</td>
                                <td class="text-xs">{{ $rev->revision_date->format('d M Y') }}</td>
                                <td class="text-xs">{{ $rev->description ?? '—' }}</td>
                                <td class="text-xs">{{ $rev->uploader->name ?? '—' }}</td>
                                <td class="text-xs">
                                    @if($rev->getFirstMedia('drawing_file'))
                                        <a href="{{ $rev->getFirstMedia('drawing_file')->getUrl() }}" target="_blank" class="text-primary hover:underline">Download</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($rev->is_current)
                                        <span class="badge badge-outline-success">Current</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-white-dark">No revisions yet.</p>
        @endif

        @if(!auth()->user()->hasRole('client'))
            <hr class="my-6 border-white-light dark:border-gray-700">
            <h6 class="mb-4 text-sm font-semibold">Add Revision</h6>
            <form action="{{ route('admin.core.documents.drawings.revisions.store', $drawing) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="form-group">
                        <label for="revision">Revision <span class="text-danger">*</span></label>
                        <input type="text" name="revision" id="revision" class="form-input" required placeholder="e.g. Rev A, Rev 01" />
                        @error('revision') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="revision_date">Date <span class="text-danger">*</span></label>
                        <input type="date" name="revision_date" id="revision_date" class="form-input" required value="{{ date('Y-m-d') }}" />
                        @error('revision_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="file">File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-input" required />
                        @error('file') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group md:col-span-3">
                        <label for="description">Description</label>
                        <input type="text" name="description" id="description" class="form-input" placeholder="Changes in this revision" />
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Upload Revision</button>
            </form>
        @endif
    </div>
@endsection
