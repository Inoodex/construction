@extends('admin.layouts.master')

@section('title', 'Create Client')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Create Client</h5>
        <a href="{{ route('admin.crm.clients.index') }}" class="btn btn-outline-danger">Back</a>
    </div>

    <form action="{{ route('admin.crm.clients.store') }}" method="POST">
        @csrf

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-xs">Company Name <span class="text-danger">*</span></label>
                <input type="text" name="company_name" class="form-input" value="{{ old('company_name') }}" required />
                @error('company_name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs">Contact Person</label>
                <input type="text" name="contact_person" class="form-input" value="{{ old('contact_person') }}" />
            </div>
            <div>
                <label class="mb-1 block text-xs">Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" />
            </div>
            <div>
                <label class="mb-1 block text-xs">Phone</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" />
            </div>
            <div>
                <label class="mb-1 block text-xs">Mobile</label>
                <input type="text" name="mobile" class="form-input" value="{{ old('mobile') }}" />
            </div>
            <div>
                <label class="mb-1 block text-xs">Tax ID</label>
                <input type="text" name="tax_id" class="form-input" value="{{ old('tax_id') }}" />
            </div>
            <div>
                <label class="mb-1 block text-xs">Trade License</label>
                <input type="text" name="trade_license" class="form-input" value="{{ old('trade_license') }}" />
            </div>
            <div>
                <label class="mb-1 block text-xs">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs">Address</label>
                <textarea name="address" class="form-textarea" rows="2">{{ old('address') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs">Notes</label>
                <textarea name="notes" class="form-textarea" rows="2">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="mb-5">
            <h6 class="mb-3 text-sm font-semibold">Contact Persons</h6>
            <div id="contacts-wrapper">
                <div class="mb-2 grid grid-cols-1 gap-2 md:grid-cols-5 contact-row">
                    <input type="text" name="contacts[0][name]" class="form-input text-xs" placeholder="Name" />
                    <input type="text" name="contacts[0][designation]" class="form-input text-xs" placeholder="Designation" />
                    <input type="email" name="contacts[0][email]" class="form-input text-xs" placeholder="Email" />
                    <input type="text" name="contacts[0][phone]" class="form-input text-xs" placeholder="Phone" />
                    <label class="flex items-center gap-1 text-xs"><input type="checkbox" name="contacts[0][is_primary]" value="1" /> Primary</label>
                </div>
            </div>
            <button type="button" onclick="addContactRow()" class="btn btn-sm btn-outline-primary">+ Add Another Contact</button>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Create Client</button>
            <a href="{{ route('admin.crm.clients.index') }}" class="btn btn-outline-danger">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let contactIndex = 1;
function addContactRow() {
    const html = `<div class="mb-2 grid grid-cols-1 gap-2 md:grid-cols-5 contact-row">
        <input type="text" name="contacts[${contactIndex}][name]" class="form-input text-xs" placeholder="Name" />
        <input type="text" name="contacts[${contactIndex}][designation]" class="form-input text-xs" placeholder="Designation" />
        <input type="email" name="contacts[${contactIndex}][email]" class="form-input text-xs" placeholder="Email" />
        <input type="text" name="contacts[${contactIndex}][phone]" class="form-input text-xs" placeholder="Phone" />
        <label class="flex items-center gap-1 text-xs"><input type="checkbox" name="contacts[${contactIndex}][is_primary]" value="1" /> Primary</label>
    </div>`;
    document.getElementById('contacts-wrapper').insertAdjacentHTML('beforeend', html);
    contactIndex++;
}
</script>
@endpush
@endsection
