<?php

namespace App\Constants;

class RodMemberType
{
    const FOOTING    = 'footing';
    const PILE_CAP   = 'pile_cap';
    const PILE       = 'pile';
    const COLUMN     = 'column';
    const BEAM       = 'beam';
    const GRADE_BEAM = 'grade_beam';
    const LINTEL     = 'lintel';
    const SLAB       = 'slab';
    const WALL       = 'wall';
    const STAIR      = 'stair';
    const CUSTOM     = 'custom';

    const ALL = [
        self::FOOTING, self::PILE_CAP, self::PILE, self::COLUMN,
        self::BEAM, self::GRADE_BEAM, self::LINTEL, self::SLAB,
        self::WALL, self::STAIR, self::CUSTOM,
    ];

    const LABELS = [
        self::FOOTING    => 'Footing',
        self::PILE_CAP   => 'Pile Cap',
        self::PILE       => 'Pile',
        self::COLUMN     => 'Column',
        self::BEAM       => 'Beam',
        self::GRADE_BEAM => 'Grade Beam',
        self::LINTEL     => 'Lintel',
        self::SLAB       => 'Slab',
        self::WALL       => 'Wall',
        self::STAIR      => 'Stair',
        self::CUSTOM     => 'Custom',
    ];

    const DEFAULT_COVER = [
        self::FOOTING    => 50,
        self::PILE_CAP   => 50,
        self::PILE       => 50,
        self::COLUMN     => 40,
        self::BEAM       => 25,
        self::GRADE_BEAM => 40,
        self::LINTEL     => 25,
        self::SLAB       => 20,
        self::WALL       => 25,
        self::STAIR      => 20,
        self::CUSTOM     => 25,
    ];
}
