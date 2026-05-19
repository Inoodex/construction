<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lead::with('creator');

        // Filter by source
        if ($request->has('source') && $request->source != '') {
            $query->where('source', $request->source);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by name or phone
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('student_name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $leads = $query->latest()->paginate(15);

        return view('admin.marketing.leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.marketing.leads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'current_education' => 'nullable|string|max:255',
            'preferred_country' => 'nullable|string|max:255',
            'preferred_course' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'next_follow_up_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        Lead::create($validated);

        return redirect()->route('admin.marketing.leads.index')->with('success', 'Lead collected successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        return view('admin.marketing.leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        return view('admin.marketing.leads.edit', compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'current_education' => 'nullable|string|max:255',
            'preferred_country' => 'nullable|string|max:255',
            'preferred_course' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|string',
            'next_follow_up_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $lead->update($validated);

        return redirect()->route('admin.marketing.leads.index')->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.marketing.leads.index')->with('success', 'Lead deleted successfully.');
    }
}
