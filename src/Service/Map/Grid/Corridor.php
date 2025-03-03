<?php

namespace App\Service\Map\Grid;

use App\Helper\Coordinates;

class Corridor extends Cell
{
    public const TYPE = 'CORRIDOR';

    public function __construct(Coordinates $coordinates)
    {
        parent::__construct($coordinates, self::TYPE);
    }
}