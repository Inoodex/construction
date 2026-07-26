<?php

namespace App\Services;

use App\Constants\RodCalculationConstants;
use App\Models\Project;
use App\Models\RodCalculation;
use App\Models\RodMember;
use App\Models\RodMemberBar;

class RodCalculationService
{
    public function determineActualSize(RodMember $member, string $direction): float
    {
        $l = (float) ($member->length ?? 0);
        $w = (float) ($member->width ?? 0);
        $h = (float) ($member->height ?? $member->depth ?? 0);
        $t = (float) ($member->thickness ?? 0);

        switch (strtoupper($direction)) {
            case 'X':
                return $l > 0 ? $l : ($w > 0 ? $w : $h);
            case 'Y':
                return $w > 0 ? $w : ($l > 0 ? $l : $h);
            case 'MAIN':
            case 'TOP':
            case 'BOTTOM':
                if (strtolower($member->type) === 'column' && $h > 0) {
                    return $h;
                }
                return $l > 0 ? $l : ($w > 0 ? $w : ($h > 0 ? $h : $t));
            case 'DISTRIBUTION':
                return $w > 0 ? $w : ($l > 0 ? $l : $t);
            case 'STIRRUP':
                if ($w > 0 && $h > 0) {
                    return 2 * ($w + $h);
                }
                return $w > 0 ? $w : $h;
            default:
                return $l > 0 ? $l : ($w > 0 ? $w : ($h > 0 ? $h : $t));
        }
    }

    public function determineHookLength(float $diameter, string $direction): float
    {
        $dir = strtoupper($direction);

        if ($dir === 'STIRRUP') {
            return round(20 * $diameter, 2);
        }

        if (in_array($dir, ['MAIN', 'TOP', 'BOTTOM', 'X', 'Y'])) {
            return round(18 * $diameter, 2);
        }

        return 0;
    }

    public function generateReferenceNo(Project $project): string
    {
        $code = $project->code ?? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $project->name), 0, 6));
        $year = now()->format('Y');

        do {
            $seq = RodCalculation::withTrashed()
                ->where('reference_no', 'like', "BBS-{$code}-{$year}-%")
                ->count() + 1;
            $ref = sprintf('BBS-%s-%s-%05d', $code, $year, $seq);
        } while (RodCalculation::withTrashed()->where('reference_no', $ref)->exists());

        return $ref;
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

        foreach ($byDiameter as $key => $d) {
            $byDiameter[$key]['commercial_12m_sticks'] = ceil($d['total_mm'] / 12000);
        }

        usort($byDiameter, fn($a, $b) => $a['diameter'] <=> $b['diameter']);

        $wastePercent = (float) ($calc->waste_percent ?? 0);
        $wasteKg = round($totalKg * ($wastePercent / 100), 2);
        $grossTotalKg = round($totalKg + $wasteKg, 2);

        return [
            'total_kg'             => round($totalKg, 2),
            'waste_percent'        => $wastePercent,
            'waste_kg'             => $wasteKg,
            'gross_total_kg'       => $grossTotalKg,
            'total_12m_sticks'     => array_sum(array_column($byDiameter, 'commercial_12m_sticks')),
            'by_diameter'          => array_values($byDiameter),
            'by_member'            => $byMember,
        ];
    }
}
