@extends('admin.layouts.master')

@section('title', 'Resource Allocation Chart')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Resource Allocation Chart</h2>
        <a href="{{ route('admin.core.resources.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Resources
        </a>
    </div>

    <div class="panel mt-6">
        <h5 class="mb-4 text-base font-semibold">Select a Project</h5>
        @if($projects->isEmpty())
            <p class="text-sm text-white-dark">No projects found.</p>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($projects as $project)
                    <a href="{{ route('admin.core.projects.resource-gantt', $project) }}" class="rounded-lg border p-4 transition-colors hover:border-primary hover:bg-primary/5 dark:border-gray-700 dark:hover:border-primary">
                        <div class="font-semibold">{{ $project->name }}</div>
                        <div class="mt-2 flex items-center gap-4 text-xs text-white-dark">
                            <span>Resources: {{ $project->resources_count ?? 0 }}</span>
                            <span>Progress: {{ $project->progress }}%</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
