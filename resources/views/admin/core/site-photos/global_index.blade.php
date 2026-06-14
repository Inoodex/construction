@extends('admin.layouts.master')

@section('title', 'All Site Photos')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">All Site Photos</h2>
    </div>

    <div class="panel mt-6">
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
            @forelse($photos as $photo)
                <div class="group relative overflow-hidden rounded-lg border dark:border-gray-700">
                    <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->caption ?: $photo->original_name }}" class="h-32 w-full object-cover transition group-hover:scale-105" loading="lazy" />
                    </a>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                        <p class="truncate text-xs text-white">{{ $photo->caption ?: $photo->original_name }}</p>
                        <p class="text-[10px] text-gray-300">{{ $photo->site->name ?? '—' }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-white-dark">No photos uploaded yet.</div>
            @endforelse
        </div>
        <div class="mt-4">{{ $photos->links() }}</div>
    </div>
@endsection
