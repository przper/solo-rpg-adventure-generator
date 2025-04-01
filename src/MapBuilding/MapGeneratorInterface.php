<?php

namespace App\MapBuilding;

use App\Core\Map\Map;

interface MapGeneratorInterface
{
    public function create(): Map;
}
