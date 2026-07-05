@extends('admin.layouts.master')

@section('title', 'Create Lead')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Create Lead</h5>
        <a href="{{ route('admin.crm.leads.index') }}" class="btn btn-outline-danger">Back</a>
    </div>

    <form action="{{ route('admin.crm.leads.store') }}" method="POST">
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
                <label class="mb-1 block text-xs">Source</label>
                <input type="text" name="source" class="form-input" placeholder="e.g. website, referral, tender" value="{{ old('source') }}" />
            </div>
            <div>
                <label class="mb-1 block text-xs">Estimated Value (৳)</label>
                <input type="number" step="0.01" min="0" name="estimated_value" class="form-input" value="{{ old('estimated_value') }}" />
            </div>
            <div>
                <label class="mb-1 block text-xs">Status</label>
                <select name="status" class="form-select">
                    <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ old('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="proposal_sent" {{ old('status') == 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                    <option value="negotiation" {{ old('status') == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                    <option value="won" {{ old('status') == 'won' ? 'selected' : '' }}>Won</option>
                    <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs">Assigned To</label>
                <select name="assigned_to" class="form-select">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs">Next Follow-up</label>
                <input type="date" name="next_follow_up_at" class="form-input" value="{{ old('next_follow_up_at') }}" />
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs">Description</label>
                <textarea name="description" class="form-textarea" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs">Notes</label>
                <textarea name="notes" class="form-textarea" rows="2">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Create Lead</button>
            <a href="{{ route('admin.crm.leads.index') }}" class="btn btn-outline-danger">Cancel</a>
        </div>
    </form>
</div>
@endsection
