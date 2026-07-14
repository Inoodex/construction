<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialIssueSlip;
use App\Models\MaterialIssueSlipItem;
use App\Models\Project;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class MaterialIssueSlipController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialIssueSlip::with('project', 'site', 'recipient');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $slips = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.procurement.material-issue-slips.index', compact('slips', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $sites = Site::all();
        $users = User::all();
        $materials = Material::all();
        return view('admin.procurement.material-issue-slips.create', compact('projects', 'sites', 'users', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'site_id' => 'required|exists:sites,id',
            'issued_to' => 'required|exists:users,id',
            'issue_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
        ]);

        DB::transaction(function () use ($validated) {
            $number = 'ISS-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $slip = MaterialIssueSlip::create([
                'project_id' => $validated['project_id'],
                'site_id' => $validated['site_id'],
                'issued_to' => $validated['issued_to'],
                'issue_number' => $number,
                'issue_date' => $validated['issue_date'],
            ]);

            foreach ($validated['items'] as $item) {
                MaterialIssueSlipItem::create([
                    'material_issue_slip_id' => $slip->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                ]);

                // Deduct from site stock
                DB::table('stocks')
                    ->where('site_id', $validated['site_id'])
                    ->where('material_id', $item['material_id'])
                    ->decrement('quantity', $item['quantity']);
            }
        });

        return redirect()->route('admin.procurement.material-issue-slips.index')
            ->with('success', 'Material issued successfully.');
    }

    public function show(MaterialIssueSlip $materialIssueSlip)
    {
        $materialIssueSlip->load('project', 'site', 'recipient', 'items.material');
        return view('admin.procurement.material-issue-slips.show', compact('materialIssueSlip'));
    }

    public function destroy(MaterialIssueSlip $materialIssueSlip)
    {
        $materialIssueSlip->delete();
        return redirect()->route('admin.procurement.material-issue-slips.index')
            ->with('success', 'Issue slip deleted successfully.');
    }

    public function printPdf(MaterialIssueSlip $materialIssueSlip)
    {
        $materialIssueSlip->load('project', 'site', 'recipient', 'items.material');
        $pdf = Pdf::loadView('admin.procurement.material-issue-slips.pdf.issue-slip', compact('materialIssueSlip'));
        return $pdf->stream();
    }
}
