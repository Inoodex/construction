<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Project;
use App\Services\CostOverrunService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::with('project', 'creator');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('cost_code')) {
            $query->where('cost_code', 'like', $request->cost_code . '%');
        }

        $budgets = $query->latest()->paginate(15);
        $projects = Project::all();
        $costCodes = Budget::select('cost_code')->distinct()->pluck('cost_code');

        return view('admin.finance.budgets.index', compact('budgets', 'projects', 'costCodes'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.finance.budgets.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'cost_code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'budgeted_amount' => 'required|numeric|min:0',
            'actual_amount' => 'required|numeric|min:0',
            'financial_year' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        $budget = Budget::create($validated);

        app(CostOverrunService::class)->checkBudget($budget);

        return redirect()->route('admin.finance.budgets.index')
            ->with('success', 'Budget created successfully.');
    }

    public function show(Budget $budget)
    {
        $budget->load('project', 'creator');
        return view('admin.finance.budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $projects = Project::all();
        return view('admin.finance.budgets.edit', compact('budget', 'projects'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'cost_code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'budgeted_amount' => 'required|numeric|min:0',
            'actual_amount' => 'required|numeric|min:0',
            'financial_year' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $budget->update($validated);

        app(CostOverrunService::class)->checkBudget($budget);

        return redirect()->route('admin.finance.budgets.index')
            ->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->route('admin.finance.budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }
}
