<?php

namespace App\Constants;

class ConcreteRatioConstants
{
    const STATUS_DRAFT     = 'draft';
    const STATUS_APPROVED  = 'approved';
    const STATUS_COMPLETED = 'completed';

    const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_APPROVED,
        self::STATUS_COMPLETED,
    ];

    const M3_TO_CFT = 35.3147;

    const GRADES = ['M10', 'M15', 'M20', 'M25', 'M30', 'M35', 'M40'];

    const GRADE_LABELS = [
        'M10' => 'M10 (1:3:6)',
        'M15' => 'M15 (1:2:4)',
        'M20' => 'M20 (1:1.5:3)',
        'M25' => 'M25 (1:1:2)',
        'M30' => 'M30 (Design Mix ~1:0.75:1.5)',
        'M35' => 'M35 (Design Mix ~1:0.5:1)',
        'M40' => 'M40 (Design Mix ~1:0.4:0.8)',
    ];

    const MATERIAL_FACTORS = [
        'M10' => ['cement_bags' => 4.4,  'sand_m3' => 0.46, 'aggregate_m3' => 0.92, 'water_liters' => 205],
        'M15' => ['cement_bags' => 6.6,  'sand_m3' => 0.45, 'aggregate_m3' => 0.90, 'water_liters' => 205],
        'M20' => ['cement_bags' => 8.22, 'sand_m3' => 0.42, 'aggregate_m3' => 0.84, 'water_liters' => 205],
        'M25' => ['cement_bags' => 10.0, 'sand_m3' => 0.39, 'aggregate_m3' => 0.78, 'water_liters' => 205],
        'M30' => ['cement_bags' => 11.4, 'sand_m3' => 0.36, 'aggregate_m3' => 0.72, 'water_liters' => 205],
        'M35' => ['cement_bags' => 12.8, 'sand_m3' => 0.33, 'aggregate_m3' => 0.66, 'water_liters' => 205],
        'M40' => ['cement_bags' => 14.0, 'sand_m3' => 0.31, 'aggregate_m3' => 0.62, 'water_liters' => 205],
    ];
}
