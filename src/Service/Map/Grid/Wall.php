<?php

namespace App\Service\Map\Grid;

use App\Helper\Coordinates;

class Wall extends Cell
{
    public const TYPE = 'WALL';

    public function __construct(Coordinates $coordinates)
    {
        parent::__construct($coordinates, self::TYPE);
    }
}