<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use App\Models\TenderPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenderPackageController extends Controller
{
    public function store(Request $request, Tender $tender)
    {
        $validated = $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|in:boq,specification,terms,drawing,other',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('tender-packages', 'public');
        }

        $validated['tender_id'] = $tender->id;
        unset($validated['file']);
        TenderPackage::create($validated);

        return redirect()->route('admin.finance.tenders.show', $tender)
            ->with('success', 'Document added to tender package.');
    }

    public function destroy(Tender $tender, TenderPackage $tenderPackage)
    {
        if ($tenderPackage->file_path && Storage::disk('public')->exists($tenderPackage->file_path)) {
            Storage::disk('public')->delete($tenderPackage->file_path);
        }

        $tenderPackage->delete();

        return redirect()->route('admin.finance.tenders.show', $tender)
            ->with('success', 'Document removed from tender package.');
    }
}
