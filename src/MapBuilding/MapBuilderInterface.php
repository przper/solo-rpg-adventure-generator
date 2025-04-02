<?php

namespace App\MapBuilding;

use App\Core\Map\Map;

interface MapBuilderInterface
{
    public function build(): Map;
}
