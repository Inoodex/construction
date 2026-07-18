<?php

namespace App\Services;

use App\Constants\RodCalculationConstants;
use App\Models\Project;
use App\Models\RodCalculation;
use App\Models\RodMember;
use App\Models\RodMemberBar;

class RodCalculationService
{
    public function generateReferenceNo(Project $project): string
    {
        $code = $project->code ?? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $project->name), 0, 6));
        $year = now()->format('Y');
        $seq = RodCalculation::where('reference_no', 'like', "BBS-{$code}-{$year}-%")
            ->count() + 1;

        return sprintf('BBS-%s-%s-%05d', $code, $year, $seq);
    }

    public function recalculateBar(RodMemberBar $bar): RodMemberBar
    {
        $cover = $bar->member->cover ?? 0;

        $effectiveLength = max(0, $bar->actual_size - ($cover * 2));

        $bar->cutting_length = $effectiveLength + $bar->hook_length + $bar->bend_length + $bar->lap_length;

        if ($bar->is_manual_count || empty($bar->spacing) || $bar->spacing <= 0) {
            $bar->bars_count = max(1, (int) $bar->bars_count);
        } else {
            $bar->bars_count = max(1, (int) floor($effectiveLength / $bar->spacing) + 1);
        }

        $memberQty = $bar->member->quantity ?? 1;
        $bar->total_length = $bar->cutting_length * $bar->bars_count * $memberQty;

        $diameter = $bar->diameter;
        $bar->unit_weight = ($diameter * $diameter) / 162;

        $bar->total_weight = ($bar->total_length / 1000) * $bar->unit_weight;

        return $bar;
    }

    public function recalculateMember(RodMember $member): void
    {
        foreach ($member->bars as $bar) {
            $bar->fill([
                'actual_size'    => $bar->actual_size,
                'spacing'        => $bar->spacing,
                'hook_length'    => $bar->hook_length,
                'bend_length'    => $bar->bend_length,
                'lap_length'     => $bar->lap_length,
                'is_manual_count' => $bar->is_manual_count,
                'bars_count'     => $bar->bars_count,
            ]);
            $this->recalculateBar($bar);
            $bar->save();
        }
    }

    public function recalculateAll(RodCalculation $calc): void
    {
        $calc->load('members.bars');
        foreach ($calc->members as $member) {
            $this->recalculateMember($member);
        }
    }

    public function summary(RodCalculation $calc): array
    {
        $calc->load('members.bars.member');

        $totalKg = 0;
        $byDiameter = [];
        $byMember = [];

        foreach ($calc->members as $member) {
            $memberKg = 0;
            foreach ($member->bars as $bar) {
                $totalKg += $bar->total_weight;
                $memberKg += $bar->total_weight;

                $diameterKey = number_format($bar->diameter, 1);
                if (!isset($byDiameter[$diameterKey])) {
                    $byDiameter[$diameterKey] = [
                        'diameter'   => $bar->diameter,
                        'total_kg'   => 0,
                        'total_mm'   => 0,
                        'bars_count' => 0,
                    ];
                }
                $byDiameter[$diameterKey]['total_kg'] += $bar->total_weight;
                $byDiameter[$diameterKey]['total_mm'] += $bar->total_length;
                $byDiameter[$diameterKey]['bars_count'] += $bar->bars_count;
            }
            $byMember[] = [
                'member_code' => $member->member_code,
                'type'        => $member->type,
                'total_kg'    => round($memberKg, 2),
            ];
        }

        usort($byDiameter, fn($a, $b) => $a['diameter'] <=> $b['diameter']);

        return [
            'total_kg'     => round($totalKg, 2),
            'by_diameter'  => array_values($byDiameter),
            'by_member'    => $byMember,
        ];
    }
}
