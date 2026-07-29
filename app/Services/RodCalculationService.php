<?php

namespace App\Services;

use App\Constants\ConcreteRatioConstants;
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

        $steelPrice       = (float) ($calc->steel_price_per_kg ?? 0);
        $wastePercent     = (float) ($calc->waste_percent ?? 0);
        $steelWasteFactor = 1 + ($wastePercent / 100);

        $concWastePercent = (float) ($calc->concrete_waste_percent ?? $calc->waste_percent ?? 0);
        $concWasteFactor  = 1 + ($concWastePercent / 100);

        $cementPrice  = (float) ($calc->cement_price_per_bag ?? 0);
        $sandPrice    = (float) ($calc->sand_price_per_cft ?? 0);
        $aggPrice     = (float) ($calc->aggregate_price_per_cft ?? 0);

        // Concrete summation counters
        $totalVolM3       = 0;
        $totalCementBags  = 0;
        $totalSandM3      = 0;
        $totalAggM3       = 0;
        $totalWaterLiters = 0;
        $m3ToCft          = ConcreteRatioConstants::M3_TO_CFT;

        foreach ($calc->members as $member) {
            $memberKg = 0;
            $hasBars = $member->bars->isNotEmpty();

            foreach ($member->bars as $bar) {
                $totalKg  += $bar->total_weight;
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

            // Concrete Member Calculations (only active after bar data is inputted)
            $concretebd = $hasBars ? $member->concrete_breakdown : [
                'volume_m3'     => 0,
                'volume_cft'    => 0,
                'cement_bags'   => 0,
                'sand_m3'       => 0,
                'sand_cft'      => 0,
                'aggregate_m3'  => 0,
                'aggregate_cft' => 0,
                'water_liters'  => 0,
            ];

            if ($hasBars) {
                $totalVolM3       += $concretebd['volume_m3'];
                $totalCementBags  += $concretebd['cement_bags'];
                $totalSandM3      += $concretebd['sand_m3'];
                $totalAggM3       += $concretebd['aggregate_m3'];
                $totalWaterLiters += $concretebd['water_liters'];
            }

            // Cost per member
            $memberSteelCost = round($memberKg * $steelWasteFactor * $steelPrice, 2);

            $memberCementCost = round($concretebd['cement_bags'] * $concWasteFactor * $cementPrice, 2);
            $memberSandCost   = round($concretebd['sand_cft'] * $concWasteFactor * $sandPrice, 2);
            $memberAggCost    = round($concretebd['aggregate_cft'] * $concWasteFactor * $aggPrice, 2);
            $memberConcCost   = round($memberCementCost + $memberSandCost + $memberAggCost, 2);

            $memberTotalCost  = round($memberSteelCost + $memberConcCost, 2);

            $byMember[$member->id] = [
                'member_id'           => $member->id,
                'member_code'         => $member->member_code,
                'type'                => $member->type,
                'has_bars'            => $hasBars,
                'total_kg'            => round($memberKg, 2),
                'total_tons'          => round($memberKg / 1000, 3),
                'steel_cost'          => $memberSteelCost,
                'volume_m3'           => $concretebd['volume_m3'],
                'volume_cft'          => $concretebd['volume_cft'],
                'cement_bags'         => $concretebd['cement_bags'],
                'sand_cft'            => $concretebd['sand_cft'],
                'aggregate_cft'       => $concretebd['aggregate_cft'],
                'water_liters'        => $concretebd['water_liters'],
                'concrete_cost'       => $memberConcCost,
                'total_member_cost'   => $memberTotalCost,
            ];
        }

        foreach ($byDiameter as $key => $d) {
            $byDiameter[$key]['total_kg'] = round($d['total_kg'], 2);
            $byDiameter[$key]['total_tons'] = round($d['total_kg'] / 1000, 3);
            $byDiameter[$key]['commercial_12m_sticks'] = ceil($d['total_mm'] / 12000);
            $byDiameter[$key]['estimated_cost'] = round($d['total_kg'] * $steelWasteFactor * $steelPrice, 2);
        }

        usort($byDiameter, fn($a, $b) => $a['diameter'] <=> $b['diameter']);

        $wasteKg       = round($totalKg * ($wastePercent / 100), 2);
        $grossTotalKg  = round($totalKg + $wasteKg, 2);

        $totalTons     = round($totalKg / 1000, 3);
        $wasteTons     = round($wasteKg / 1000, 3);
        $grossTotalTons= round($grossTotalKg / 1000, 3);

        $totalSteelCost= round($grossTotalKg * $steelPrice, 2);
        $costPerTon    = round($steelPrice * 1000, 2);

        // Concrete summary & gross materials calculations
        $grossVolM3       = round($totalVolM3 * $concWasteFactor, 4);
        $grossCementBags  = round($totalCementBags * $concWasteFactor, 2);
        $grossSandM3      = round($totalSandM3 * $concWasteFactor, 4);
        $grossAggM3       = round($totalAggM3 * $concWasteFactor, 4);
        $grossWaterLiters = round($totalWaterLiters * $concWasteFactor, 2);

        $totalVolCft = round($totalVolM3 * $m3ToCft, 2);
        $totalSandCft = round($totalSandM3 * $m3ToCft, 2);
        $totalAggCft  = round($totalAggM3 * $m3ToCft, 2);

        $grossVolCft  = round($grossVolM3 * $m3ToCft, 2);
        $grossSandCft = round($grossSandM3 * $m3ToCft, 2);
        $grossAggCft  = round($grossAggM3 * $m3ToCft, 2);

        $cementCost   = round($grossCementBags * $cementPrice, 2);
        $sandCost     = round($grossSandCft * $sandPrice, 2);
        $aggCost      = round($grossAggCft * $aggPrice, 2);
        $totalConcreteCost = round($cementCost + $sandCost + $aggCost, 2);

        $grandTotalSheetCost = round($totalSteelCost + $totalConcreteCost, 2);

        return [
            'total_kg'             => round($totalKg, 2),
            'total_tons'           => $totalTons,
            'waste_percent'        => $wastePercent,
            'waste_kg'             => $wasteKg,
            'waste_tons'           => $wasteTons,
            'gross_total_kg'       => $grossTotalKg,
            'gross_total_tons'     => $grossTotalTons,
            'total_12m_sticks'     => array_sum(array_column($byDiameter, 'commercial_12m_sticks')),
            'steel_price_per_kg'   => $steelPrice,
            'cost_per_ton'         => $costPerTon,
            'total_steel_cost'     => $totalSteelCost,
            'by_diameter'          => array_values($byDiameter),
            'by_member'            => $byMember,

            // Concrete Summary Details
            'concrete_grade'          => $calc->concrete_grade,
            'concrete_waste_percent'  => $concWastePercent,
            'total_volume_m3'         => round($totalVolM3, 4),
            'total_volume_cft'        => $totalVolCft,
            'total_cement_bags'       => round($totalCementBags, 2),
            'total_sand_m3'           => round($totalSandM3, 4),
            'total_sand_cft'          => $totalSandCft,
            'total_aggregate_m3'      => round($totalAggM3, 4),
            'total_aggregate_cft'     => $totalAggCft,
            'total_water_liters'      => round($totalWaterLiters, 2),
            'gross_volume_m3'         => $grossVolM3,
            'gross_volume_cft'        => $grossVolCft,
            'gross_cement_bags'       => $grossCementBags,
            'gross_sand_m3'           => $grossSandM3,
            'gross_sand_cft'          => $grossSandCft,
            'gross_aggregate_m3'      => $grossAggM3,
            'gross_aggregate_cft'     => $grossAggCft,
            'gross_water_liters'      => $grossWaterLiters,
            'cement_price_per_bag'    => $cementPrice,
            'sand_price_per_cft'      => $sandPrice,
            'aggregate_price_per_cft' => $aggPrice,
            'cement_cost'             => $cementCost,
            'sand_cost'               => $sandCost,
            'aggregate_cost'          => $aggCost,
            'total_concrete_cost'     => $totalConcreteCost,

            // Grand Total (Steel + Concrete)
            'grand_total_sheet_cost'  => $grandTotalSheetCost,
        ];
    }
}
