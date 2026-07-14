<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\InterimPaymentApplication;
use App\Models\IpaItem;
use App\Models\Project;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IpaController extends Controller
{
    public function index(Request $request)
    {
        $query = InterimPaymentApplication::with('project');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ipas = $query->latest()->paginate(15);
        $projects = Project::all();
        return view('admin.finance.ipas.index', compact('ipas', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.finance.ipas.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'       => 'required|exists:projects,id',
            'title'            => 'required|string|max:255',
            'application_date' => 'required|date',
            'period_start'     => 'required|date',
            'period_end'       => 'required|date|after_or_equal:period_start',
            'retention_rate'   => 'required|numeric|min:0|max:100',
            'notes'            => 'nullable|string',
        ]);

        $validated['ipa_number'] = 'IPA-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        $validated['previous_cumulative_amount'] = $this->getPreviousCumulative($validated['project_id']);
        $validated['applied_amount'] = 0;
        $validated['certified_amount'] = 0;
        $validated['retention_amount'] = 0;
        $validated['net_amount'] = 0;
        $validated['paid_amount'] = 0;
        $validated['status'] = 'draft';
        $validated['created_by'] = Auth::id();

        $ipa = InterimPaymentApplication::create($validated);

        $this->autoCreateItemsFromBoq($ipa);

        return redirect()->route('admin.finance.ipas.show', $ipa->id)
            ->with('success', 'IPA created with BOQ items.');
    }

    public function show(InterimPaymentApplication $ipa)
    {
        $ipa->load('project', 'creator', 'items', 'submittedBy', 'certifiedBy', 'approvedBy', 'invoice');
        $canSubmit = $ipa->status === 'draft' && $ipa->items()->count() > 0;
        return view('admin.finance.ipas.show', compact('ipa', 'canSubmit'));
    }

    public function printPdf(InterimPaymentApplication $ipa)
    {
        $ipa->load('project', 'items');
        $pdf = Pdf::loadView('admin.finance.ipas.pdf.ipa', compact('ipa'))
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'sans-serif')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->stream('IPA-'.$ipa->ipa_number.'.pdf');
    }

    public function edit(InterimPaymentApplication $ipa)
    {
        abort_if($ipa->status !== 'draft', 403, 'Only draft IPAs can be edited.');

        if ($ipa->items()->count() === 0) {
            $this->autoCreateItemsFromBoq($ipa);
        }

        $ipa->load('items');
        $projects = Project::all();

        return view('admin.finance.ipas.edit', compact('ipa', 'projects'));
    }

    public function update(Request $request, InterimPaymentApplication $ipa)
    {
        abort_if($ipa->status !== 'draft', 403, 'Only draft IPAs can be edited.');
        $validated = $request->validate([
            'project_id'       => 'required|exists:projects,id',
            'title'            => 'required|string|max:255',
            'application_date' => 'required|date',
            'period_start'     => 'required|date',
            'period_end'       => 'required|date|after_or_equal:period_start',
            'retention_rate'   => 'required|numeric|min:0|max:100',
            'notes'            => 'nullable|string',
            'items'            => 'nullable|array',
            'items.*.id'              => 'required|exists:ipa_items,id',
            'items.*.current_quantity'=> 'required|numeric|min:0',
            'items.*.unit_price'      => 'required|numeric|min:0',
        ]);

        $ipa->update($validated);

        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                $item = IpaItem::findOrFail($itemData['id']);
                $prevQty = $item->previous_quantity;
                $cumQty = $prevQty + $itemData['current_quantity'];
                $item->update([
                    'current_quantity'   => $itemData['current_quantity'],
                    'unit_price'         => $itemData['unit_price'],
                    'cumulative_quantity'=> $cumQty,
                    'previous_amount'    => $prevQty * $itemData['unit_price'],
                    'current_amount'     => $itemData['current_quantity'] * $itemData['unit_price'],
                    'cumulative_amount'  => $cumQty * $itemData['unit_price'],
                ]);
            }
            $this->recalculateIpa($ipa);
        }

        return redirect()->route('admin.finance.ipas.show', $ipa->id)
            ->with('success', 'IPA updated successfully.');
    }

    public function destroy(InterimPaymentApplication $ipa)
    {
        abort_if($ipa->status !== 'draft', 403, 'Only draft IPAs can be deleted.');
        $ipa->items()->delete();
        $ipa->delete();
        return redirect()->route('admin.finance.ipas.index')
            ->with('success', 'IPA deleted successfully.');
    }

    public function removeItem(InterimPaymentApplication $ipa, IpaItem $ipaItem)
    {
        abort_if($ipa->status !== 'draft', 403);
        $ipaItem->delete();
        $this->recalculateIpa($ipa);
        return back()->with('success', 'Item removed from IPA.');
    }

    public function submit(InterimPaymentApplication $ipa)
    {
        abort_if($ipa->status !== 'draft', 403);
        $ipa->update([
            'status'       => 'submitted',
            'submitted_by' => Auth::id(),
            'submitted_at' => now(),
        ]);
        return back()->with('success', 'IPA submitted for certification.');
    }

    public function certify(Request $request, InterimPaymentApplication $ipa)
    {
        abort_if($ipa->status !== 'submitted', 403);
        $request->validate([
            'certified_amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($ipa) {
                    if ($value > $ipa->applied_amount) {
                        $fail("The certified amount must be less than or equal to " . number_format($ipa->applied_amount) . ".");
                    }
                },
            ],
        ]);

        $retentionAmount = $request->certified_amount * ($ipa->retention_rate / 100);
        $netAmount = $request->certified_amount - $retentionAmount;

        $ipa->update([
            'certified_amount' => $request->certified_amount,
            'retention_amount' => $retentionAmount,
            'net_amount'       => $netAmount,
            'status'           => 'certified',
            'certified_by'     => Auth::id(),
            'certified_at'     => now(),
        ]);
        return back()->with('success', 'IPA certified.');
    }

    public function approve(InterimPaymentApplication $ipa)
    {
        abort_if($ipa->status !== 'certified', 403);
        $ipa->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'IPA approved.');
    }

    public function reject(Request $request, InterimPaymentApplication $ipa)
    {
        abort_if(!in_array($ipa->status, ['submitted', 'certified']), 403);
        $request->validate(['notes' => 'required|string']);
        $ipa->update([
            'status' => 'rejected',
            'notes'  => $ipa->notes . "\n[Rejected] " . $request->notes,
        ]);
        return back()->with('success', 'IPA rejected.');
    }

    public function generateInvoice(InterimPaymentApplication $ipa)
    {
        abort_if($ipa->status !== 'certified', 403);
        abort_if($ipa->invoice_id, 403, 'Invoice already generated for this IPA.');

        $invoice = Invoice::create([
            'project_id'      => $ipa->project_id,
            'invoice_number'  => 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
            'title'           => $ipa->ipa_number . ' - ' . $ipa->title,
            'issue_date'      => now()->format('Y-m-d'),
            'due_date'        => now()->addDays(30)->format('Y-m-d'),
            'subtotal'        => $ipa->certified_amount,
            'tax_rate'        => 0,
            'tax_amount'      => 0,
            'retention_rate'  => $ipa->retention_rate,
            'retention_amount'=> $ipa->retention_amount,
            'total_amount'    => $ipa->net_amount,
            'paid_amount'     => 0,
            'due_amount'      => $ipa->net_amount,
            'status'          => 'sent',
            'created_by'      => Auth::id(),
        ]);

        foreach ($ipa->items as $item) {
            $invoice->items()->create([
                'description' => "{$item->item_number} - {$item->description}",
                'quantity'    => $item->cumulative_quantity,
                'unit_price'  => $item->unit_price,
                'total_price' => $item->cumulative_amount,
            ]);
        }

        $ipa->update(['invoice_id' => $invoice->id]);
        return redirect()->route('admin.finance.invoices.show', $invoice->id)
            ->with('success', 'Invoice generated from certified IPA.');
    }

    private function autoCreateItemsFromBoq(InterimPaymentApplication $ipa): void
    {
        $boqItems = \App\Models\BoqItem::whereHas('boq', fn($q) => $q->where('project_id', $ipa->project_id))->get();

        $lastIpaItems = IpaItem::whereHas('ipa', fn($q) => $q->where('project_id', $ipa->project_id)->whereIn('status', ['certified', 'approved', 'paid']))
            ->get()
            ->groupBy('boq_item_id');

        foreach ($boqItems as $bi) {
            $prevItems = $lastIpaItems->get($bi->id, collect());
            $lastItem = $prevItems->sortByDesc('id')->first();
            $prevQty = $lastItem?->cumulative_quantity ?? 0;

            IpaItem::create([
                'ipa_id'             => $ipa->id,
                'boq_item_id'        => $bi->id,
                'item_number'        => $bi->item_number,
                'description'        => $bi->description,
                'unit'               => $bi->unit,
                'previous_quantity'  => $prevQty,
                'current_quantity'   => 0,
                'cumulative_quantity'=> $prevQty,
                'unit_price'         => $bi->unit_price,
                'previous_amount'    => $prevQty * $bi->unit_price,
                'current_amount'     => 0,
                'cumulative_amount'  => $prevQty * $bi->unit_price,
            ]);
        }

        $this->recalculateIpa($ipa);
    }

    private function recalculateIpa(InterimPaymentApplication $ipa): void
    {
        $applied = $ipa->items()->sum('current_amount');
        $prevCumulative = $ipa->items()->sum('previous_amount');

        $ipa->update([
            'previous_cumulative_amount' => $prevCumulative,
            'applied_amount'             => $applied,
        ]);
    }

    private function getPreviousCumulative(int $projectId): float
    {
        return InterimPaymentApplication::where('project_id', $projectId)
            ->whereIn('status', ['certified', 'approved', 'paid'])
            ->sum('applied_amount');
    }
}
