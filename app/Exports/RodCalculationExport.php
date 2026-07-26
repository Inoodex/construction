<?php

namespace App\Exports;

use App\Models\RodCalculation;
use App\Services\RodCalculationService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class RodCalculationExport implements FromView, WithTitle, ShouldAutoSize
{
    protected RodCalculation $rodCalculation;

    public function __construct(RodCalculation $rodCalculation)
    {
        $this->rodCalculation = $rodCalculation;
    }

    public function view(): View
    {
        $this->rodCalculation->load('project', 'creator', 'approver', 'members.bars');
        $service = new RodCalculationService();
        $summary = $service->summary($this->rodCalculation);

        return view('admin.finance.rod-calculations.excel.bbs', [
            'rodCalculation' => $this->rodCalculation,
            'summary'        => $summary,
        ]);
    }

    public function title(): string
    {
        return $this->rodCalculation->reference_no;
    }
}
