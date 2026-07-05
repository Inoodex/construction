@extends('admin.layouts.master')

@section('title', 'Edit Client')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Edit Client</h5>
        <a href="{{ route('admin.crm.clients.show', $client) }}" class="btn btn-outline-info">Cancel</a>
    </div>

    <form action="{{ route('admin.crm.clients.update', $client) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-xs">Company Name <span class="text-danger">*</span></label>
                <input type="text" name="company_name" class="form-input" value="{{ old('company_name', $client->company_name) }}" required />
                @error('company_name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs">Contact Person</label>
                <input type="text" name="contact_person" class="form-input" value="{{ old('contact_person', $client->contact_person) }}" />
            </div>
            <div><label class="mb-1 block text-xs">Email</label><input type="email" name="email" class="form-input" value="{{ old('email', $client->email) }}" /></div>
            <div><label class="mb-1 block text-xs">Phone</label><input type="text" name="phone" class="form-input" value="{{ old('phone', $client->phone) }}" /></div>
            <div><label class="mb-1 block text-xs">Mobile</label><input type="text" name="mobile" class="form-input" value="{{ old('mobile', $client->mobile) }}" /></div>
            <div><label class="mb-1 block text-xs">Tax ID</label><input type="text" name="tax_id" class="form-input" value="{{ old('tax_id', $client->tax_id) }}" /></div>
            <div><label class="mb-1 block text-xs">Trade License</label><input type="text" name="trade_license" class="form-input" value="{{ old('trade_license', $client->trade_license) }}" /></div>
            <div>
                <label class="mb-1 block text-xs">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="md:col-span-2"><label class="mb-1 block text-xs">Address</label><textarea name="address" class="form-textarea" rows="2">{{ old('address', $client->address) }}</textarea></div>
            <div class="md:col-span-2"><label class="mb-1 block text-xs">Notes</label><textarea name="notes" class="form-textarea" rows="2">{{ old('notes', $client->notes) }}</textarea></div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Update Client</button>
            <a href="{{ route('admin.crm.clients.show', $client) }}" class="btn btn-outline-danger">Cancel</a>
        </div>
    </form>
</div>
@endsection
