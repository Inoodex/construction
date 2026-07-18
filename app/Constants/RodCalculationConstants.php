<?php

namespace App\Constants;

class RodCalculationConstants
{
    const STATUS_DRAFT     = 'draft';
    const STATUS_APPROVED  = 'approved';
    const STATUS_COMPLETED = 'completed';

    const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_APPROVED,
        self::STATUS_COMPLETED,
    ];

    const STEEL_GRADES = ['FY400', 'FY500', 'FY550'];

    const FORMULA_VERSION = '1.0';

    const DIAMETERS = [8, 10, 12, 16, 20, 25, 32];
}
