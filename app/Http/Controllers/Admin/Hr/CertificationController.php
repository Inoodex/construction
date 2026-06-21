<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Employee;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Certification::with('employee');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest('expiry_date')->paginate(20);
        $employees = Employee::active()->pluck('full_name', 'id');

        return view('admin.hr.certifications.index', compact('records', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.certifications.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'certification_name' => 'required|string|max:255',
            'issuing_authority' => 'nullable|string|max:255',
            'certificate_no' => 'nullable|string|max:100',
            'category' => 'required|in:certification,license,permit',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:active,expired,suspended,revoked',
            'renewal_reminder_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        Certification::create($validated);

        return redirect()->route('admin.hr.certifications.index')
            ->with('success', 'Certification created.');
    }

    public function show(Certification $certification)
    {
        $certification->load('employee');
        return view('admin.hr.certifications.show', compact('certification'));
    }

    public function edit(Certification $certification)
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.certifications.edit', compact('certification', 'employees'));
    }

    public function update(Request $request, Certification $certification)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'certification_name' => 'required|string|max:255',
            'issuing_authority' => 'nullable|string|max:255',
            'certificate_no' => 'nullable|string|max:100',
            'category' => 'required|in:certification,license,permit',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:active,expired,suspended,revoked',
            'renewal_reminder_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $certification->update($validated);

        return redirect()->route('admin.hr.certifications.index')
            ->with('success', 'Certification updated.');
    }

    public function destroy(Certification $certification)
    {
        $certification->delete();
        return back()->with('success', 'Certification deleted.');
    }
}
