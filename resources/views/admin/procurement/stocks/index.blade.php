@extends('admin.layouts.master')

@section('title', 'Stock Levels')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Stock Levels</h2>
        <div class="flex w-full flex-wrap items-center justify-end gap-4 sm:w-auto">
            <a href="{{ route('admin.procurement.stocks.create') }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Stock
            </a>
        </div>
    </div>

    <div class="panel mt-6">
        <div class="mb-5">
            <form action="{{ route('admin.procurement.stocks.index') }}" method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <select name="material_id" class="form-select">
                    <option value="">All Materials</option>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                    @endforeach
                </select>
                <select name="warehouse_id" class="form-select">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                    @endforeach
                </select>
                <select name="location" class="form-select">
                    <option value="">All Locations</option>
                    <option value="warehouse" {{ request('location') == 'warehouse' ? 'selected' : '' }}>Warehouse</option>
                    <option value="site" {{ request('location') == 'site' ? 'selected' : '' }}>Site</option>
                </select>
                <select name="low_stock" class="form-select">
                    <option value="">All Stock</option>
                    <option value="1" {{ request('low_stock') == '1' ? 'selected' : '' }}>Low Stock Only</option>
                </select>
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                            @if(request()->anyFilled(['material_id', 'warehouse_id', 'location', 'low_stock']))
                        <a href="{{ route('admin.procurement.stocks.index') }}" class="btn btn-outline-danger flex-1">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="datatable">
            <div class="overflow-x-auto">
                <table class="table-hover w-full table-auto">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Location</th>
                            <th>On Hand</th>
                            <th>Min Stock</th>
                            <th>Status</th>
                            <th>Unit</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            @php
                                $isLowStock = $stock->quantity > 0 && $stock->min_stock > 0 && $stock->quantity < $stock->min_stock;
                                $isOutOfStock = $stock->quantity <= 0;
                                $rowClass = $isOutOfStock ? 'bg-danger/10' : ($isLowStock ? 'bg-warning/10' : '');
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="font-semibold">{{ $stock->material->name ?? 'Unknown' }}</td>
                                <td class="text-xs">
                                    @if($stock->warehouse)
                                        <span class="badge badge-outline-info">Warehouse: {{ $stock->warehouse->name }}</span>
                                    @elseif($stock->site)
                                        <span class="badge badge-outline-primary">Site: {{ $stock->site->name }}</span>
                                    @else
                                        <span class="text-white-dark">—</span>
                                    @endif
                                </td>
                                <td class="font-semibold {{ $isOutOfStock ? 'text-danger' : ($isLowStock ? 'text-warning' : '') }}">{{ number_format($stock->quantity, 2) }}</td>
                                <td>{{ $stock->min_stock > 0 ? number_format($stock->min_stock, 2) : '—' }}</td>
                                <td>
                                    @if($isOutOfStock)
                                        <span class="badge bg-danger text-white">Out of Stock</span>
                                    @elseif($isLowStock)
                                        <span class="badge bg-warning text-white">Low Stock</span>
                                    @else
                                        <span class="badge badge-outline-success">OK</span>
                                    @endif
                                </td>
                                <td>{{ $stock->material->unit ?? '—' }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.procurement.stocks.show', $stock->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.procurement.stocks.edit', $stock->id) }}" class="btn btn-sm btn-outline-primary">Adjust</a>
                                        <form action="{{ route('admin.procurement.stocks.destroy', $stock->id) }}" method="POST" onsubmit="return confirm('Delete this stock record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No stock records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $stocks->links() }}
            </div>
        </div>
    </div>
@endsection
