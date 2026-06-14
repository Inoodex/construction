@extends('admin.layouts.master')

@section('title', 'Site Photos')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Photos — {{ $site->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.core.sites.show', $site) }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Site
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.core.sites.photos.store', $site) }}" method="POST" enctype="multipart/form-data" class="mb-6 rounded-lg border border-dashed p-6 dark:border-gray-700">
            @csrf
            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold">Upload Photos</label>
                <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="form-input file:mr-4 file:rounded file:border-0 file:bg-primary file:px-4 file:py-2 file:text-white file:hover:bg-primary-dark" required />
                @error('photos') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                @error('photos.*') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="caption" class="mb-1 block text-xs text-white-dark">Caption (optional, applies to all)</label>
                <input type="text" name="caption" id="caption" class="form-input" value="{{ old('caption') }}" placeholder="e.g. Foundation work progress" />
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        @if($photos->isNotEmpty())
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                @foreach($photos as $photo)
                    <div class="group relative overflow-hidden rounded-lg border dark:border-gray-700">
                        <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->caption ?: $photo->original_name }}" class="h-36 w-full object-cover transition group-hover:scale-105" loading="lazy" />
                        </a>
                        <div class="p-2">
                            <form action="{{ route('admin.core.sites.photos.caption', [$site, $photo]) }}" method="POST" class="mb-1">
                                @csrf
                                <input type="text" name="caption" value="{{ $photo->caption }}" placeholder="Add caption..." class="w-full text-xs bg-transparent border-0 border-b border-dashed border-gray-400 focus:border-primary focus:outline-none" />
                            </form>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] text-white-dark">{{ $photo->uploader->name ?? '—' }}</span>
                                <form action="{{ route('admin.core.sites.photos.destroy', [$site, $photo]) }}" method="POST" onsubmit="return confirm('Delete this photo?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[10px] text-danger hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $photos->links() }}</div>
        @else
            <p class="text-center text-white-dark py-8">No photos uploaded yet for this site.</p>
        @endif
    </div>
@endsection
