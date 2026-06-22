@extends('admin.layouts.master')

@section('title', 'Edit Vendor')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Vendor</h2>
        <a href="{{ route('admin.procurement.vendors.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.procurement.vendors.update', $vendor->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="name">Vendor Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-input" required
                        value="{{ old('name', $vendor->name) }}" />
                    @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="contact_name">Contact Person</label>
                    <input type="text" name="contact_name" id="contact_name" class="form-input"
                        value="{{ old('contact_name', $vendor->contact_name) }}" />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-input"
                        value="{{ old('email', $vendor->email) }}" />
                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-input"
                        value="{{ old('phone', $vendor->phone) }}" />
                </div>
                <div class="form-group">
                    <label for="trade_category">Trade Category</label>
                    <select name="trade_category" id="trade_category" class="form-select">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\Category::tradeCategories()->get() as $cat)
                            <option value="{{ $cat->value }}" {{ old('trade_category', $vendor->trade_category) == $cat->value ? 'selected' : '' }}>{{ $cat->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" {{ old('status', $vendor->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status', $vendor->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $vendor->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="approved" {{ old('status', $vendor->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $vendor->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="blacklisted" {{ old('status', $vendor->status) == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                    </select>
                    @error('status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                {{-- <div class="form-group">
                    <label for="credit_limit">Credit Limit (৳)</label>
                    <input type="number" step="0.01" min="0" name="credit_limit" id="credit_limit" class="form-input"
                        value="{{ old('credit_limit', $vendor->credit_limit) }}" />
                </div> --}}
                {{-- <div class="form-group">
                    <label for="payment_terms">Payment Terms</label>
                    <input type="text" name="payment_terms" id="payment_terms" class="form-input"
                        value="{{ old('payment_terms', $vendor->payment_terms) }}" placeholder="e.g. Net 30, Net 60" />
                </div> --}}
                <div class="form-group">
                    <label for="performance_rating">Performance Rating</label>
                    <select name="performance_rating" id="performance_rating" class="form-select">
                        <option value="">Not Rated</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('performance_rating', $vendor->performance_rating) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="address">Address</label>
                <textarea name="address" id="address" class="form-textarea" rows="3">{{ old('address', $vendor->address) }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Vendor</button>
                <button type="button" onclick="window.location.href='{{ route('admin.procurement.vendors.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>
@endsection
