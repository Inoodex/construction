<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientContact;
use App\Models\CommunicationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->search . '%')
                    ->orWhere('contact_person', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->latest()->paginate(15);

        return view('admin.crm.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.crm.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:100',
            'trade_license' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'required|string|max:255',
            'contacts.*.designation' => 'nullable|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.phone' => 'nullable|string|max:50',
            'contacts.*.is_primary' => 'boolean',
        ]);

        $client = Client::create($validated);

        if ($request->filled('contacts')) {
            foreach ($request->contacts as $contact) {
                $client->contacts()->create($contact);
            }
        }

        return redirect()->route('admin.crm.clients.show', $client)
            ->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        $client->load(['contacts', 'documents', 'communications.creator']);

        return view('admin.crm.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $client->load('contacts');

        return view('admin.crm.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:100',
            'trade_license' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $client->update($validated);

        return redirect()->route('admin.crm.clients.show', $client)
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.crm.clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    public function addContact(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'is_primary' => 'boolean',
        ]);

        if ($validated['is_primary'] ?? false) {
            $client->contacts()->update(['is_primary' => false]);
        }

        $client->contacts()->create($validated);

        return back()->with('success', 'Contact added successfully.');
    }

    public function removeContact(Client $client, ClientContact $contact)
    {
        $contact->delete();

        return back()->with('success', 'Contact removed successfully.');
    }

    public function addCommunication(Request $request, Client $client)
    {
        $validated = $request->validate([
            'type' => 'required|in:call,email,meeting,note',
            'subject' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $client->communications()->create([
            'type' => $validated['type'],
            'subject' => $validated['subject'],
            'notes' => $validated['notes'],
            'date' => $validated['date'],
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Communication logged successfully.');
    }

    public function uploadDocument(Request $request, Client $client)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
            'type' => 'nullable|string|max:100',
        ]);

        $path = $request->file('file')->store('client-documents', 'public');

        $client->documents()->create([
            'title' => $validated['title'],
            'file_path' => $path,
            'type' => $validated['type'],
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function deleteDocument(Client $client, \App\Models\ClientDocument $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}
