<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PpeIssuance;
use Illuminate\Http\Request;

class PpeIssuanceController extends Controller
{
    public function index(Request $request)
    {
        $query = PpeIssuance::with('employee');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('returned')) {
            $request->returned === 'yes'
                ? $query->whereNotNull('return_date')
                : $query->whereNull('return_date');
        }

        $records = $query->latest('issue_date')->paginate(20);
        $employees = Employee::active()->pluck('full_name', 'id');
        $categories = PpeIssuance::select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('admin.hr.ppe-issuances.index', compact('records', 'employees', 'categories'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.ppe-issuances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'issue_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string|max:50',
            'condition_on_issue' => 'nullable|string|max:100',
            'return_date' => 'nullable|date|after_or_equal:issue_date',
            'condition_on_return' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        PpeIssuance::create($validated);

        return redirect()->route('admin.hr.ppe-issuances.index')
            ->with('success', 'PPE issuance recorded.');
    }

    public function show(PpeIssuance $ppeIssuance)
    {
        $ppeIssuance->load('employee');
        return view('admin.hr.ppe-issuances.show', compact('ppeIssuance'));
    }

    public function edit(PpeIssuance $ppeIssuance)
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.ppe-issuances.edit', compact('ppeIssuance', 'employees'));
    }

    public function update(Request $request, PpeIssuance $ppeIssuance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'issue_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string|max:50',
            'condition_on_issue' => 'nullable|string|max:100',
            'return_date' => 'nullable|date|after_or_equal:issue_date',
            'condition_on_return' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $ppeIssuance->update($validated);

        return redirect()->route('admin.hr.ppe-issuances.index')
            ->with('success', 'PPE issuance updated.');
    }

    public function destroy(PpeIssuance $ppeIssuance)
    {
        $ppeIssuance->delete();
        return back()->with('success', 'PPE issuance deleted.');
    }
}
