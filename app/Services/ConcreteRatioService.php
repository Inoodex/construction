<?php

namespace App\Services;

use App\Constants\ConcreteRatioConstants;
use App\Models\ConcreteRatio;
use App\Models\ConcreteRatioMember;
use App\Models\Project;

class ConcreteRatioService
{
    public function generateReferenceNo(Project $project): string
    {
        $code = $project->code ?? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $project->name), 0, 6));
        $year = now()->format('Y');

        do {
            $seq = ConcreteRatio::withTrashed()
                ->where('reference_no', 'like', "CR-{$code}-{$year}-%")
                ->count() + 1;
            $ref = sprintf('CR-%s-%s-%05d', $code, $year, $seq);
        } while (ConcreteRatio::withTrashed()->where('reference_no', $ref)->exists());

        return $ref;
    }

    public function calculateMember(ConcreteRatioMember $member, ?string $grade): void
    {
        $length    = (float) ($member->length ?? 0);
        $width     = (float) ($member->width ?? 0);
        $height    = (float) ($member->height ?? 0);
        $quantity  = max(1, (int) $member->quantity);

        $member->volume_m3 = round(($length * $width * $height) / 1_000_000_000 * $quantity, 4);

        if ($grade && isset(ConcreteRatioConstants::MATERIAL_FACTORS[$grade])) {
            $factors = ConcreteRatioConstants::MATERIAL_FACTORS[$grade];
            $vol     = $member->volume_m3;

            $member->cement_bags  = round($vol * $factors['cement_bags'], 2);
            $member->sand_m3      = round($vol * $factors['sand_m3'], 4);
            $member->aggregate_m3 = round($vol * $factors['aggregate_m3'], 4);
            $member->water_liters = round($vol * $factors['water_liters'], 2);
        } else {
            $member->cement_bags  = 0;
            $member->sand_m3      = 0;
            $member->aggregate_m3 = 0;
            $member->water_liters = 0;
        }
    }

    public function recalculateMember(ConcreteRatioMember $member): void
    {
        $grade = $member->ratio->grade ?? null;
        $this->calculateMember($member, $grade);
        $member->save();
    }

    public function recalculateAll(ConcreteRatio $ratio): void
    {
        $ratio->load('members');
        foreach ($ratio->members as $member) {
            $this->calculateMember($member, $ratio->grade);
            $member->save();
        }
    }

    public function summary(ConcreteRatio $ratio): array
    {
        $ratio->load('members');

        $totalVolume    = 0;
        $totalCement    = 0;
        $totalSand      = 0;
        $totalAggregate = 0;
        $totalWater     = 0;
        $byMember       = [];

        foreach ($ratio->members as $member) {
            $totalVolume    += $member->volume_m3;
            $totalCement    += $member->cement_bags;
            $totalSand      += $member->sand_m3;
            $totalAggregate += $member->aggregate_m3;
            $totalWater     += $member->water_liters;

            $byMember[] = [
                'member_code' => $member->member_code,
                'type'        => $member->type,
                'volume_m3'   => round($member->volume_m3, 4),
                'cement_bags' => round($member->cement_bags, 2),
                'sand_m3'     => round($member->sand_m3, 4),
                'aggregate_m3'=> round($member->aggregate_m3, 4),
                'water_liters'=> round($member->water_liters, 2),
            ];
        }

        return [
            'total_volume_m3'    => round($totalVolume, 4),
            'total_cement_bags'  => round($totalCement, 2),
            'total_sand_m3'      => round($totalSand, 4),
            'total_aggregate_m3' => round($totalAggregate, 4),
            'total_water_liters' => round($totalWater, 2),
            'by_member'          => $byMember,
        ];
    }
}
