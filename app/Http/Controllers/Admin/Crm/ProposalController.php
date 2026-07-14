<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalItem;
use App\Models\Lead;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = Proposal::with(['lead', 'client', 'creator']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('proposal_number', 'like', '%' . $request->search . '%')
                    ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $proposals = $query->latest()->paginate(15);

        return view('admin.crm.proposals.index', compact('proposals'));
    }

    public function create(Request $request)
    {
        $leads = Lead::whereNotIn('status', ['lost'])->get();
        $clients = Client::where('status', 'active')->get();
        $selectedLead = $request->lead_id ? Lead::find($request->lead_id) : null;
        $selectedClient = $request->client_id ? Client::find($request->client_id) : null;

        return view('admin.crm.proposals.create', compact('leads', 'clients', 'selectedLead', 'selectedClient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'nullable|exists:leads,id',
            'client_id' => 'nullable|exists:clients,id',
            'title' => 'required|string|max:255',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $number = 'PRO-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $subtotal = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $taxAmount = $subtotal * ($validated['tax_rate'] / 100);
        $totalAmount = $subtotal + $taxAmount;

        $proposal = DB::transaction(function () use ($validated, $number, $request, $subtotal, $taxAmount, $totalAmount) {
            $proposal = Proposal::create([
                'lead_id' => $validated['lead_id'] ?? null,
                'client_id' => $validated['client_id'] ?? null,
                'proposal_number' => $number,
                'title' => $validated['title'],
                'status' => $validated['status'],
                'subtotal' => $subtotal,
                'tax_rate' => $validated['tax_rate'],
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'valid_until' => $validated['valid_until'],
                'notes' => $validated['notes'],
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $i => $item) {
                $proposal->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'] ?? null,
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'sort_order' => $i,
                ]);
            }

            return $proposal;
        });

        return redirect()->route('admin.crm.proposals.show', $proposal)
            ->with('success', "Proposal {$proposal->proposal_number} created successfully.");
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['items', 'lead', 'client', 'creator']);

        return view('admin.crm.proposals.show', compact('proposal'));
    }

    public function printPdf(Proposal $proposal)
    {
        $proposal->load('items', 'lead', 'client');
        $pdf = Pdf::loadView('admin.crm.proposals.pdf.proposal', compact('proposal'))
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream('PRO-'.$proposal->proposal_number.'.pdf');
    }

    public function edit(Proposal $proposal)
    {
        $proposal->load('items');
        $leads = Lead::whereNotIn('status', ['lost'])->get();
        $clients = Client::where('status', 'active')->get();

        return view('admin.crm.proposals.edit', compact('proposal', 'leads', 'clients'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'lead_id' => 'nullable|exists:leads,id',
            'client_id' => 'nullable|exists:clients,id',
            'title' => 'required|string|max:255',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $taxAmount = $subtotal * ($validated['tax_rate'] / 100);
        $totalAmount = $subtotal + $taxAmount;

        DB::transaction(function () use ($proposal, $validated, $request, $subtotal, $taxAmount, $totalAmount) {
            $proposal->update([
                'lead_id' => $validated['lead_id'] ?? null,
                'client_id' => $validated['client_id'] ?? null,
                'title' => $validated['title'],
                'status' => $validated['status'],
                'subtotal' => $subtotal,
                'tax_rate' => $validated['tax_rate'],
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'valid_until' => $validated['valid_until'],
                'notes' => $validated['notes'],
            ]);

            $proposal->items()->delete();
            foreach ($request->items as $i => $item) {
                $proposal->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'] ?? null,
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'sort_order' => $i,
                ]);
            }
        });

        return redirect()->route('admin.crm.proposals.show', $proposal)
            ->with('success', 'Proposal updated successfully.');
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();

        return redirect()->route('admin.crm.proposals.index')
            ->with('success', 'Proposal deleted successfully.');
    }

    public function updateStatus(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
        ]);

        $proposal->update(['status' => $validated['status']]);

        return back()->with('success', 'Proposal status updated to ' . str_replace('_', ' ', $validated['status']) . '.');
    }
}
