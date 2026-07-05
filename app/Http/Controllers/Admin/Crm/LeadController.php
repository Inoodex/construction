<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with('assignedTo');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->search . '%')
                    ->orWhere('contact_person', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(15);
        $statuses = ['new', 'contacted', 'proposal_sent', 'negotiation', 'won', 'lost'];

        return view('admin.crm.leads.index', compact('leads', 'statuses'));
    }

    public function create()
    {
        $users = User::all();

        return view('admin.crm.leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'estimated_value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:new,contacted,proposal_sent,negotiation,won,lost',
            'assigned_to' => 'nullable|exists:users,id',
            'next_follow_up_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        $lead = Lead::create($validated);

        return redirect()->route('admin.crm.leads.show', $lead)
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['assignedTo', 'creator', 'communications.creator']);

        return view('admin.crm.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $users = User::all();

        return view('admin.crm.leads.edit', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'estimated_value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:new,contacted,proposal_sent,negotiation,won,lost',
            'assigned_to' => 'nullable|exists:users,id',
            'next_follow_up_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $lead->update($validated);

        return redirect()->route('admin.crm.leads.show', $lead)
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.crm.leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function addCommunication(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'type' => 'required|in:call,email,meeting,note',
            'subject' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $lead->communications()->create([
            'type' => $validated['type'],
            'subject' => $validated['subject'],
            'notes' => $validated['notes'],
            'date' => $validated['date'],
            'created_by' => auth()->id(),
        ]);

        $lead->update(['last_contacted_at' => now()]);

        return back()->with('success', 'Communication logged successfully.');
    }

    public function convertToClient(Lead $lead)
    {
        $client = \App\Models\Client::create([
            'company_name' => $lead->company_name,
            'contact_person' => $lead->contact_person,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'notes' => 'Converted from lead #' . $lead->id,
            'status' => 'active',
        ]);

        $lead->update(['status' => 'won']);

        return redirect()->route('admin.crm.clients.show', $client)
            ->with('success', 'Lead converted to client successfully.');
    }
}
