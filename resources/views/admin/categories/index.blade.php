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
                                    <x-action-dropdown>
                                        <x-slot name="items">
                                            <li><a href="{{ route('admin.categories.edit', $cat) }}"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/></svg> Edit</a></li>
                                            <li class="divider"></li>
                                            <li class="danger">
                                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 001-1h4a1 1 0 001 1m-6 0h6"/></svg> Delete</button>
                                                </form>
                                            </li>
                                        </x-slot>
                                    </x-action-dropdown>
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
