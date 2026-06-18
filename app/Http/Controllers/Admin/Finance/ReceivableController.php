<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Receivable;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    public function index(Request $request)
    {
        $query = Receivable::with('project');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('payer_name', 'like', "%{$s}%")
                    ->orWhere('receivable_number', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $receivables = $query->latest()->paginate(15);

        return view('admin.finance.receivables.index', compact('receivables'));
    }

    public function create()
    {
        $projects = Project::pluck('name', 'id');
        $nextNumber = 'AR-' . date('Ymd') . '-' . str_pad(Receivable::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.finance.receivables.create', compact('projects', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receivable_number' => 'required|string|max:50|unique:receivables',
            'project_id' => 'nullable|exists:projects,id',
            'payer_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';
        $validated['created_by'] = auth()->id();

        Receivable::create($validated);

        return redirect()->route('admin.finance.receivables.index')
            ->with('success', 'Receivable created successfully.');
    }

    public function show(Receivable $receivable)
    {
        $receivable->load('project', 'payments');
        return view('admin.finance.receivables.show', compact('receivable'));
    }

    public function destroy(Receivable $receivable)
    {
        $receivable->delete();
        return redirect()->route('admin.finance.receivables.index')
            ->with('success', 'Receivable deleted successfully.');
    }

    public function addPayment(Request $request, Receivable $receivable)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $receivable->due_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $receivable->payments()->create($validated);
        $receivable->increment('paid_amount', $validated['amount']);

        if ($receivable->paid_amount >= $receivable->amount) {
            $receivable->update(['status' => 'paid']);
        } elseif ($receivable->paid_amount > 0) {
            $receivable->update(['status' => 'partial']);
        }

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function removePayment(Receivable $receivable, $payment)
    {
        $payment = $receivable->payments()->findOrFail($payment);
        $receivable->decrement('paid_amount', $payment->amount);
        $payment->delete();

        if ($receivable->paid_amount <= 0) {
            $receivable->update(['status' => 'pending']);
        } elseif ($receivable->paid_amount < $receivable->amount) {
            $receivable->update(['status' => 'partial']);
        }

        return back()->with('success', 'Payment removed successfully.');
    }
}
