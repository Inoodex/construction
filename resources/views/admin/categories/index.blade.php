@extends('admin.layouts.master')

@section('title', 'Category Management')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Category Management</h5>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Add Category</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    @foreach($groups as $groupType => $items)
        <div class="mb-4">
            <div class="flex items-center gap-2 cursor-pointer select-none py-2 px-3 bg-gray-100 dark:bg-[#1b2e4b] rounded-md" onclick="this.nextElementSibling.classList.toggle('hidden')">
                <svg class="w-4 h-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
                <span class="font-semibold">{{ str_replace('_', ' ', ucfirst($groupType)) }}</span>
                <span class="text-xs text-white-dark">({{ $items->count() }})</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Value</th>
                            <th>Label</th>
                            <th>Sort</th>
                            <th>Active</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $cat)
                            <tr>
                                <td class="font-mono text-xs">{{ $cat->value }}</td>
                                <td>{{ $cat->label }}</td>
                                <td>{{ $cat->sort_order ?? '-' }}</td>
                                <td>{!! $cat->is_active ? '<span class="badge badge-outline-success">Yes</span>' : '<span class="badge badge-outline-danger">No</span>' !!}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-gray-500">No items.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
