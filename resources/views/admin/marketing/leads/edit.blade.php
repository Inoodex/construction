@extends('admin.layouts.master')

@section('title', 'Edit Data - Marketing')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Data</h2>
        <a href="{{ route('admin.marketing.leads.index') }}" class="btn btn-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.marketing.leads.update', $lead->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="form-group">
                    <label for="student_name">Student Name <span class="text-danger">*</span></label>
                    <input type="text" name="student_name" id="student_name" class="form-input" required
                        value="{{ old('student_name', $lead->student_name) }}" />
                    @error('student_name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone/WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="phone" class="form-input" required
                        value="{{ old('phone', $lead->phone) }}" />
                    @error('phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-input"
                        value="{{ old('email', $lead->email) }}" />
                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="source">Contact Source</label>
                    <select name="source" id="source" class="form-select">
                        <option value="Phone" {{ old('source', $lead->source) == 'Phone' ? 'selected' : '' }}>Phone Call
                        </option>
                        <option value="Message" {{ old('source', $lead->source) == 'Message' ? 'selected' : '' }}>WhatsApp/SMS
                        </option>
                        <option value="Messenger" {{ old('source', $lead->source) == 'Messenger' ? 'selected' : '' }}>FB
                            Messenger</option>
                        <option value="Online Chat" {{ old('source', $lead->source) == 'Online Chat' ? 'selected' : '' }}>
                            Website Chat
                        </option>
                        <option value="Walk-in" {{ old('source', $lead->source) == 'Walk-in' ? 'selected' : '' }}>Walk-in
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="current_education">Current Education</label>
                    <input type="text" name="current_education" id="current_education" class="form-input"
                        value="{{ old('current_education', $lead->current_education) }}" placeholder="e.g. HSC, Bachelor" />
                </div>
                <div class="form-group">
                    <label for="preferred_country">Preferred Country</label>
                    <input type="text" name="preferred_country" id="preferred_country" class="form-input"
                        value="{{ old('preferred_country', $lead->preferred_country) }}"
                        placeholder="e.g. UK, Canada, Australia" />
                </div>
                <div class="form-group">
                    <label for="preferred_course">Preferred Course</label>
                    <input type="text" name="preferred_course" id="preferred_course" class="form-input"
                        value="{{ old('preferred_course', $lead->preferred_course) }}" placeholder="e.g. MBA, Computing" />
                </div>
                <div class="form-group">
                    <label for="next_follow_up_at">Next Follow-up Date</label>
                    <input type="date" name="next_follow_up_at" id="next_follow_up_at" class="form-input"
                        value="{{ old('next_follow_up_at', $lead->next_follow_up_at) }}" />
                </div>
            </div>

            <div class="form-group mt-5">
                <label for="notes">Notes/Remarks</label>
                <textarea name="notes" id="notes" class="form-textarea" rows="4"
                    placeholder="Brief details about the client interest...">{{ old('notes', $lead->notes) }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn btn-primary px-10">Update Lead</button>
                <button type="button" onclick="window.location.href='{{ route('admin.marketing.leads.index') }}'"
                    class="btn btn-outline-danger">Cancel</button>
            </div>
        </form>
    </div>
@endsection