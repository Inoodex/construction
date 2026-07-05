@extends('admin.layouts.master')

@section('title', 'Edit Lead')

@section('content')
<div class="panel">
    <div class="mb-5 flex items-center justify-between">
        <h5 class="text-lg font-semibold dark:text-white-light">Edit Lead</h5>
        <a href="{{ route('admin.crm.leads.show', $lead) }}" class="btn btn-outline-info">Cancel</a>
    </div>

    <form action="{{ route('admin.crm.leads.update', $lead) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-xs">Company Name <span class="text-danger">*</span></label>
                <input type="text" name="company_name" class="form-input" value="{{ old('company_name', $lead->company_name) }}" required />
                @error('company_name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs">Contact Person</label>
                <input type="text" name="contact_person" class="form-input" value="{{ old('contact_person', $lead->contact_person) }}" />
            </div>
            <div><label class="mb-1 block text-xs">Email</label><input type="email" name="email" class="form-input" value="{{ old('email', $lead->email) }}" /></div>
            <div><label class="mb-1 block text-xs">Phone</label><input type="text" name="phone" class="form-input" value="{{ old('phone', $lead->phone) }}" /></div>
            <div><label class="mb-1 block text-xs">Source</label><input type="text" name="source" class="form-input" value="{{ old('source', $lead->source) }}" /></div>
            <div><label class="mb-1 block text-xs">Estimated Value (৳)</label><input type="number" step="0.01" min="0" name="estimated_value" class="form-input" value="{{ old('estimated_value', $lead->estimated_value) }}" /></div>
            <div>
                <label class="mb-1 block text-xs">Status</label>
                <select name="status" class="form-select">
                    <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="proposal_sent" {{ old('status', $lead->status) == 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                    <option value="negotiation" {{ old('status', $lead->status) == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                    <option value="won" {{ old('status', $lead->status) == 'won' ? 'selected' : '' }}>Won</option>
                    <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs">Assigned To</label>
                <select name="assigned_to" class="form-select">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="mb-1 block text-xs">Next Follow-up</label><input type="date" name="next_follow_up_at" class="form-input" value="{{ old('next_follow_up_at', $lead->next_follow_up_at?->format('Y-m-d')) }}" /></div>
            <div class="md:col-span-2"><label class="mb-1 block text-xs">Description</label><textarea name="description" class="form-textarea" rows="3">{{ old('description', $lead->description) }}</textarea></div>
            <div class="md:col-span-2"><label class="mb-1 block text-xs">Notes</label><textarea name="notes" class="form-textarea" rows="2">{{ old('notes', $lead->notes) }}</textarea></div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Update Lead</button>
            <a href="{{ route('admin.crm.leads.show', $lead) }}" class="btn btn-outline-danger">Cancel</a>
        </div>
    </form>
</div>
@endsection
