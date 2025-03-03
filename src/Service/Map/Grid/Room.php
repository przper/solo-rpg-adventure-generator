<?php

namespace App\Service\Map\Grid;

use App\Helper\Coordinates;

class Room extends Cell
{
    public const TYPE = 'ROOM';

    public function __construct(Coordinates $coordinates)
    {
        parent::__construct($coordinates, self::TYPE);
    }
}