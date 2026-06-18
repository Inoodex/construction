@extends('admin.layouts.master')

@section('title', 'Edit Subcontractor')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Subcontractor</h2>
        <a href="{{ route('admin.procurement.subcontractors.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.subcontractors.update', $subcontractor->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="name">Subcontractor Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required
                        value="{{ old('name', $subcontractor->name) }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="contact_name">Contact Person</label>
                    <input type="text" name="contact_name" id="contact_name" class="form-input"
                        value="{{ old('contact_name', $subcontractor->contact_name) }}" />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-input"
                        value="{{ old('email', $subcontractor->email) }}" />
                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-input"
                        value="{{ old('phone', $subcontractor->phone) }}" />
                </div>
                <div class="form-group">
                    <label for="trade_category">Trade Category</label>
                    <select name="trade_category" id="trade_category" class="form-select">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\Category::tradeCategories()->get() as $cat)
                            <option value="{{ $cat->value }}" {{ old('trade_category', $subcontractor->trade_category) == $cat->value ? 'selected' : '' }}>{{ $cat->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <input type="text" name="specialization" id="specialization" class="form-input"
                        value="{{ old('specialization', $subcontractor->specialization) }}" placeholder="e.g. Concrete Works, Steel Fixing" />
                </div>
                <div class="form-group">
                    <label for="license_number">License Number</label>
                    <input type="text" name="license_number" id="license_number" class="form-input"
                        value="{{ old('license_number', $subcontractor->license_number) }}" />
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" {{ old('status', $subcontractor->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status', $subcontractor->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $subcontractor->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="approved" {{ old('status', $subcontractor->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $subcontractor->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="suspended" {{ old('status', $subcontractor->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="performance_rating">Performance Rating</label>
                    <select name="performance_rating" id="performance_rating" class="form-select">
                        <option value="">Not Rated</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('performance_rating', $subcontractor->performance_rating) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="address">Address</label>
                <textarea name="address" id="address" class="form-textarea" rows="3">{{ old('address', $subcontractor->address) }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Subcontractor</button>
                <button type="button" onclick="window.location.href='{{ route('admin.procurement.subcontractors.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>
@endsection
