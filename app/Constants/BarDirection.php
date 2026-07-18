<?php

namespace App\Constants;

class BarDirection
{
    const X              = 'X';
    const Y              = 'Y';
    const TOP            = 'TOP';
    const BOTTOM         = 'BOTTOM';
    const MAIN           = 'MAIN';
    const STIRRUP        = 'STIRRUP';
    const DISTRIBUTION   = 'DISTRIBUTION';
    const OTHER          = 'OTHER';

    const ALL = [
        self::X, self::Y, self::TOP, self::BOTTOM,
        self::MAIN, self::STIRRUP, self::DISTRIBUTION, self::OTHER,
    ];
}
