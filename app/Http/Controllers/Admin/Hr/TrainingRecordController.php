<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\TrainingRecord;
use Illuminate\Http\Request;

class TrainingRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainingRecord::with('employee');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest('start_date')->paginate(20);
        $employees = Employee::active()->pluck('full_name', 'id');

        return view('admin.hr.training-records.index', compact('records', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.training-records.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'training_name' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in-progress,completed,expired',
            'certificate_no' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date|after_or_equal:start_date',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        TrainingRecord::create($validated);

        return redirect()->route('admin.hr.training-records.index')
            ->with('success', 'Training record created.');
    }

    public function show(TrainingRecord $trainingRecord)
    {
        $trainingRecord->load('employee');
        return view('admin.hr.training-records.show', compact('trainingRecord'));
    }

    public function edit(TrainingRecord $trainingRecord)
    {
        $employees = Employee::active()->orderBy('full_name')->get();
        return view('admin.hr.training-records.edit', compact('trainingRecord', 'employees'));
    }

    public function update(Request $request, TrainingRecord $trainingRecord)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'training_name' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in-progress,completed,expired',
            'certificate_no' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date|after_or_equal:start_date',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $trainingRecord->update($validated);

        return redirect()->route('admin.hr.training-records.index')
            ->with('success', 'Training record updated.');
    }

    public function destroy(TrainingRecord $trainingRecord)
    {
        $trainingRecord->delete();
        return back()->with('success', 'Training record deleted.');
    }
}
