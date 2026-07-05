@extends('admin.layouts.master')

@section('title', 'Client - ' . $client->company_name)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h2 class="text-xl font-semibold uppercase">{{ $client->company_name }}</h2>
    <div class="flex gap-2">
        <a href="{{ route('admin.crm.clients.index') }}" class="btn btn-secondary">Back to List</a>
        <a href="{{ route('admin.crm.clients.edit', $client) }}" class="btn btn-primary">Edit</a>
    </div>
</div>

<div class="panel mt-6">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div><span class="text-xs text-white-dark">Company Name</span><p class="font-semibold">{{ $client->company_name }}</p></div>
        <div><span class="text-xs text-white-dark">Contact Person</span><p>{{ $client->contact_person ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Email</span><p>{{ $client->email ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Phone</span><p>{{ $client->phone ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Mobile</span><p>{{ $client->mobile ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Tax ID</span><p>{{ $client->tax_id ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Trade License</span><p>{{ $client->trade_license ?? '—' }}</p></div>
        <div><span class="text-xs text-white-dark">Status</span><p><span class="badge {{ $client->status == 'active' ? 'badge-outline-success' : 'badge-outline-secondary' }} capitalize">{{ $client->status }}</span></p></div>
        <div class="md:col-span-4"><span class="text-xs text-white-dark">Address</span><p>{{ $client->address ?? '—' }}</p></div>
        <div class="md:col-span-4"><span class="text-xs text-white-dark">Notes</span><p>{{ $client->notes ?? '—' }}</p></div>
    </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <div class="panel">
        <div class="mb-3 flex items-center justify-between">
            <h5 class="text-base font-semibold">Contact Persons</h5>
            <button type="button" onclick="document.getElementById('addContactForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Add</button>
        </div>

        <div id="addContactForm" class="mb-3 hidden rounded-lg border p-3 dark:border-gray-700">
            <form action="{{ route('admin.crm.clients.contacts.store', $client) }}" method="POST" class="flex flex-wrap items-end gap-2">
                @csrf
                <input type="text" name="name" class="form-input flex-1 text-xs" placeholder="Name" required />
                <input type="text" name="designation" class="form-input flex-1 text-xs" placeholder="Designation" />
                <input type="email" name="email" class="form-input flex-1 text-xs" placeholder="Email" />
                <input type="text" name="phone" class="form-input flex-1 text-xs" placeholder="Phone" />
                <label class="flex items-center gap-1 text-xs"><input type="checkbox" name="is_primary" value="1" /> Primary</label>
                <button type="submit" class="btn btn-sm btn-primary">Add</button>
            </form>
        </div>

        @if($client->contacts->count() > 0)
            <div class="space-y-2">
                @foreach($client->contacts as $contact)
                    <div class="flex items-center justify-between rounded-lg border p-2 text-xs dark:border-gray-700">
                        <div>
                            <span class="font-semibold">{{ $contact->name }}</span>
                            @if($contact->designation) <span class="text-white-dark">— {{ $contact->designation }}</span> @endif
                            @if($contact->is_primary) <span class="badge badge-outline-primary text-xs ml-1">Primary</span> @endif
                            <div class="mt-1 text-white-dark">
                                @if($contact->email) <span>{{ $contact->email }}</span> @endif
                                @if($contact->phone) <span class="ml-2">{{ $contact->phone }}</span> @endif
                            </div>
                        </div>
                        <form action="{{ route('admin.crm.clients.contacts.destroy', [$client, $contact]) }}" method="POST" onsubmit="return confirm('Remove this contact?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">×</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-white-dark">No contacts added yet.</p>
        @endif
    </div>

    <div class="panel">
        <div class="mb-3 flex items-center justify-between">
            <h5 class="text-base font-semibold">Communication Log</h5>
            <button type="button" onclick="document.getElementById('addCommForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Log</button>
        </div>

        <div id="addCommForm" class="mb-3 hidden rounded-lg border p-3 dark:border-gray-700">
            <form action="{{ route('admin.crm.clients.communications.store', $client) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-2 md:grid-cols-4">
                    <select name="type" class="form-select text-xs" required>
                        <option value="note">Note</option>
                        <option value="call">Call</option>
                        <option value="email">Email</option>
                        <option value="meeting">Meeting</option>
                    </select>
                    <input type="text" name="subject" class="form-input text-xs" placeholder="Subject" />
                    <input type="date" name="date" class="form-input text-xs" value="{{ date('Y-m-d') }}" required />
                    <button type="submit" class="btn btn-sm btn-primary">Log</button>
                </div>
                <div class="mt-2">
                    <textarea name="notes" class="form-textarea text-xs" rows="2" placeholder="Notes..."></textarea>
                </div>
            </form>
        </div>

        @if($client->communications->count() > 0)
            <div class="space-y-2">
                @foreach($client->communications as $log)
                    <div class="rounded-lg border p-2 text-xs dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="badge badge-outline-{{ $log->type == 'call' ? 'info' : ($log->type == 'email' ? 'primary' : ($log->type == 'meeting' ? 'warning' : 'secondary')) }} text-xs capitalize">{{ $log->type }}</span>
                                @if($log->subject) <span class="font-semibold">{{ $log->subject }}</span> @endif
                            </div>
                            <span class="text-white-dark">{{ $log->date->format('d M Y') }} by {{ $log->creator?->name ?? '—' }}</span>
                        </div>
                        @if($log->notes) <p class="mt-1 text-white-dark">{{ $log->notes }}</p> @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-white-dark">No communications logged yet.</p>
        @endif
    </div>
</div>

<div class="mt-6 panel">
    <div class="mb-3 flex items-center justify-between">
        <h5 class="text-base font-semibold">Documents</h5>
        <button type="button" onclick="document.getElementById('addDocForm').classList.toggle('hidden')" class="btn btn-sm btn-outline-primary">+ Upload</button>
    </div>

    <div id="addDocForm" class="mb-3 hidden rounded-lg border p-3 dark:border-gray-700">
        <form action="{{ route('admin.crm.clients.documents.store', $client) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-wrap items-end gap-2">
                <input type="text" name="title" class="form-input flex-1 text-xs" placeholder="Document title" required />
                <input type="file" name="file" class="form-input flex-1 text-xs" required />
                <input type="text" name="type" class="form-input flex-1 text-xs" placeholder="Type (e.g. contract, proposal)" />
                <button type="submit" class="btn btn-sm btn-primary">Upload</button>
            </div>
        </form>
    </div>

    @if($client->documents->count() > 0)
        <div class="space-y-2">
            @foreach($client->documents as $doc)
                <div class="flex items-center justify-between rounded-lg border p-2 text-xs dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold">{{ $doc->title }}</span>
                        @if($doc->type) <span class="badge badge-outline-secondary text-xs">{{ $doc->type }}</span> @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                        <form action="{{ route('admin.crm.clients.documents.destroy', [$client, $doc]) }}" method="POST" onsubmit="return confirm('Delete this document?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">×</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-xs text-white-dark">No documents uploaded yet.</p>
    @endif
</div>
@endsection
