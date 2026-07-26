<?php

namespace App\Exports;

use App\Models\ConcreteRatio;
use App\Services\ConcreteRatioService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ConcreteRatioExport implements FromView, WithTitle, ShouldAutoSize
{
    protected ConcreteRatio $concreteRatio;

    public function __construct(ConcreteRatio $concreteRatio)
    {
        $this->concreteRatio = $concreteRatio;
    }

    public function view(): View
    {
        $this->concreteRatio->load('project', 'creator', 'approver', 'rodCalculation', 'members');
        $service = new ConcreteRatioService();
        $summary = $service->summary($this->concreteRatio);

        return view('admin.finance.concrete-ratios.excel.concrete', [
            'concreteRatio' => $this->concreteRatio,
            'summary'       => $summary,
        ]);
    }

    public function title(): string
    {
        return $this->concreteRatio->reference_no;
    }
}
